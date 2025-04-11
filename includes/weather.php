<?php

function swd_fetch_and_store_weather() {
    $api_key = '4c1fe9df740e1991e997531342c24fa2'; // make sure it's correct
    $city = 'Vienna';
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&appid={$api_key}";

    $res = wp_remote_get($url);

    if (is_wp_error($res)) {
        error_log('Weather fetch failed: ' . $res->get_error_message());
        return;
    }

    $body = wp_remote_retrieve_body($res);
    error_log('RAW WEATHER BODY: ' . $body); // ✅ add this

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
    error_log('Weather saved: ' . print_r($weather, true)); // ✅ confirm saved
}

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