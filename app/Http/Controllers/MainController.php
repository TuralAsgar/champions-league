<?php

namespace App\Http\Controllers;

use App\Repositories\GameRepository;
use App\Repositories\StandingRepository;
use App\Services\FixtureDraw\HomeAndAwayDraw;
use App\Services\Prediction\SimplePrediction;

class MainController extends Controller
{
    private $standingRepository;
    private $gameRepository;

    public function __construct(StandingRepository $standingRepository, GameRepository $gameRepository)
    {
        $this->standingRepository = $standingRepository;
        $this->gameRepository = $gameRepository;
        $this->handleRequirements();
    }

    public function handleRequirements()
    {
        //check if there is no standing, then make it
        if (!$this->standingRepository->checkStanding()) {
            $this->standingRepository->createStanding();
        }
        //check if all games are drawn
        if (!$this->gameRepository->checkIfFixturesDrawn()) {
            $this->makeFixtures();
        }
    }

    public function makeFixtures()
    {
        $drawService = new HomeAndAwayDraw($this->gameRepository->getTeamsId());
        $this->gameRepository->createFixture($drawService->getFixturesPlan());
    }

    public function getStarting()
    {
        $games = $this->gameRepository->getFixture()->groupBy('week_id');
        $predictions = (new SimplePrediction($this->standingRepository, $this->gameRepository))->getPrediction();

        return view(
            'landing',
            [
                'standing' => $this->standingRepository->getAll(),
                'weeks' => $this->gameRepository->getWeeks(),
                'games' => $games,
                'predictions' => $predictions
            ]);
    }

    public function resetAll()
    {
        $this->gameRepository->truncateGames();
        $this->standingRepository->truncateStanding();
        $this->makeFixtures();
        return response()->json(['status' => 'ok'], 200);
    }

    public function getStandings()
    {
        return response()->json($this->standingRepository->getAll());
    }

    public function getFixtures()
    {
        $weeks = $this->gameRepository->getWeeks();
        $fixture = $this->gameRepository->getFixture()->groupBy('week_id');
        return response()->json(['weeks' => $weeks, 'items' => $fixture]);
    }

}
