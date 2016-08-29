<?php

namespace helpers;

/**
 * Class WeatherAPI
 * @package helpers
 */
class WeatherAPI
{
    const API_URL = 'http://pogoda.ngs.ru/api/v1/';

    public static function getForecast($city)
    {
        $url = self::API_URL . 'forecasts/forecast';

        $response = self::query($url, [
            'city' => $city
        ]);
        return $response;
    }

    public static function getCurrentForecast($city)
    {
        $url = self::API_URL . 'forecasts/current';

        $response = self::query($url, [
            'city' => $city
        ]);
        return $response;
    }

    public static function getCities()
    {
        $url = self::API_URL . 'cities';
        $response = self::query($url);
        return $response;
    }

    protected static function query($url, $params = [], $type = 'GET')
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($type));

        if (!empty($params) && is_array($params)) {
            if (strtoupper($type) == 'GET') {
                $url .= '?' . http_build_query($params);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);

        return json_decode($response, true);
    }
}
