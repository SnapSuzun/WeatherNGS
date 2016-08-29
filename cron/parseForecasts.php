<?php
require_once __DIR__ . "/../core/Core.php";

use models\City;
use helpers\WeatherAPI;
use models\Forecast;

$config = require __DIR__ . "/../config/main.php";

Core::init($config);

echo "\r\rSTART...\r\n";

$cities = City::collection()->find();

foreach ($cities as $city) {

    $forecast = WeatherAPI::getCurrentForecast($city['alias']);

    if (isset($forecast['errors'])) {
        throw new \Exception($forecast['errors']['message']);
    }

    $forecast = array_shift($forecast['forecasts']);

    $cursor = Forecast::collection()->find([
        'links' => ['city' => $city['alias']],
        'update_date' => $forecast['update_date']
    ]);

    if ($cursor->count() == 0) {
        $forecast['date'] = new \MongoDate(strtotime($forecast['date']));
        Forecast::collection()->save($forecast);
    }
}

echo "\r\rEND\r\n";
