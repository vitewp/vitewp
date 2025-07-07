<div
    {{
        $attributes
            ->class(['block-exception'])
            ->merge([
                'data-block' => 'block-exception',
            ])
    }}
>
    <div class="block-exception__icon">⚠️</div>

    <div class="block-exception__content">
        <p class="block-exception__title">
            {{ $title ?? __('Exception', 'fm') }}
        </p>

        <p class="block-exception__message">
            {!! wp_kses_post($message) !!}
        </p>
    </div>
</div>
