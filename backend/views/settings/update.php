<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */

$this->title = Yii::t('app', 'Обновить настройку: ' . $model->name, [
    'nameAttribute' => '' . $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Настройки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->key]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Обновить');
?>
<div class="settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
