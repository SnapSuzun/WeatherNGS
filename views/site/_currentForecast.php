<?php

/* @var $this core\View */
/* @var $forecast array */
/* @var $city string */
?>

<style>
    .column {
        padding-top: 10px;
        vertical-align: top;
        padding-bottom: 20px;
    }

    .first-col {
        display: inline;
        float: left;
        padding-right: 2%;
    }

    .icon-weather {
        display: inline;
        float: left;
        margin-top: -40px;
        padding-top: 0;
        height: 200px;
        padding-bottom: 0;
    }

    .third-col,
    .second-col {
        display: inline-block;
        float: none;
    }

    .second-col {
        padding-right: 2%;
    }

    .current-forecast {
        padding: 5px 20px 20px;
    }
    .last-updated {
        margin-top: -15px;
        padding-bottom: 20px;
        font-size: 15px;
    }
    .info-main {
        height: 160px;
        line-height: 22px;
    }
</style>

<div class="current-forecast">
    <div class="current-city">
        <h2>Сейчас в городе <?= $city ?></h2>
        <div class="last-updated">Обновлено в <?= date('H:i',strtotime($forecast['update_date']))?></div>
    </div>
    <div class="column icon-weather">
        <img src="http://pogoda.ngs.ru/static/img/ico/big-icons/<?= $forecast['icon'] ?>.png">
    </div>
    <div class="info-main">
        <div class="first-col column">
            <div>Температура: <?= $forecast['temperature'] > 0 ? '+' : '' ?><?= $forecast['temperature'] ?>°</div>
            <div>Ощущается: <?= $forecast['feel_like_temperature'] > 0 ? '+' : '' ?><?= $forecast['feel_like_temperature'] ?>°</div>
            <div><?= ucfirst($forecast['cloud']['title']) . ", " . $forecast['precipitation']['title'] ?></div>
            <div>Ветер: <?= $forecast['wind']['speed'] ?> м/с, <?= $forecast['wind']['direction']['title'] ?></div>
            <div>Атм. давление: <?= $forecast['pressure'] ?> мм</div>
            <div>Влажность: <?= $forecast['humidity'] ?>%</div>
        </div>
        <div>
            <div class="second-col column">
                <div>Восход / закат: <?= $forecast['astronomy']['sunrise'] ?>
                    - <?= $forecast['astronomy']['sunset'] ?></div>
                <div>Долгота дня: <?= $forecast['astronomy']['length_day_human'] ?></div>
                <?php if (isset($forecast['precipitation']['day_value'])): ?>
                    <div>Осадки за сутки: <?= $forecast['precipitation']['day_value'] ?></div>
                <?php endif; ?>
                <?php if (!empty($forecast['uv_index'])): ?>
                    <div>УФ индекс: <?= $forecast['uv_index'] ?></div>
                <?php endif; ?>
                <?php if (!empty($forecast['solar_radiation'])): ?>
                    <div>Излучение солнца: <?= $forecast['solar_radiation'] ?> Вт/м²</div>
                <?php endif; ?>
                <div><?= $forecast['magnetic_status'] ?></div>
            </div>
            <?php if (isset($forecast['water'])): ?>
                <div class="third-col column">
                    <div>Вода:</div>
                    <?php foreach ($forecast['water'] as $item): ?>
                        <div><?= $item['title'] ?> <?= $item['temperature']['value'] ?>°</div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
