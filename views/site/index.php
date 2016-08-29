<?php
/* @var $this core\View */
/* @var $forecast array */
/* @var $has_errors bool */
/* @var $current_city */
/* @var $availableCities array */
/* @var $cities \models\City[] */

$this->title = 'Список городов';
?>

<div class="cities">
    <div class="block">
        <?php if (!$has_errors): ?>
            <?= $this->render('_currentForecast', [
                'forecast' => $forecast,
                'city' => $current_city
            ]) ?>
        <?php else: ?>
            <?= $this->render('_error', [
                'error' => $forecast
            ]) ?>
        <?php endif; ?>
    </div>
    <div class="block choose-city">
        <?= $this->render('_cityList', [
            'cities' => $cities
        ])?>
    </div>
    <div class="block add-city">
        <?= $this->render('_formAddCity', [
            'cities' => $availableCities
        ])?>
    </div>
</div>
