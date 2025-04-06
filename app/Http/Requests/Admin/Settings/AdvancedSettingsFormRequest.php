<?php

namespace Pterodactyl\Http\Requests\Admin\Settings;

use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class AdvancedSettingsFormRequest extends AdminFormRequest
{
    /**
     * Return all the rules to apply to this request's data.
     */
    public function rules(): array
    {
        return [
            'recaptcha:enabled' => 'required|in:true,false',
            'recaptcha:secret_key' => 'required|string|max:191',
            'recaptcha:website_key' => 'required|string|max:191',
            'pterodactyl:guzzle:timeout' => 'required|integer|between:1,60',
            'pterodactyl:guzzle:connect_timeout' => 'required|integer|between:1,60',
            'pterodactyl:client_features:allocations:enabled' => 'required|in:true,false',
            'pterodactyl:client_features:allocations:range_start' => [
                'nullable',
                'required_if:pterodactyl:client_features:allocations:enabled,true',
                'integer',
                'between:1024,65535',
            ],
            'pterodactyl:client_features:allocations:range_end' => [
                'nullable',
                'required_if:pterodactyl:client_features:allocations:enabled,true',
                'integer',
                'between:1024,65535',
                'gt:pterodactyl:client_features:allocations:range_start',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'recaptcha:enabled' => 'reCAPTCHA Включено',
            'recaptcha:secret_key' => 'reCAPTCHA Секретный ключ',
            'recaptcha:website_key' => 'reCAPTCHA Веб-ключ',
            'pterodactyl:guzzle:timeout' => 'Время ожидания HTTP-запроса',
            'pterodactyl:guzzle:connect_timeout' => 'Время ожидания HTTP-подключения',
            'pterodactyl:client_features:allocations:enabled' => 'Включено автоматическое создание аллокаций',
            'pterodactyl:client_features:allocations:range_start' => 'Начальный порт',
            'pterodactyl:client_features:allocations:range_end' => 'Конечный порт',
        ];
    }
}
