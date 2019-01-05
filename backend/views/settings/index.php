<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.19
 * Time: 15:06
 */
use yii\widgets\Pjax;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Все настройки');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container settings">
    <span class="trash-images">Блок ненужных картинок</span>
    <div class="delete-all-img">
        <?php Pjax::begin(['id' => 'pjax-delete-trash-img']); ?>
            <?php if( $img_delete):?>
                <span class="count-old-img">Общее количество старых картинок: <b><?=$trash;?></b> </span>
                <div class="all-img-delete-block">
                    <?php foreach ($img_delete as $img): ?>
                        <div class="img_delete_item"><?=$img;?> (<?=round(filesize($img)/1000) . 'Кб'?>) </div>
                    <?php endforeach;?>
                </div>
                <?= Html::button('Удалить'.Html::img('/admin/images/staticimg/loaderbtn.gif' , ['class' => 'loader-delete']).'', ['class' => 'btn btn-danger' , 'id' => 'btn-delete-img' , $trash ? '' : 'disabled' => 'true']); ?>
            <?php else:?>
                <span class="count-trash">Старых картинок нету</span>
            <?php endif;?>
        <?php Pjax::end(); ?>
    </div>
</div>
