<?php

namespace FM\Components;

use FM\Components\Component;

class Components
{
    private array $components = [];

    /**
     * @action after_setup_theme
     */
    public function init(): void
    {
        $classes = collect(fm()->filesystem()->glob(__DIR__ . '/*.php'))
            ->map(fn($path) => pathinfo($path, PATHINFO_FILENAME))
            ->reject(fn($name) => in_array($name, ['Component', 'Components']))
            ->map(fn($name) => sprintf('FM\Components\\%s', $name));

        foreach ($classes as $class) {
            $component = \FM\App::init(new $class());
            $this->components[$component->getId()] = $component;
        }
    }

    public function get(string $key): ?Component
    {
        return ! empty($this->components[$key]) ? $this->components[$key] : null;
    }

    public function all(): array
    {
        return $this->components;
    }

    /**
     * @action fm_templating_provider_init
     */
    public function register(\Illuminate\View\Compilers\BladeCompiler $compiler): void
    {
        foreach ($this->components as $component) {
            $compiler->component($component->getId(), $component::class);
        }
    }
}
