<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model frontend\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->label('Заголовок')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'anons')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?php echo $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map($category, 'id', 'title')
    ) ?>

    <?= $form->field($model, 'author_id')->dropDownList(
        ArrayHelper::map($authors, 'id', 'username')
    ) ?>
    <?= $form->field($model, 'publish_status')->dropDownList([ 'draft' => 'Черновик', 'publish' => 'Опубликовано', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'publish_date')->textInput() ?>

    <?= $form->field($model, 'upload')->fileInput() ?>
    <?php if ($model->img): ?>
        <?= Html::img('@web/'.$model->img.'' ,
            ['alt' => $model->title , 'class' => 'preview_img']); ?>
    <?php endif; ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
