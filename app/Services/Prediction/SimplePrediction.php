<?php
namespace App\Services\Prediction;

use App\Repositories\GameRepository;
use App\Repositories\StandingRepository;

class SimplePrediction implements PredictionInterface
{
    public function __construct(protected StandingRepository $standingRepository, protected GameRepository $gameRepository)
    {
    }

    public function getPrediction(): array
    {
        $finished = $this->standingRepository->getAll();
        if (!$this->checkIfPredictionIsNeeded()) {
            return [];
        }
        $teams = $this->collectionPredictions($finished);

        //get top team current point and number of fixture remained for each team
        $remainedPoints = 3 * (6 - $teams[1]['played']);
        $topTeamPoint = $teams[1]['points'];

        $rawPrediction = [];
        foreach ($teams as $rank => $team) {
            $rawPrediction[$team['team_name']] = $this->calculateTeamChance($team, $rank, $remainedPoints,
                $topTeamPoint);
        }

        return $this->calculateChanceInPercentage($rawPrediction);
    }

    public function checkIfPredictionIsNeeded()
    {
        $played = $this->standingRepository->checkStandingStatus();
        if ($played->played == 0 || $played->played == 6) {
            return false;
        }
        return true;
    }

    private function collectionPredictions($data)
    {
        $teams = [];
        $collection = collect($data);
        $collection->each(function ($item) use (&$teams) {
            $teams[$item->team_id]['points'] = $item->points;
            $teams[$item->team_id]['played'] = $item->played;
            $teams[$item->team_id]['team_id'] = $item->team_id;
            $teams[$item->team_id]['team_name'] = $item->name;
        });
        return $teams;
    }

    public function calculateTeamChance($team, $rank, $remainedPoints, $topTeamPoint)
    {
        //check if team can be champions if win all future games due to current top team
        if ($remainedPoints + $team['points'] < $topTeamPoint) {
            return 0;
        }
        $homeChance = 0;
        $awayChance = 0;

        $games = $this->gameRepository->getAllGamesByTeamId($team['team_id']);

        foreach ($games as $game) {
            if ($game->home_team == $team['team_id']) {
                $homeChance += 2;
            }

            if ($game->away_team == $team['team_id']) {
                $awayChance += 1;
            }
        }

        $chanceByRemainedGames = ($homeChance + $awayChance);
        $chanceIncludingCurrentRank = $chanceByRemainedGames - ($rank / 2);
        $chanceIncludingPointsDifference = $chanceIncludingCurrentRank - (($topTeamPoint - $team['points']) / 2);
        return $chanceIncludingPointsDifference > 0 ? $chanceIncludingPointsDifference : 0;

    }

    //before first week and after last week there is no prediction needed

    public function calculateChanceInPercentage($rawPrediction)
    {
        $onePointPercent = 100 / array_sum($rawPrediction);

        $chanceInPercentage = [];
        foreach ($rawPrediction as $teamId => $teamChance) {
            $chanceInPercentage[$teamId] = round($teamChance * $onePointPercent, 2);
        }

        return $chanceInPercentage;
    }
}
