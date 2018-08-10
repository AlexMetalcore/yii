<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\CategoryWidget;
$this->title = $post->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-post">
    <div class="col-md-8 content_post_view">
        <h1><?= $post->title;?></h1>
        <span class="date">Дата публикации: <?= $post->publish_date; ?></span>
    , Категория: <a href="<?= Url::to(['category/view' , 'id' => $post->category->id])?>"
                    title="<?= $post->category->title; ?>"><span class="category"><?= $post->category->title;?></span></a>
    <span class="author_name"> , Автор: <span class="name"><?php echo $post->author->username; ?></span></span>
    <?= Html::a(Html::img('/admin/'.$post->img.'' , ['alt' => $post->title , 'class' => 'post']), '/admin/'.$post->img.'', ['rel' => 'fancybox']); ?>
    <div class="content_text_post"><?= $post->content;?></div>
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