<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\datetime\DateTimePicker;
use kartik\date\DatePicker;
mihaildev\elfinder\Assets::noConflict($this);

/* @var $this yii\web\View */
/* @var $model backend\models\Post */
/* @var $form yii\widgets\ActiveForm */
$id = \Yii::$app->user->identity->getId();
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->label('Заголовок')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'anons')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(),[
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',[]),
    ]);?>

    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map($category, 'id', 'title')
    ) ?>

    <?= $form->field($model, 'author_id')->dropDownList(
        ArrayHelper::map($authors, 'id', 'username'), ['options' => [$id => ['Selected'=>'selected']]]
    ) ?>
    <?= $form->field($model, 'publish_status')->
    dropDownList([ 'draft' => 'Черновик', 'publish' => 'Опубликовано', ]) ?>

    <?= $form->field($model, 'publish_date')->widget(DatePicker::className(),[
        'name' => 'check_issue_date',
        'value' => date('d-M-Y', strtotime('+2 days')),
        'options' => ['placeholder' => 'Select issue date ...'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
]); ?>

    <?= $form->field($model, 'upload')->fileInput(['multiple' => true])->label(false); ?>

    <div class="form-group">
        <?= Html::button(Yii::t('app', 'Загрузить фото'), ['class' => 'btn btn-success upload_gallary']) ?>
        <?= Html::img('/admin/images/staticimg/AjaxLoader2.gif' , ['class' => 'portfolio-loader']) ?>
    </div>

    <?php if ($model->img): ?>
        <?= Html::img('@web/'.$model->img.'' ,
            ['alt' => $model->title , 'class' => 'preview_img']); ?>
    <?php endif; ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
