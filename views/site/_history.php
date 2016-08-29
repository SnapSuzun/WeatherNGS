<?php

/* @var $this \core\View */
/* @var $history array */
?>
<style>
    .history-part {
        display: inline-block;
        text-align: center;
        background-color: aliceblue;
        padding: 10px;
    }

    .history-date {
        padding-bottom: 10px;
    }
</style>
<div class="history">
    <div class="history-title">
        <h3>Температура за прошедшую неделю</h3>
    </div>
    <div class="history-list">
        <?php foreach ($history as $date => $temp): ?>
            <div class="history-part">
                <div class="history-date">
                    <?= $date ?>
                </div>
                <div class="history-temp">
                    <?= $temp > 0 ? '+' : '' ?> <?= $temp ?>°
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
