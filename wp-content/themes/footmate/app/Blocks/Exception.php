<?php

namespace FM\Blocks;

use FM\Blocks\Block;

class Exception extends Block
{
    public function __construct()
    {
        $this->setId('exception');
        $this->setTitle('Exception');
        $this->setSchema(
            [
                'title' => 'nullable|string',
                'message' => 'nullable|string',
            ]
        );
        $this->setData([]);
        $this->setPrimary(true);
    }
}
