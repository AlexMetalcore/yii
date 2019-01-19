<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->label('Статус')
        ->dropDownList(['20' => 'Администратор' , '15' => 'Модератор' , '10' => 'Пользователь']) ?>

    <?= $form->field($model, 'about')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'upload_user_avatar')->fileInput()->label(false); ?>

    <div class="form-group">
        <?= Html::button(Yii::t('app', 'Загрузить фото'), ['class' => 'btn btn-success upload_user_avatar']) ?>
        <?= Html::img('/admin/images/staticimg/AjaxLoader2.gif' , ['class' => 'portfolio-loader']) ?>
    </div>

    <?php if ($model->user_img): ?>
        <?= Html::img('@web/'.$model->user_img.'' ,
            ['alt' => $model->username , 'class' => 'preview_img']); ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>