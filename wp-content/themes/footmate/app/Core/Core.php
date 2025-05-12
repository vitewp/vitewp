<?php

namespace FM\Core;

use FM\Core\Debugger;

class Core
{
    public function __construct()
    {
        if (! empty(WP_DEBUG) && ! empty(WP_ENVIRONMENT_TYPE) && WP_ENVIRONMENT_TYPE === 'development') {
            \FM\App::init(new Debugger());
        }
    }
}
