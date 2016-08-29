<?php

/* @var $this \core\View */
/* @var $forecasts array */
/* @var $dayCount integer */
$counter = 0;
?>

<style>
    .forecast-list {
        width: 65%;
        padding-left: 5px;
    }

    .forecast {
        display: inline-block;
        width: 33%;
    }

    .forecast-header {
        background-color: gainsboro;
        padding-left: 10px;
        padding-top: 4px;
        padding-bottom: 4px;
        text-align: center;
    }

    .forecast-part {
        display: inline-block;
        width: 24%;
        text-align: center;
        line-height: 25px;
    }
</style>

<div>
    <h3>Прогноз на <?= $dayCount ?> дня</h3>
</div>
<div class="forecast-list">
    <?php foreach ($forecasts as $forecast): ?>
        <?php if (++$counter > $dayCount) {
            break;
        } ?>
        <div class="forecast">
            <div class="forecast-header">
                <?= strftime('%A, %d %B', strtotime($forecast['date'])) ?>
            </div>
            <?php foreach ($forecast['hours'] as $hour): ?>
                <div class="forecast-part">
                    <div>
                        <?= date('H:i', mktime($hour['hour'], 0)) ?>
                    </div>
                    <div class="small-icon">
                        <img src="http:<?= $hour['icon_path'] ?>">
                    </div>
                    <div class="forecast-temperature">
                        <?= $hour['temperature']['avg'] > 0 ? '+' : '' ?> <?= $hour['temperature']['avg'] ?>°
                    </div>
                    <div class="forecast-wind">
                        <?= $hour['wind']['speed']['avg']?> м/с, <?= $hour['wind']['direction']['title_short']?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
