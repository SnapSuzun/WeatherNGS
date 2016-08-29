<?php
/* @var $this \core\View */
/* @var $cities array */

?>
<style>
    .create-city label {
        display: inline-block;
    }
    .sbm-btn {
        margin-top: 10px;
    }
</style>
<div class="add-city">
    <div>
        <h4>Добавить город</h4>
    </div>
    <form class="create-city" id="create_city" method="post" action="/create-city">
        <div>
            <label>
                <div>Название города:</div>
                <input type="text" name="create_city[name]">
            </label>
            <label>
                <div>Алиас:</div>
                <select name="create_city[alias]">
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= $city ?>"><?= $city ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <div>
            <button type="submit" class="sbm-btn">Добавить</button>
        </div>
    </form>
</div>
