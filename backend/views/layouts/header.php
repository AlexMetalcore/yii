<?php
use yii\helpers\Html;
use backend\models\Settings;
use backend\models\User;
/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
    <?= Html::a('<span class="logo-mini">'.Settings::get(Settings::NAME_BLOG_TITLE).'</span><span class="logo-lg">' . Settings::get(Settings::NAME_BLOG_TITLE) . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li>
                    <?=Html::a('( Перейти на сайт )' , ['/../'] , ['class' => 'go-site']);?>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/admin/<?=User::findIdentity(Yii::$app->user->identity->getId())->user_img ? User::findIdentity(Yii::$app->user->identity->getId())->user_img : 'images/staticimg/no-img.png'?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->username; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/admin/<?=User::findIdentity(Yii::$app->user->identity->getId())->user_img ? User::findIdentity(Yii::$app->user->identity->getId())->user_img : 'images/staticimg/no-img.png'?>" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?= Yii::$app->user->identity->username; ?>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/admin/user/view?id=<?=Yii::$app->user->identity->getId();?>" class="btn btn-default btn-flat">Профиль пользователя</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Выход',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
