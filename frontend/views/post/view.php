<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $post->title;
?>
<div class="container content_post_view">
        <h1><?= lcfirst($post->title);?></h1>
        <span class="date"><?= $post->publish_date; ?></span>
    , Категория: <a href="<?= Url::to(['category/view' , 'id' => $post->category->id])?>"
                    title="<?= $post->category->title; ?>"><span class="category"><?= $post->category->title;?></span></a>
    <span class="author_name"> , Автор: <span class="name"><?php echo $post->author->username; ?></span></span>
    <?= Html::a(Html::img('/admin/'.$post->img.'' , ['alt' => $post->title , 'class' => 'post']), '/admin/'.$post->img.'', ['rel' => 'fancybox']); ?>
    <div class="content_text_post"><?= $post->content;?></div>
</div>
<?php
echo newerton\fancybox\FancyBox::widget([
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