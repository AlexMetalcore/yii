<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\CategoryWidget;
use yii\widgets\Pjax;
use backend\models\Settings;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;

$this->title = $post->title;
$this->params['breadcrumbs'][] = ['label' => $post->category->title, 'url' => ['category/view', 'id' => $post->category->id ? $post->category->id : '']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if (Yii::$app->session->hasFlash('error-login')): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo Yii::$app->session->getFlash('error-login'); ?>
    </div>
<?php endif;?>
<div class="site-post">
    <div class="row">
        <div class="col-md-8 content_post_view">
            <h1 class="post_title"><?= $post->title;?></h1>
            <span class="date">Дата публикации: <?= $post->getDate(); ?></span>
          Категория: <a href="<?= Url::to(['category/view' , 'id' => $post->category->id])?>"
                        title="<?= $post->category->title; ?>"><span class="category"><?= $post->category->title;?></span></a>
        <span class="author_name"> Автор: <span class="name"><?php echo $post->author->username; ?></span></span>
            <?php if (!Yii::$app->user->isGuest): ?>
                Нравится :
            <?= (!isset($model_author) && empty($model_author)) ? Html::img('/admin/images/staticimg/heart-outline.png' , ['class' => 'heart-like']) : Html::img('/admin/images/staticimg/heart_red.png' , ['class' => 'heart-like-active']); ?>
                <?php Pjax::begin([ 'id' => 'pjaxCountLikes']); ?>
            <span class="count_like"><?= !$count ? '' : $count; ?></span>
                <span class="tooltiptext">
                    <?php if(!empty($post->like) && count($post->like) >= 1): ?>
                    <?php foreach ($post->like as $author): ?>
                        <span class="author_likes"><?= $author->like_author;?></span>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </span>
                <?php Pjax::end(); ?>
                <input type="hidden" class="post-id" value="<?= $post->id;?>"/>
            <?php endif; ?>
            <span> <?= Html::img('/admin/images/staticimg/viewed.png' , ['class' => 'viewed']);?><span class="count_viewed"><?= $post->viewed;?></span></span>
        <?= $post->img ? Html::a(Html::img('/admin/'.$post->img.'' , ['alt' => $post->title , 'class' => 'post']), '/admin/'.$post->img.'', ['rel' => 'fancybox']) : ''; ?>
        <div class="content_text_post"><?= $post->content;?></div>
            <?= \yii2mod\comments\widgets\Comment::widget([
                'model' => $post,
                'maxLevel' => 2,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 10
                    ],
                ],
                'listViewConfig' => [
                    'emptyText' => Yii::t('app', 'Комментариев нету'),
                ],
            ]); ?>
        </div>
        <div class="col-md-4 full-width">
            <div class="category_widget category_top_widget">
                <?php if ($this->beginCache('CategoryWidget', ['duration' => Settings::get(Settings::TIME_CACHE_WIDGET)])):?>
                    <?=CategoryWidget::widget();?>
                    <?php $this->endCache(); ?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<?php
Modal::begin([
    'header'=>'<h4>Авторизация</h4>',
    'id'=>'modal-login-user',
    'size'=>'modal-md',
]);?>
    <div class="form-login">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'validateOnBlur'         => false,
            'validateOnType'         => false,
            'validateOnChange'       => false,
            'validateOnSubmit'       => true,
        ]); ?>

        <?= $form->field($model, 'username')->label('Пользователь')->textInput(['autofocus' => true , 'class' => 'input-login-site form-control']) ?>

        <?= $form->field($model, 'password')->label('Пароль')->passwordInput(['class' => 'input-login-site form-control']) ?>

        <?= $form->field($model, 'rememberMe')->label('Запомнить меня')->checkbox() ?>

        <?= Html::a('Регистрация', ['site/signup'] , ['class' => 'register_link']) ?>

        <div style="color:#999;margin:1em 0">
            Если вы забыли свой пароль, вы можете <?= Html::a('сбросить его', ['site/request-password-reset']) ?>.
        </div>

        <div class="form-group">
            <?= Html::submitButton('Авторизироваться', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
<?php
Modal::end();
?>
<?php
$this->registerJS("
    $('.link-login').click(function(e){
        e.preventDefault();
        $('#modal-login-user').modal('show'); 
    });
");
?>
<?= newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=fancybox]',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);