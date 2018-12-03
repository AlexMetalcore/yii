<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\CategoryWidget;
use yii\widgets\Pjax;
use common\models\User;

$this->title = $post->title;
$this->params['breadcrumbs'][] = ['label' => $post->category->title, 'url' => ['category/view', 'id' => $post->category->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-post">
    <div class="col-md-8 content_post_view">
        <h1><?= $post->title;?></h1>
        <span class="date">Дата публикации: <?= $post->publish_date; ?></span>
    , Категория: <a href="<?= Url::to(['category/view' , 'id' => $post->category->id])?>"
                    title="<?= $post->category->title; ?>"><span class="category"><?= $post->category->title;?></span></a>
    <span class="author_name"> , Автор: <span class="name"><?php echo $post->author->username; ?></span></span>
        <?php if (!Yii::$app->user->isGuest): ?>
            Нравится :
        <?= (!isset($model_author->like_author) && empty($model_author->like_author)) ? Html::img('/admin/images/heart-outline.png' , ['class' => 'heart-like']) : Html::img('/admin/images/heart_red.png' , ['class' => 'heart-like-active']); ?>
            <?php Pjax::begin([ 'id' => 'pjaxCountLikes']); ?>
        <span class="count_like"><?= $count == 0 ? '' : $count; ?></span>
            <span class="tooltiptext">
                <?php if(!empty($author_likes)): ?>
                <?php foreach ($author_likes as $author): ?>
                    <span class="author_likes"><?= $author;?></span>
                <?php endforeach; ?>
                <?php endif; ?>
            </span>
            <?php Pjax::end(); ?>
            <input type="hidden" class="post-id" value="<?= $post->id;?>"/>
        <?php endif; ?>
    <?= Html::a(Html::img('/admin/'.$post->img.'' , ['alt' => $post->title , 'class' => 'post']), '/admin/'.$post->img.'', ['rel' => 'fancybox']); ?>
    <div class="content_text_post"><?= $post->content;?></div>
        <?= \yii2mod\comments\widgets\Comment::widget([
            'model' => $model,
            'maxLevel' => 2,
            'dataProviderConfig' => [
                'pagination' => [
                    'pageSize' => 10
                ],
            ],
            'listViewConfig' => [
                'emptyText' => Yii::t(  'app', 'Комментариев нету'),
            ],
        ]); ?>
    </div>
    <div class="col-md-4 full-width">
        <div class="category_widget category_top_widget">
        <?= CategoryWidget::widget(); ?>
        </div>
    </div>
</div>

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