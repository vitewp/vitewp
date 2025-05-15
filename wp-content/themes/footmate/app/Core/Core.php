<?php

namespace FM\Core;

use FM\Core\Debugger;

class Core
{
    public function __construct()
    {
        if (! empty(WP_DEBUG)) {
            \FM\App::init(new Debugger());
        }
    }
}
