<?php
/* @var $this core\View */
/* @var $content string */
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="<?= Core::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->title ?></title>
</head>
<body>

<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"></p>
    </div>
</footer>

</body>
</html>
