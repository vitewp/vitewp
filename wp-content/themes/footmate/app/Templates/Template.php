<?php

namespace FM\Templates;

use FM\Core\Validation;

abstract class Template
{
    private string $id = '';

    private string $title = '';

    private array $data = [];

    private array $schema = [];

    private bool $primary = false;

    final public function render(array $data = []): void
    {
        $this->enqueue();
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $this->generate($data);
    }

    final public function generate(array $data = []): string
    {
        ob_start();

        fm()->templating()->render("templates::{$this->getId()}.template", $this->parse($data));

        return ob_get_clean();
    }

    final protected function parse(array $data): array
    {
        $data = array_replace_recursive($this->getData(), $data);
        $data = apply_filters("fm_templates_{$this->getId()}_data", $data);

        if ($this->hasSchema()) {
            $result = Validation::validate($data, $this->getSchema());

            if (is_wp_error($result)) {
                if (wp_doing_ajax() || defined('REST_REQUEST')) {
                    wp_die(
                        esc_attr(
                            sprintf(
                                '%s template data verification failed: %s',
                                $this->getTitle(),
                                $result->get_error_message()
                            )
                        )
                    );
                } else {
                    throw new \Exception(
                        esc_attr(
                            sprintf(
                                '%s template data verification failed: %s',
                                $this->getTitle(),
                                $result->get_error_message()
                            )
                        )
                    );
                }
            }
        }

        return $data;
    }

    final public function enqueue(): void
    {
        fm()->assets()->enqueue(
            "templates/{$this->getId()}/script.js",
            [
                'handle' => "template-{$this->getId()}-script",
                'deps' => ['script'],
            ]
        );
        fm()->assets()->enqueue(
            "templates/{$this->getId()}/style.scss",
            [
                'handle' => "template-{$this->getId()}-style",
                'deps' => ['style'],
            ]
        );
    }

    final public function getId(): string
    {
        if (empty($this->id)) {
            throw new \Exception('Template ID is missing.');
        }

        return $this->id;
    }

    final protected function setId(string $id): void
    {
        $this->id = $id;
    }

    final public function getTitle(): string
    {
        if (empty($this->id)) {
            throw new \Exception('Template Title is missing.');
        }

        return $this->title;
    }

    final protected function setTitle(string $title): void
    {
        $this->title = $title;
    }

    final protected function getData(string $key = ''): mixed
    {
        if (! empty($key)) {
            return data_get($this->data, $key);
        }

        return $this->data;
    }

    final protected function setData(array $data): void
    {
        $this->data = $data;
    }

    final public function hasData(string $key = ''): bool
    {
        if (! empty($key)) {
            return ! empty($this->getData($key));
        }

        return ! empty($this->getData());
    }

    final protected function getSchema(): array
    {
        return $this->schema;
    }

    final protected function setSchema(array $schema): void
    {
        $this->schema = $schema;
    }

    final public function hasSchema(): bool
    {
        return ! empty($this->getSchema());
    }

    final public function isPrimary(): bool
    {
        return ! empty($this->primary);
    }

    final public function setPrimary(bool $primary = true): void
    {
        $this->primary = $primary;
    }

    final public function isCurrent(): bool
    {
        return $this->getId() === get_post_meta(get_the_id(), '_wp_page_template', true);
    }

    /**
     * @action wp_enqueue_scripts
     */
    final public function enqueuePrimary(): void
    {
        if ($this->isPrimary()) {
            $this->enqueue();
        }
    }
}
