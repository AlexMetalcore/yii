<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Все статьи';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php ?>
<div class="site-index">
    <div class="container">
        <div class="row">
            <?php foreach ($posts as $post) : ?>
                <div class="col-md-4 block_border">
                    <div class="content_post">
                        <?php if($post->img): ?>
                            <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
                                <?= Html::img('/admin/'.$post->img.'' ,
                                    ['alt' => $post->title , 'class' => 'post_img']); ?></a>
                        <?php else: ?>
                            <a href="<?= Url::to(['post/view' , 'id' => $post['id']]); ?>" title="<?= $post->title;?>">
                                <?= Html::img('/admin/images/no-img.png' ,
                                    ['alt' => $post->title , 'class' => 'post_img']); ?></a>
                        <?php endif;?>
                        <span class="title">
                        <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>"><?= strtoupper($post->title); ?></a>
                    </span>
                        <div class="content">
                            <?php if (strlen($post->content) > 40): ?>
                            <?= mb_substr($post->content, 0 , 40 ). '...'; ?>
                            <?php else:?>
                                <?= $post->content; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>

