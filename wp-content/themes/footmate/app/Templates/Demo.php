<?php

namespace FM\Templates;

use FM\Templates\Template;

class Demo extends Template
{
    public function __construct()
    {
        $this->setId('demo');
        $this->setTitle('Demo');
        $this->setSchema([]);
        $this->setData([]);
    }

    /**
     * @action show_admin_bar
     */
    public function hideAdminBar(bool $show): bool
    {
        return $this->isCurrent() ? false : $show;
    }
}
