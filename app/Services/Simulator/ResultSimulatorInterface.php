<?php
/**
 * Created by PhpStorm.
 * User: vahid
 * Date: 3/14/19
 * Time: 9:24 PM
 */

namespace App\Services\Simulator;


interface ResultSimulatorInterface
{
    public function simulate($singleInput);

    public function bulkSimulate($arrayInput);
}
