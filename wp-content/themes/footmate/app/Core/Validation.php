<?php

namespace FM\Core;

use WP_Error;
use Illuminate\Validation\Factory;
use Illuminate\Translation\Translator;
use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;

class Validation
{
    public static function valid(mixed $data, string|array $rules): bool
    {
        $validator = self::factory()->make(
            is_string($rules) ? ['value' => $data] : $data,
            is_string($rules) ? ['value' => $rules] : $rules
        );

        return ! $validator->fails();
    }

    public static function validate(array $data, array $rules, array $messages = [], array $attrs = []): array|WP_Error
    {
        $validator = self::factory()->make($data, $rules, $messages, $attrs);

        if ($validator->fails()) {
            $errors = new WP_Error();

            foreach ($validator->errors()->messages() as $key => $messages) {
                foreach ($messages as $message) {
                    $errors->add($key, $message);
                }
            }

            return $errors;
        }

        return $validator->validated();
    }

    private static function factory(): Factory
    {
        $factory = wp_cache_get('fm_validation_factory');

        if (empty($factory) || ! is_a($factory, Factory::class)) {
            $config = apply_filters(
                'fm_core_validation_config',
                [
                    'lang' => 'en',
                    'langs' => FM_PATH . '/vendor/illuminate/translation/lang',
                ]
            );

            $factory = new Factory(
                new Translator(
                    new FileLoader(new Filesystem(), $config['langs']),
                    $config['lang']
                )
            );

            $factory->extend(
                'nonce',
                function (string $attribute, mixed $value) {
                    return ! empty(wp_verify_nonce($value, 'contact'));
                },
                __('The :attribute is invalid.', 'fm')
            );

            $factory->extend(
                'link',
                function (string $attribute, mixed $value) {
                    if (empty($value['title'])) {
                        return false;
                    }

                    if (empty($value['url'])) {
                        return false;
                    }

                    if (! filter_var($value['url'], FILTER_VALIDATE_URL)) {
                        return false;
                    }

                    if (
                        ! empty($value['target']) &&
                        ! in_array($value['target'], ['_blank', '_self', '_parent', '_top'])
                    ) {
                        return false;
                    }

                    return true;
                },
                __('The :attribute is invalid.', 'fm')
            );

            do_action('fm_core_validation_factory', $factory);

            wp_cache_set('fm_validation_factory', $factory);
        }

        return $factory;
    }
}
