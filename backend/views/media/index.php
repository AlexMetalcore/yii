<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.01.19
 * Time: 0:30
 */
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Медиатека');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

    <?php if (Yii::$app->session->hasFlash('upload-file')): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo Yii::$app->session->getFlash('upload-file'); ?>
        </div>
    <?php endif;?>
    <?php $form = ActiveForm::begin([
        /*'enableAjaxValidation'   => true,
        'enableClientValidation' => true,
        'validateOnBlur'         => false,
        'validateOnType'         => false,
        'validateOnChange'       => false,
        'validateOnSubmit'       => true,*/
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <?= $form->field($model, 'media_upload[]')->fileInput(['multiple' => true])->label(false); ?>

    <div class="form-group">
        <?= Html::button(Yii::t('app', 'Загрузить фото'), ['class' => 'btn btn-success upload_gallary']) ?>
        <?= Html::img('/admin/images/staticimg/AjaxLoader2.gif' , ['class' => 'portfolio-loader']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="row">
        <?php Pjax::begin([
            'id' => 'pjax-list-media',
        ]); ?>
        <?php foreach ($imgs as $img => $date): ?>
        <div class="col-md-3">
            <span>Дата загрузки: <?=$date?></span>
            <?= Html::img('/admin/images/staticimg/delete.png' , ['class' => 'img-delete-item']) ?>
            <?= Html::a(Html::img($img , ['alt' => basename($img), 'class' => 'media-img' ]), $img, ['rel' => 'fancybox' , 'class' => 'link-media-img'])?>
        </div>
        <?php endforeach; ?>
        <?php Pjax::end(); ?>
    </div>
</div>
<?= newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=fancybox]',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);

