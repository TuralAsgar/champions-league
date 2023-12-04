<?php
namespace App\Repositories;

use App\Models\Standing;
use App\Models\Team;

class StandingRepository
{
    public function __construct(private Standing $standing, private Team $team)
    {
    }

    public function checkStanding()
    {
        $result = $this->standing->get();
        return $result->isEmpty() ? false : true;
    }

    public function createStanding()
    {
        $result = $this->standing->get();
        if (!$result->isEmpty()) {
            return;
        }
        foreach ($this->getTeams() as $value) {
            $data = [
                'team_id' => $value,
                'played' => 0,
                'won' => 0,
                'lose' => 0,
                'draw' => 0,
                'goal_difference' => 0,
                'points' => 0
            ];
            $this->standing->create($data);
        }

    }

    public function getTeams()
    {
        return $this->team->pluck('id');
    }

    public function getAll()
    {
        return $this->team->leftJoin('standings', 'teams.id', '=', 'standings.team_id')
            ->orderBy('standings.points', 'DESC')
            ->orderBy('standings.goal_difference', 'DESC')
            ->orderBy('standings.won', 'DESC')
            ->get();
    }

    public function getStandingByTeamId($team_id)
    {
        return $this->standing->where('team_id', $team_id)->first();
    }

    public function truncateStanding()
    {
        $this->standing->truncate();
    }

    public function checkStandingStatus()
    {
        return $this->standing->select('played')->first();
    }

}
