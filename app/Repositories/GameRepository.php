<?php
/**
 * Created by PhpStorm.
 * User: vahid
 * Date: 3/14/19
 * Time: 2:35 PM
 */

namespace App\Repositories;


use App\Models\Game;
use App\Models\Team;
use App\Models\Week;

class GameRepository
{

    protected $team;
    protected $game;
    protected $week;

    public function __construct(Team $team, Game $game, Week $week)
    {
        $this->team = $team;
        $this->game = $game;
        $this->week = $week;
    }


    public function getTeamsId()
    {
        return $this->team->pluck('id')->toArray();
    }


    public function getWeeksId()
    {
        return $this->week->pluck('id');
    }


    public function getWeeks()
    {
        return $this->week->get();
    }

    public function createFixture($fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->game->create([
                'home_team' => $fixture['home'],
                'away_team' => $fixture['away'],
                'week_id' => $fixture['week']
            ]);
        }
    }

    public function checkIfFixturesDrawn()
    {
        return $this->game->count() ? true : false;
    }


    /**
     * @return mixed
     */
    public function getFixture()
    {
        return $this->game->select(
            'games.id',
            'games.status',
            'games.week_id',
            'games.home_team_goal',
            'games.away_team_goal',
            'week_id',
            'home.name as home_team',
            'home.logo as home_logo',
            'home.shirt as home_shirt',
            'away.logo as away_logo',
            'away.shirt as away_shirt',
            'away.name as away_team')
            ->join('weeks', 'weeks.id', '=', 'games.week_id')
            ->join('teams as home', 'home.id', '=', 'games.home_team')
            ->join('teams as away', 'away.id', '=', 'games.away_team')
            ->orderBy('week_id', 'ASC')
            ->get();
    }

    /**
     * @param $week_id
     * @return mixed
     */
    public function getFixtureByWeekId($week_id)
    {

        return $this->game->select(
            'games.id',
            'games.status',
            'games.week_id',
            'games.home_team_goal',
            'games.away_team_goal',
            'week_id',
            'weeks.title',
            'home.logo as home_logo',
            'away.logo as away_logo',
            'home.name as home_team',
            'away.name as away_team')
            ->join('weeks', 'weeks.id', '=', 'games.week_id')
            ->join('teams as home', 'home.id', '=', 'games.home_team')
            ->join('teams as away', 'away.id', '=', 'games.away_team')
            ->where('games.week_id', '=', $week_id)
            ->orderBy('games.id', 'ASC')
            ->get();
    }

    /**
     * @param $week
     * @return mixed
     */
    public function getGamesFromWeek($week)
    {
        return $this->game->where([['week_id', '=', $week], ['status', '=', 0]])->get();
    }


    public function getAllGames($status = 0)
    {
        return $this->game->where('status', '=', $status)->get();
    }

    public function getAllGamesByTeamId($teamId)
    {
        return $this->game
            ->where(function ($q) use ($teamId) {
                $q->where('home_team', '=', $teamId)
                    ->orWhere('away_team', '=', $teamId);
            })
            ->where('status', '=', 0)
            ->get();
    }

    /**
     * @param $homeScore
     * @param $awayScore
     * @param $home
     * @param $away
     */
    public function updateGameScore($homeScore, $awayScore, $home, $away)
    {
        $goalDrawn = abs($awayScore - $homeScore);

        if ($homeScore > $awayScore) {
            $home->won($goalDrawn);
            $away->lose($goalDrawn);

        } elseif ($awayScore > $homeScore) {
            $away->won($goalDrawn);
            $home->lose($goalDrawn);
        } else {
            $home->draw();
            $away->draw();
        }

        $home->save();
        $away->save();
    }


    /**
     *
     */
    public function truncateGames()
    {
        $this->game->truncate();
    }

    public function resultSaver($match, $homeScore, $awayScore)
    {
        $match->home_team_goal = $homeScore;
        $match->away_team_goal = $awayScore;
        $match->status = 1;
        return $match->save();
    }

}
