<?php
namespace App\Services\Simulator;

use App\Repositories\GameRepository;
use App\Repositories\StandingRepository;

class GameSimulator implements ResultSimulatorInterface
{
    protected $standingRepository;
    protected $gameRepository;

    public function __construct(StandingRepository $standingRepository, GameRepository $gameRepository)
    {
        $this->standingRepository = $standingRepository;
        $this->gameRepository = $gameRepository;
    }

    public function bulkSimulate($games)
    {
        foreach ($games as $game) {
            $this->simulate($game);
        }
    }

    public function simulate($game)
    {
        $home = $this->standingRepository->getStandingByTeamId($game->home_team);
        $away = $this->standingRepository->getStandingByTeamId($game->away_team);
        $homeScore = $this->generateScore(true, $home->id);
        $awayScore = $this->generateScore(false, $away->id);

        $this->updateGameScore($homeScore, $awayScore, $home, $away);
        return $this->gameRepository->resultSaver($game, $homeScore, $awayScore);
    }

    public function generateScore(bool $is_home, int $teamRank)
    {
        // this generator is assuming home team and also current rank to generate result
        return $is_home ? rand(0, 10) : rand(0, 10 - $teamRank);
    }

    public function updateGameScore($homeScore, $awayScore, $home, $away)
    {
        $this->gameRepository->updateGameScore($homeScore, $awayScore, $home, $away);
    }
}
