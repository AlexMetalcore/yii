<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Post;
use \backend\helper\HelperImageFolder;

$this->title = 'Все статьи';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php ?>
<div class="site-index">
    <div class="container">
        <div class="row">
            <?php foreach ($posts as $post) : ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-4 block_border">
                    <div class="content_post">
                        <?php if($post->thumb_img): ?>
                            <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
                                <?= Html::img('/admin/'.$post->thumb_img.'' ,
                                    ['alt' => $post->title , 'class' => 'post_img']); ?></a>
                        <?php else: ?>
                            <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
                                <?= Html::img('/admin/images/staticimg/no-img.png' ,
                                    ['alt' => $post->title , 'class' => 'post_img']); ?></a>
                        <?php endif;?>
                        <span class="title">
                        <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>"><?= strtoupper($post->title); ?></a>
                    </span>
                        <div class="content">
                            <?php if (strlen(HelperImageFolder::removeImgTags($post->content)) > 100): ?>
                            <?= mb_substr(HelperImageFolder::removeImgTags($post->content), 0 , 100 ). '...'; ?>
                            <?php else:?>
                                <?= HelperImageFolder::removeImgTags($post->content); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="block-pagination">
        <?= LinkPager::widget([
            'pagination' => $pages,
        ]); ?>
    </div>
</div>

