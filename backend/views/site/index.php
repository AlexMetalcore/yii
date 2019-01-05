<?php

/* @var $this yii\web\View */
use yii\bootstrap\Html;

$this->title = 'Веб заметки (админка)';
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-6">
            <span class="post-headline">Последние добавленные статьи</span>
            <div class="last-post-block">
            <?php foreach ($posts as $post):?>
                <div class="post-author-block">
                    <?= Html::a('- '.ucfirst($post->title) , ['/post/view' , 'id' => $post->id] , ['class' => 'last-post']);?> <span class="author-post-name">автор (<?= Html::a($post->author->username , ['/user/view' , 'id' => $post->author->id] , ['class' => 'last-post-author']);?>)</span>
                </div>
                <hr class="line-under-name">
            <?php endforeach;?>
            </div>
        </div>

        <div class="col-md-6">
            <span class="post-headline">Последние зарегистрированные пользователи</span>
            <div class="last-user-register-block">
                <?php foreach ($users as $user):?>
                    <div class="author-block">
                        <?= Html::a($user->username , ['/user/view' , 'id' => $user->id] , ['class' => 'last-post']);?><span class="author-name">дата регистрации (<?=$user->getDate(); ?>)</span>
                    </div>
                    <hr class="line-under-name">
                <?php endforeach;?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <span class="post-headline">Популярные статьи</span>
            <div class="last-popular-block">
                <?php foreach ($populars as $popular):?>
                    <div class="popular-block">
                        <?= Html::a($popular->title , ['/post/view' , 'id' => $popular->id] , ['class' => 'popular-post']);?><span class="author-post-name">просмотров (<?=$popular->viewed; ?>)</span>
                    </div>
                    <hr class="line-under-name">
                <?php endforeach;?>
            </div>
        </div>

        <div class="col-md-6">
            <span class="portfolios-headline">Последние работы</span>
            <div class="last-portfolios-block">
                <?php if ($portfolios): ?>
                <?php foreach ($portfolios as $portfolio):?>
                    <div class="portfolio-block">
                        <?= Html::a($portfolio->title , ['/portfolio/view' , 'id' => $portfolio->id] , ['class' => 'portfolio-name']);?>
                    </div>
                    <hr class="line-under-name">
                <?php endforeach;?>
                <?php else: ?>
                Работ пока нету...
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
