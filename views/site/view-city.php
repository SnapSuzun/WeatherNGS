<?php
/* @var $this \core\View */
/* @var $forecast array */
/* @var $city array */
/* @var $has_errors bool */
?>

<div class="view-city">
    <div class="block">
        <?php if (!$has_errors): ?>
            <?= $this->render('_currentForecast', [
                'forecast' => $forecast,
                'city' => $city['name']
            ]) ?>
        <?php else: ?>
            <?= $this->render('_error', [
                'error' => $forecast
            ]) ?>
        <?php endif; ?>
    </div>
</div>
