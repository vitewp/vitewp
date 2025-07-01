<?php

namespace FM\Templates;

use FM\Templates\Template;

class Templates
{
    private array $templates = [];

    /**
     * @action after_setup_theme
     */
    public function init(): void
    {
        $classes = collect(fm()->filesystem()->glob(__DIR__ . '/*.php'))
            ->map(fn($path) => pathinfo($path, PATHINFO_FILENAME))
            ->reject(fn($name) => in_array($name, ['Template', 'Templates']))
            ->map(fn($name) => sprintf('FM\Templates\\%s', $name));

        foreach ($classes as $class) {
            $template = \FM\App::init(new $class());
            $this->templates[$template->getId()] = $template;
        }
    }

    public function has(string $template): bool
    {
        return isset($this->templates[$template]);
    }

    public function get(string $template): Template
    {
        return $this->templates[$template];
    }

    /**
     * @filter theme_templates
     */
    public function add(array $templates): array
    {
        if (empty($this->templates)) {
            return $templates;
        }

        foreach ($this->templates as $template) {
            $templates[$template->getId()] = $template->getTitle();
        }

        asort($templates);

        return $templates;
    }
}
