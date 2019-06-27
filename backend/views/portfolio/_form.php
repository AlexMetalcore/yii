<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Portfolio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="portfolio-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data' , 'method' => 'post']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model , 'gallery[]')->fileInput(['multiple' => true])->label(false); ?>

    <div class="form-group">
        <?= Html::button(Yii::t('app', 'Загрузить фото'), ['class' => 'btn btn-success upload_gallary']) ?>
        <?= Html::img('/admin/images/staticimg/AjaxLoader2.gif' , ['class' => 'portfolio-loader']) ?>
    </div>


    <?php if ($model->img): ?>
        <?= Html::img('@web/'.explode(',' ,$model->img)[0].'' ,
            ['alt' => $model->title , 'class' => 'preview_img']); ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
