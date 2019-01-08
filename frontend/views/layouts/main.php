<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
use rmrevin\yii\fontawesome\FA;

AppAsset::register($this);
$name_blog = 'Веб заметки';
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
    <?= $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(['/favicon.png'])]); ?>
    <?php
    NavBar::begin([
        'brandLabel' => $name_blog,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => FA::icon('home'). 'Главная', 'url' => ['site/index']],
        ['label' => 'Блог', 'url' => ['site/blog']],
        ['label' => 'Портфолио', 'url' => ['site/portfolio']],
        ['label' => 'Обратная связь', 'url' => ['site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '<span class="glyphicon glyphicon-user"></span> Войти', 'url' => ['site/login']];
        $menuItems[] = Html::img('/admin/images/staticimg/search.png' , ['class' => 'icon_search']);
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
        $menuItems[] = Html::img('/admin/images/staticimg/search.png' , ['class' => 'icon_search' , 'title' => 'Поиск']);
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <form action="<?= Url::to(['site/search']); ?>" method="get" id="form_search">
        <input name="search_query" type="text" placeholder="Введите поисковый запрос" class="input_search">
        <button type="submit" class="search">
            <?=Html::img('/admin/images/search_icon.png' , ['class' => 'search_icon_field'])?>
        </button>
    </form>
    <ul class="search_result"></ul>
    <div class="overlay"></div>

    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'Главная', 'url' => '/'],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?php echo $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode($name_blog) ?> <?= date('Y') ?></p>

        <p class="pull-right">Powered by <a href="https://github.com/AlexMetalcore/" target="_blank">Alex</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
