<?php
/* @var $this \core\View */
/* @var $currentForecast array */
/* @var $forecasts array */
/* @var $city array */
/* @var $forecastDayCount integer */

$this->title = 'Погода в городе ' . $city['name'];
?>

<div class="view-city">
    <div class="block">
        <?php if (isset($currentForecast['forecasts'])): ?>
            <?= $this->render('_currentForecast', [
                'forecast' => array_shift($currentForecast['forecasts']),
                'city' => $city['name']
            ]) ?>
        <?php elseif (isset($currentForecast['errors'])): ?>
            <?= $this->render('_error', [
                'error' => $currentForecast['errors']
            ]) ?>
        <?php endif; ?>
    </div>
    <div class="block">
        <?php if (isset($forecasts['forecasts'])): ?>
            <?= $this->render('_forecast', [
                'forecasts' => $forecasts['forecasts'],
                'dayCount' => $forecastDayCount,
            ]) ?>
        <?php elseif (isset($forecasts['errors'])): ?>
            <?= $this->render('_error', [
                'error' => $forecasts['errors']
            ]) ?>
        <?php endif; ?>
    </div>
</div>
