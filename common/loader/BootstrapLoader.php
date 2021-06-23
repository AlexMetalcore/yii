<?php

namespace common\loader;

use common\repositories\PostRepository;
use common\repositories\PostRepositoryInterface;
use \common\repositories\UserRepository;

use common\repositories\UserRepositoryInterface;
use yii\base\BootstrapInterface;

class BootstrapLoader implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(UserRepositoryInterface::class, UserRepository::class);

        $container->setSingleton(PostRepositoryInterface::class, PostRepository::class);

    }
}