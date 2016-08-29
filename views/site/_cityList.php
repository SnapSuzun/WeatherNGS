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

    .cities-list table {
        padding-bottom: 5px;
        padding-left: 10px;
    }

    .cities-list table td {
        padding-bottom: 5px;
        padding-right: 10px;
    }
</style>

<div class="cities-list">
    <div>
        <h3>Список городов:</h3>
    </div>
    <table>
        <thead>
            <th>Город</th>
            <th>Алиас</th>
        </thead>
        <tbody>
        <?php foreach ($cities as $city): ?>
            <tr>
                <td><a href="/view-city/<?= $city['_id']->{'$id'} ?>"><?= $city['name'] ?></a></td>
                <td><?= $city['alias'] ?></td>
                <td><a href="/delete-city/<?= $city['_id']->{'$id'} ?>" class="button"> X </a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!--<ul>
        <?php /*foreach ($cities as $city): */ ?>
            <li>
                <a href="/view-city/<? /*= $city['_id']->{'$id'} */ ?>"><? /*= $city['name'] */ ?></a> - <? /*= $city['alias'] */ ?>
                <a href="/delete-city/<? /*=$city['_id']->{'$id'}*/ ?>" class="button">  X  </a>
            </li>
        <?php /*endforeach; */ ?>
    </ul>-->
</div>
