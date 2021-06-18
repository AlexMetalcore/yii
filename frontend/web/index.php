<?php

if ($_SERVER['SERVER_ADDR'] === '127.0.0.1') {
    defined('YII_ENV') or define('YII_ENV', 'dev');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

require __DIR__. '/../../functions.php';

$dotenv = new \Dotenv\Dotenv(realpath(__DIR__ . '/../..'));
$dotenv->load();

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

(new yii\web\Application($config))->run();
