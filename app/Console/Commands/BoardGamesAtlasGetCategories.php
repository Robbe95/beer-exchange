<?php

namespace App\Console\Commands;

use App\Sdks\BoardGameAtlasSdk;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BoardGamesAtlasGetCategories extends Command
{
    protected $signature = 'games:categories';

    protected $description = 'Clean logs';

    public function __construct()
    {
        parent::__construct();

    }

    public function handle()
    {
        $boardGames = new BoardGameAtlasSdk();
        $boardGames->getCategories();
    }
}
