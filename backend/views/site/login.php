<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\Alert;

$this->title = 'Авторизация';
?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 form-login">
            <?= Alert::widget() ?>
            <h1><?= Html::encode($this->title) ?></h1>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->label('Пользователь')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->label('Пароль')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->label('Запомнить меня')->checkbox() ?>

            <div style="color:#999;margin:1em 0">
                Если вы забыли свой пароль, вы можете <?= Html::a('сбросить его', ['/../site/request-password-reset']) ?>.
            </div>

            <div class="form-group">
                <?= Html::submitButton('Авторизироваться', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
