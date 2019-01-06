<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.19
 * Time: 15:06
 */
use yii\widgets\Pjax;
use yii\helpers\Html;
use frontend\components\CategoryWidget;
$this->title = Yii::t('app', 'Все настройки');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container settings">
    <span class="trash-images">Блок не нужных картинок</span>
    <div class="delete-all-img">
        <?php Pjax::begin(['id' => 'pjax-delete-trash-img']); ?>
            <?php if( $img_delete):?>
                <span class="count-old-img">Общее количество старых картинок: <b><?=$trash;?></b> </span>
                <div class="all-img-delete-block">
                    <?php foreach ($img_delete as $img): ?>
                        <div class="img_delete_item"><?=$img;?> (<?=round(filesize($img)/1000) . 'Кб'?>) </div>
                    <?php endforeach;?>
                </div>
                <?= Html::button('Очистить'.Html::img('/admin/images/staticimg/loaderbtn.gif' , ['class' => 'loader-delete']).'', ['class' => 'btn btn-danger' , 'id' => 'btn-delete-img' , $trash ? '' : 'disabled' => 'true']); ?>
            <?php else:?>
                <span class="count-trash">Старых картинок нету</span>
            <?php endif;?>
        <?php Pjax::end(); ?>
    </div>

    <span class="static-images">Сжатие статичных картинок</span>

    <div class="all-img-compress-block">
        <?php Pjax::begin(['id' => 'pjax-compress-img']); ?>
        <?php if ($staticimg): ?>
            <?php foreach ($staticimg as $img): ?>
            <div class="static-img-block"><div class="static-img"><?=$img;?></div> - размер картинки (<?= round(filesize($img)/1000) . 'Кб'?>)</div>
            <?php endforeach; ?>
            <?php Pjax::end(); ?>
    </div>
    <?= Html::button('Сжатие картинок'.Html::img('/admin/images/staticimg/loaderbtn.gif' , ['class' => 'loader-compression']).'', ['class' => 'btn btn-danger' , 'id' => 'btn-compress-img' , count($staticimg) ? '' : 'disabled' => 'true']); ?>
        <?php else:?>
        Картинки для сжатия отсутствуют
        <?php endif;?>
    <div class="cache-clear">Очистка кеша</div>
    <div>Время кеширования: <?= CategoryWidget::TIME_CACHE/3600 . ' час'; ?></div>
    <?= Html::button('Очистка кеша'.Html::img('/admin/images/staticimg/loaderbtn.gif' , ['class' => 'loader-clear-cache']).'', ['class' => 'btn btn-danger' , 'id' => 'btn-clear-cache']); ?>
</div>
