<?php

namespace App\Http\Controllers;

use App\Repositories\GameRepository;
use App\Repositories\StandingRepository;
use App\Services\Simulator\GameSimulator;

class SimulatorController extends Controller
{
    public function __construct(private StandingRepository $standingRepository, private GameRepository $gameRepository)
    {
    }

    public function playAllWeeks()
    {
        $games = $this->gameRepository->getAllGames();
        (new GameSimulator($this->standingRepository, $this->gameRepository))->bulkSimulate($games);
        return response()->json(['status' => 'ok'], 200);
    }

    public function playWeekly($week)
    {
        $games = $this->gameRepository->getGamesFromWeek($week);
        (new GameSimulator($this->standingRepository, $this->gameRepository))->bulkSimulate($games);
        $result = $this->gameRepository->getFixtureByWeekId($week);

        return response()->json([
            'status' => 'ok',
            'games' => $result
        ], 201);
    }
}
