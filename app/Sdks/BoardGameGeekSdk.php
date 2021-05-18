<?php
namespace App\Sdks;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Mechanic;
use App\Models\Profile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;


class BoardGameGeekSdk
{
    private $url;


    public function __construct() {
        $this->url = 'https://www.boardgamegeek.com/xmlapi2/thing?type=boardgame';
    }

    public function getAllGames($start = 0) {
        $handle = fopen(storage_path('app/boardgamelist/2021-05-18.json'), "r");
        $header = true;
        $boardGameIds = [];
        while ($csvLine = fgetcsv($handle, 1000, ",")) {

            if ($header) {
                $header = false;
            } else {
                $boardGameIds[] = $csvLine[0];

            }
        }
        for($boardGameCounter = $start; $boardGameCounter < count($boardGameIds); $boardGameCounter += 100) {
            $hundredBoardGames = array_slice($boardGameIds, $boardGameCounter, 100);
            $response = Http::get($this->url, [
                'page' => 1,
                'pagesize' => 3,
                'id' => implode(',',$hundredBoardGames),
            ]);
            try {
                $xml = new SimpleXMLElement($response->getBody()->getContents());
                $boardgamesJson = json_decode(json_encode($xml));
                foreach($boardgamesJson->item as $boardgame) {
                    $boardgameValues = [];

                    //GET ALL THE DIFFERENT VALUES FROM THE JSON
                    $boardgameValues['image'] = $boardgame->image;
                    $boardgameValues['thumbnail'] = $boardgame->thumbnail;
                    $boardgameValues['description'] = $boardgame->description;
                    if(is_array($boardgame->name)) {
                        $boardgameValues['title'] = current(
                            array_filter(
                                $boardgame->name,
                                fn($e) => $e->{'@attributes'}->type === "primary"
                            )
                        )->{'@attributes'}->value;

                    } else {
                        $boardgameValues['title'] = $boardgame->name->{'@attributes'}->value;
                    }
                    $boardgameValues['year_published'] = $boardgame->yearpublished->{'@attributes'}->value;
                    $boardgameValues['min_players'] = $boardgame->minplayers->{'@attributes'}->value;
                    $boardgameValues['max_players'] = $boardgame->maxplayers->{'@attributes'}->value;
                    $boardgameValues['min_play_time'] = $boardgame->minplaytime->{'@attributes'}->value;
                    $boardgameValues['max_play_time'] = $boardgame->maxplaytime->{'@attributes'}->value;
                    $boardgameValues['min_age'] = $boardgame->minage->{'@attributes'}->value;


                    $boardGameGenres = [];
                    $boardGameMechanics = [];

                    foreach($boardgame->link as $boardgameAttribute) {
                        if($boardgameAttribute->{'@attributes'}->type === 'boardgamecategory')
                            $boardGameGenres[] = $boardgameAttribute->{'@attributes'}->value;
                        if($boardgameAttribute->{'@attributes'}->type === 'boardgamemechanic')
                            $boardGameMechanics[] = $boardgameAttribute->{'@attributes'}->value;
                    }


                    $createdBoardgame = Game::updateOrCreate(
                        [
                            'title' => $boardgameValues['title']
                        ],
                        [
                            'image' => $boardgameValues['image'],
                            'year_published' => $boardgameValues['year_published'],
                            'min_players' => $boardgameValues['min_players'],
                            'max_players' => $boardgameValues['max_players'],
                            'min_play_time' => $boardgameValues['min_play_time'],
                            'max_play_time' => $boardgameValues['max_play_time'],
                            'min_age' => $boardgameValues['min_age'],
                            'thumbnail' => $boardgameValues['thumbnail'],
                            'description' => $boardgameValues['description'],
                            'public' => 1

                        ]
                    );

                    foreach($boardGameGenres as $genre) {
                        $savedGenre = Genre::firstOrCreate([
                            'title' => $genre
                        ]);
                        $createdBoardgame->genres()->syncWithoutDetaching($savedGenre);
                    }

                    foreach($boardGameMechanics as $mechanic) {
                        $savedMechanic = Mechanic::firstOrCreate([
                            'title' => $mechanic
                        ]);
                        $createdBoardgame->mechanics()->syncWithoutDetaching($savedMechanic);
                    }

                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e])->setStatusCode(400);
            }

        }

    }


}
