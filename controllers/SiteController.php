<?php

namespace controllers;

use core\Controller;
use helpers\WeatherAPI;
use MongoClient;
use models\City;
use Core;

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

        if (Core::$app->hasCookie('city') && Core::$app->getCookie('city') != $id) {
            Core::$app->setCookie('city', $id);
        }

        $forecast = WeatherAPI::getCurrentForecast($city['alias']);

        $has_errors = false;
        if (!isset($forecast['errors'])) {
            $forecast = array_shift($forecast['forecasts']);
        } else {
            $forecast = $forecast['errors'];
            $has_errors = true;
        }

        return $this->render('view-city', [
            'forecast' => $forecast,
            'has_errors' => $has_errors,
            'city' => $city
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
}
