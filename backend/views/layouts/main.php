<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use backend\models\User;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?= $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(['/../favicon.png'])]); ?>
    <?php
    NavBar::begin([
        'brandLabel' => 'Админ панель' .Html::a('( Перейти на сайт )' , ['/../'] , ['class' => 'go-site']),
        'brandUrl' => '/admin/',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            'visible' => !Yii::$app->user->isGuest,
        ],
    ]);
    $auth = Yii::$app->authManager;
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Авторизация', 'url' => ['/site/login']];
    } else {
        if ($auth->getRole('admin')) {
            $menuItems[] = ['label' => 'Статьи', 'url' => ['/post/index']];
            $menuItems[] = ['label' => 'Категории', 'url' => ['/category/index']];
            $menuItems[] = User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_MODERATOR ? ['label' => 'Пользователи', 'url' => ['/user/index']] : '';
            $menuItems[] = User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_MODERATOR ? ['label' => 'Портфолио работ', 'url' => ['/portfolio/index']] : '';
            $menuItems[] = User::findIdentity(\Yii::$app->user->identity->getId())->status != User::ROLE_MODERATOR ? ['label' => 'Настройки', 'options'=>['class'=>'dropdown'] ,'items' => [
                ['label' => 'Параметры настроек', 'url' => ['settings/index']],
                ['label' => 'Кеш и работа с картинками', 'url' => ['settings/cache-data-img']]
            ]] : '';
            $menuItems[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton('Выход (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
        }
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'Главная', 'url' => '/admin'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?php if( Yii::$app->session->hasFlash('success') ): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php endif;?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
