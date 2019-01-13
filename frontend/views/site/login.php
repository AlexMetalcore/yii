<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <h1><?= Html::encode($this->title) ?></h1>
            <?php $form = ActiveForm::begin(['id' => 'login-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                ]); ?>

                <?= $form->field($model, 'username')->label('Пользователь')->textInput(['autofocus' => true , 'class' => 'input-login-site form-control']) ?>

                <?= $form->field($model, 'password')->label('Пароль')->passwordInput(['class' => 'input-login-site form-control']) ?>

                <?= $form->field($model, 'rememberMe')->label('Запомнить меня')->checkbox() ?>

                <?= Html::a('Регистрация', ['site/signup'] , ['class' => 'register_link']) ?>

                <div style="color:#999;margin:1em 0">
                    Если вы забыли свой пароль, вы можете <?= Html::a('сбросить его', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Авторизироваться', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
