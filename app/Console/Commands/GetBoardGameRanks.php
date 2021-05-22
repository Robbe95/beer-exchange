<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Genre;
use App\Models\Mechanic;
use App\Sdks\BoardGameAtlasSdk;
use App\Sdks\BoardGameGeekSdk;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class GetBoardGameRanks extends Command
{
    protected $signature = 'games:getranks {start? : Start from where }';

    protected $description = 'Clean logs';
    private $url;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $bar = $this->output->createProgressBar(20000);
        $boardGames = Game::all();
        $start = $this->argument('start') ?? 0;
        $handle = fopen(storage_path('app/boardgamelist/2021-05-18.json'), "r");
        $header = true;
        $boardGameIds = [];
        while ($csvLine = fgetcsv($handle, 1000, ",")) {

            if ($header) {
                $header = false;
            } else {
                $boardGameIds[] = $csvLine[0];
                $game = $boardGames->where('title', $csvLine[1])->first();
                if($game) {
                    $game->rank = $csvLine[3];
                    $game->average = $csvLine[4];
                    $game->bayes_average = $csvLine[5];
                    $game->users_rated = $csvLine[6];

                    $game->save();

                }
                $bar->advance(1);

            }
        }
        return 1;
    }
}
