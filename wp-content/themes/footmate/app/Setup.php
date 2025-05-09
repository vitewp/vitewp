<?php

namespace FM;

class Setup
{
    /**
     * @action after_setup_theme
     */
    public function supports(): void
    {
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
    }

    /**
     * @action after_setup_theme
     */
    public function menus(): void
    {
        register_nav_menus(
            [
                'primary_navigation' => __('Primary Navigation', 'fm'),
            ]
        );
    }
}
