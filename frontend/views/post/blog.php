<?php
use common\models\User;
use yii\widgets\LinkPager;

$this->title = 'Все статьи';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Post;

$this->title = 'Блог по программированию';
?>

<?php ?>
<div class="site-index">
    <div class="container">
        <div class="row">
            <?php foreach ($posts as $post) : ?>
                <div class="col-md-4 block_border">
                    <div class="content_post">
                        <?php if($post['img']): ?>
                            <a href="<?= Url::to(['post/view' , 'id' => $post['id']]); ?>" title="<?= $post['title'];?>">
                                <?= Html::img('@web/'.$post['img'].'' ,
                                    ['alt' => $post['title'] , 'class' => 'post_img']); ?></a>
                        <?php else: ?>
                            <a href="<?= Url::to(['post/view' , 'id' => $post['id']]); ?>" title="<?= $post['title'];?>">
                                <?= Html::img('@web/images/no-img.png' ,
                                    ['alt' => $post['title'] , 'class' => 'post_img']); ?></a>
                        <?php endif;?>
                        <span class="title">
                        <a href="<?= Url::to(['post/view' , 'id' => $post['id']]); ?>"><?= strtoupper($post['title']); ?></a>
                    </span>
                        <div class="content">
                            <?php if (strlen($post['content']) > 40): ?>
                            <?= mb_substr($post['content'], 0 , 40 ). '...'; ?>
                            <?php else:?>
                                <?= $post['content'] ?>
                            <?php endif; ?>
                        </div>
                        <?php
                        $user_post = Post::findOne($post['id']);
                        $get_user = User::findOne($user_post->author_id);
                        $user =  $get_user->username; ?>
                        <span class="author_name">Автор: <span class="name"><?= $user;?></span></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>

