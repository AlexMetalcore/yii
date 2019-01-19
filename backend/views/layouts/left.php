<?php
use yii\helpers\Url;
use backend\models\User;
?>
<aside class="main-sidebar">

    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/admin/<?=User::findIdentity(Yii::$app->user->identity->getId())->user_img ? User::findIdentity(Yii::$app->user->identity->getId())->user_img : 'images/staticimg/no-img.png'?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username; ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <?= $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(['/../favicon.png'])]); ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Панель администратора', 'options' => ['class' => 'header'] , 'visible' => User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_USER],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Статьи', 'url' => ['/post/index'] , 'visible' => User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_USER],
                    ['label' => 'Категории', 'url' => ['/category/index'] , 'visible' => User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_USER],
                    ['label' => 'Пользователи', 'url' => ['/user/index'] , 'visible' => User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_MODERATOR && User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_USER],
                    ['label' => 'Портфолио работ', 'url' => ['/portfolio/index'] , 'visible' => User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_MODERATOR && User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_USER],
                    ['label' => 'Настройки', 'options' => ['class'=>'dropdown'] ,'items' => [
                        ['label' => 'Параметры настроек', 'url' => ['settings/index']],
                        ['label' => 'Кеш и работа с картинками', 'url' => ['settings/cache-data-img']]
                    ], 'visible' => User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_MODERATOR && User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_USER],
                    ['label' => 'Медиафайлы', 'url' => ['/media/index'], 'visible' => User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_MODERATOR && User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_USER]
                ],
            ]
        ) ?>

    </section>

</aside>
