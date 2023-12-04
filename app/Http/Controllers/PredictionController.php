<?php

namespace App\Http\Controllers;

use App\Repositories\GameRepository;
use App\Repositories\StandingRepository;
use App\Services\Prediction\SimplePrediction;

class PredictionController extends Controller
{
    public function __construct(private StandingRepository $standingRepository, private GameRepository $gameRepository)
    {
    }

    public function getPrediction()
    {
        $chart = (new SimplePrediction($this->standingRepository, $this->gameRepository))->getPrediction();
        return response()->json([
            'status' => 'ok',
            'items' => $chart
        ], 200);
    }
}
