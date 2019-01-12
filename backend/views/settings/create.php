<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Settings */

$this->title = Yii::t('app', 'Создать настройку');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Настройки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
