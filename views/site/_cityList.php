<?php

/* @var $this \core\View */
/* @var $cities \models\City[] */

?>

<style>
    a.button {
        -webkit-appearance: button;
        -moz-appearance: button;
        appearance: button;

        text-decoration: none;
        color: initial;
        padding-right: 4px;
        padding-left: 4px;
        padding-top: 2px;
        padding-bottom: 2px;
    }

    .cities-list li {
        padding-bottom: 5px;
    }
</style>

<div class="cities-list">
    <div>
        <h3>Список городов:</h3>
    </div>
    <ul>
        <?php foreach ($cities as $city): ?>
            <li>
                <a href="/view-city/<?= $city['_id']->{'$id'} ?>"><?= $city['name'] ?></a> - <?= $city['alias'] ?>
                <a href="/delete-city/<?=$city['_id']->{'$id'}?>" class="button">  X  </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
