<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <?php Pjax::begin([
        'id' => 'pjax-list',
    ]); ?>

    <?php if (Yii::$app->session->hasFlash('delete_category')): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo Yii::$app->session->getFlash('delete_category'); ?>
        </div>
    <?php endif;?>

    <h1>Список категорий</h1>

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"{summary}\n{items}\n{pager}",
        'summary' => 'Показано {count} из {totalCount} категорий',
        'summaryOptions' => [
            'tag' => 'span',
            'class' => 'summary'
        ],
        'columns' => [
            'title',
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'buttons' => [
                    'delete' => function ($url) {
                        return Html::a('', false, ['class' => 'glyphicon glyphicon-trash delete-category-item' , 'pjax-container' => 'pjax-list', 'delete-url'     => $url, 'title' => 'Delete']);

                    },
                ],
            ],
        ],
    ]); ?>
    <?php
    $this->registerJs("
        $('.delete-category-item').click(function(e) {
        $('.alert-success').children().children().trigger('click');
            e.preventDefault();
            var url = $(this).attr('delete-url');
            var id = url.split('=');
            var pjaxContainer = $(this).attr('pjax-container');
            $.ajax({
                url: '/admin/category/delete',
                data: { id: id[1] },
                type: 'GET',
                success: function (res) {
                    $('.breadcrumb').after(res);
                },
                error: function (res) {
                    alert(res);
                }
            }).done(function (data) {
                  $.pjax.reload({container: '#' + $.trim(pjaxContainer)});
                });
        });
    ");?>
    <?php Pjax::end(); ?>
</div>
