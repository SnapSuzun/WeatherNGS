<?php

namespace controllers;

use core\Controller;
use helpers\WeatherAPI;
use MongoClient;
use models\City;
use Core;
use models\Forecast;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $cities = City::collection()->find();

        $availableCities = [];
        $serverCities = WeatherAPI::getCities();
        if (!isset($serverCities['errors'])) {
            $availableCities = array_map(function ($city) {
                return $city['alias'];
            }, $serverCities['cities']);
            $serverCities = $serverCities['cities'];
        }

        $city = '';
        $cityName = '';

        if (Core::$app->hasCookie('city')) {
            $id = Core::$app->getCookie('city');
            $city = City::collection()->find(['_id' => new \MongoId($id)])->getNext();
            if (!empty($city)) {
                $cityName = $city['name'];
                $city = $city['alias'];
            }
        }

        if (empty($city) && $cities->count() > 0) {
            $cities->next();
            $city = $cities->current()['alias'];
            $cityName = $cities->current()['name'];
        }

        if (empty($city) && !isset($serverCities['errors']) && count($serverCities) > 0) {
            $city = $serverCities[0]['alias'];
            $cityName = $serverCities[0]['title'];
        }

        $forecast = WeatherAPI::getCurrentForecast($city);

        $has_errors = false;
        if (!isset($forecast['errors'])) {
            $forecast = array_shift($forecast['forecasts']);
        } else {
            $forecast = $forecast['errors'];
            $has_errors = true;
        }

        return $this->render('index', [
            'forecast' => $forecast,
            'has_errors' => $has_errors,
            'current_city' => $cityName,
            'availableCities' => $availableCities,
            'cities' => $cities
        ]);
    }

    public function actionViewCity($id)
    {
        $city = City::collection()->findOne(['_id' => new \MongoId($id)]);

        if (empty($city)) {
            throw new \Exception("City not found");
        }

        if ((Core::$app->hasCookie('city') && Core::$app->getCookie('city') != $id) || !Core::$app->hasCookie('city')) {
            Core::$app->setCookie('city', $id);
        }

        $forecast = WeatherAPI::getCurrentForecast($city['alias']);

        $forecasts = WeatherAPI::getForecast($city['alias']);

        $endDate = strtotime('-0 day');
        $startDate = strtotime('-7 days');

        $historyCursor = Forecast::collection()->find([
            'links' => [
                'city' => $city['alias']
            ],
            'date' => [
                '$gt' => new \MongoDate($startDate),
                '$lte' => new \MongoDate($endDate)
            ]
        ]);
        $history = [];
        $counter = [];
        foreach ($historyCursor as $item) {
            $day = strftime('%A, %d %B', $item['date']->sec);
            if (!isset($history[$day])) {
                $history[$day] = 0;
                $counter[$day] = 0;
            }
            $history[$day] += $item['temperature'];
            $counter[$day]++;
        }

        foreach ($counter as $key => $value) {
            $history[$key] /= $value;
        }

        return $this->render('view-city', [
            'currentForecast' => $forecast,
            'forecasts' => $forecasts,
            'city' => $city,
            'forecastDayCount' => 3,
            'history' => $history
        ]);

    }

    public function actionCreateCity()
    {
        $post = Core::$app->post('create_city');
        if (!empty($post)) {
            $model = new City();
            $model->name = $post['name'];
            $model->alias = $post['alias'];
            $model->save();
        }
        return Core::$app->redirect('/index');
    }

    public function actionDeleteCity($id)
    {
        $result = City::collection()->remove(['_id' => new \MongoId($id)]);
        Core::$app->redirect('/index');
    }

    public function actionParse()
    {
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

        Core::$app->redirect('/index');
    }
}
