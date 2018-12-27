<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Portfolio */

$this->title = Yii::t('app', 'Создать работу');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Все работы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portfolio-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
