<?php

namespace common\loader;

use \common\repositories\UserRepository;

use yii\base\Application;
use yii\base\BootstrapInterface;

class BootstrapLoader implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton('common\repositories\UserRepositoryInterface', function() {
            return new UserRepository();
        });
    }
}