<?php

// 1. Wetter abrufen und speichern
function swd_fetch_and_store_weather() {
    $api_key = '4c1fe9df740e1991e997531342c24fa2';
    $city = 'Vienna';
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&appid={$api_key}";

    $res = wp_remote_get($url);

    if (is_wp_error($res)) {
        error_log('Weather fetch failed: ' . $res->get_error_message());
        return;
    }

    $body = wp_remote_retrieve_body($res);
    error_log('RAW WEATHER BODY: ' . $body);

    $data = json_decode($body, true);

    if (!isset($data['main']['temp'], $data['weather'][0]['id'], $data['weather'][0]['icon'])) {
        error_log('Weather fetch: missing required data');
        return;
    }

    $weather = [
        'temp' => round($data['main']['temp']),
        'code' => $data['weather'][0]['id'],
        'icon' => $data['weather'][0]['icon'],
        'time' => current_time('timestamp')
    ];

    update_option('swd_weather', $weather);
    error_log('Weather saved: ' . print_r($weather, true));
}

// 2. Cron-Hook registrieren
add_action('swd_weather_cron_hook', 'swd_fetch_and_store_weather');

// 3. Cronjob planen bei Theme-Aktivierung (oder manueller Trigger)
function swd_schedule_weather_cron() {
    if (!wp_next_scheduled('swd_weather_cron_hook')) {
        wp_schedule_event(time(), 'hourly', 'swd_weather_cron_hook');
    }
}
add_action('after_setup_theme', 'swd_schedule_weather_cron');

// 4. Beim Deaktivieren aufräumen (nur bei Plugin-Nutzung relevant)
if (function_exists('register_deactivation_hook')) {
    register_deactivation_hook(__FILE__, function() {
        $timestamp = wp_next_scheduled('swd_weather_cron_hook');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'swd_weather_cron_hook');
        }
    });
}

// 5. Wetter-Icon-Hilfe
function swd_get_weather_icon($code, $icon_code) {
    $is_night = str_ends_with($icon_code, 'n');

    if ($code >= 200 && $code < 300) return 'thunderstorm';
    if ($code >= 300 && $code < 600) return 'rain';
    if ($code >= 600 && $code < 700) return 'snow';
    if ($code >= 700 && $code < 800) return 'wind';
    if ($code === 800) return $is_night ? 'moon' : 'sun';
    if ($code > 800 && $code < 900) return 'cloudy';

    return 'cloudy';
}