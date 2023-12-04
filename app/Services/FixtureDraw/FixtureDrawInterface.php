<?php
/**
 * Created by PhpStorm.
 * User: vahid
 * Date: 3/15/19
 * Time: 8:33 PM
 */

namespace App\Services\FixtureDraw;


interface FixtureDrawInterface
{

    public function __construct(array $teams);

    public function getFixturesPlan();

}
