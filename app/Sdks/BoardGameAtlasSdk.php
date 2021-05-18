<?php
namespace App\Sdks;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class BoardGameAtlasSdk
{
    private $clientId;


    public function __construct() {
        $this->clientId = 'JLBr5npPhV';
    }

    public function getAllGames() {
        $skip = 0;
        $gamesCount = 100;
        $games = [];
        while($gamesCount === 100) {
            $response = Http::get('https://api.boardgameatlas.com/api/search', [
                'client_id' => $this->clientId,
                'fields' => 'name,image_url,min_players,max_players,year_published,min_playtime,max_playtime,description,mechanics,categories',
                'limit' => 100,
                'skip' => $skip,
                'order_by' => '^A'
            ]);


            Log::info($response->getStatusCode());
            if($response->getStatusCode() === 403) {
                sleep ( 60);
                continue;
            }

            $fetchedGames = json_decode($response->getBody()->getContents());
            $gamesCount = count($fetchedGames->games);
            $skip += 100;
            Log::info(json_encode($fetchedGames->games[0]));
        }
    }

    public function getCategories() {
        $response = Http::get('https://api.boardgameatlas.com/api/game/categories', [
            'client_id' => $this->clientId,
        ]);
        dd(json_decode($response->getBody()->getContents()));

    }


}
