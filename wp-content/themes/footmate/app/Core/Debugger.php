<?php

namespace FM\Core;

use Spatie\Ignition\Ignition;

class Debugger
{
    public function __construct()
    {
        Ignition::make()->register();
    }
}
