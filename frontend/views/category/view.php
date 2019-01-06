<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\components\CategoryWidget;
use backend\models\Post;

$this->title = $category->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-category">
    <div class="row">
        <div class="col-md-8 fix-padding-caytegory">
        <?php foreach ($posts as $post): ?>

            <div class="col-md-12 category_background">
                <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" class="link_post" title="<?= $post->title;?>">
                    <span class="category_title"><?= $post->title;?></span>
                </a>
                <?php if($post->img): ?>
                    <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
                        <?= Html::img('/admin/'.$post->img.'' ,
                            ['alt' => $post->title , 'class' => 'category_post']); ?></a>
                <?php else: ?>
                    <a href="<?= Url::to(['post/view' , 'id' => $post['id']]); ?>" title="<?= $post->title;?>">
                        <?= Html::img('/admin/images/no-img.png' ,
                            ['alt' => $post->title , 'class' => 'category_post']); ?></a>
                <?php endif;?>
                <div class="category_content">
                    <?= mb_substr(Post::removeImgTags($post->content), 0 , 400);?><span class="read_more"><a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>">...Читать далее</a></span>
                </div>
                <div class="info">
                    Дата публикации: <span class="date"><?= $post->getDate(); ?></span>
                    Категория: <a href="<?= Url::to(['category/view' , 'id' => $post->category->id])?>"
                                    title="<?= $post->category->title; ?>"><span class="category"><?= $post->category->title;?></span></a>
                    <span class="author_name"> Автор: <span class="name"><?php echo $post->author->username; ?></span></span>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
        <div class="col-md-4 full-width">
            <div class="category_widget category_top_widget">
                <?php if ($this->beginCache('CategoryWidget', ['duration' => CategoryWidget::TIME_CACHE])):?>
                    <?=CategoryWidget::widget();?>
                    <?php $this->endCache(); ?>
                <?php endif;?>
            </div>
        </div>
        <?php echo LinkPager::widget([
            'pagination' => $pages,
            'options' => [
                'id' => 'pagination_category',
                'class' => 'pagination'
            ],
        ]); ?>
    </div>
</div>