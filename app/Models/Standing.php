<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'points',
        'won',
        'lose',
        'draw',
        'played',
        'goal_difference'
    ];

    public function won($goalDifference)
    {
        $this->played += 1;
        $this->won += 1;
        $this->points += 3;
        $this->goal_difference += $goalDifference;
    }

    public function lose($goalDifference)
    {
        $this->played += 1;
        $this->goal_difference += -$goalDifference;
        $this->lose += 1;
    }

    public function draw()
    {
        $this->played += 1;
        $this->draw += 1;
        $this->points += 1;
    }
}
