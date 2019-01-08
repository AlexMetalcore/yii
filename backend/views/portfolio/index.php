<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Все работы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portfolio-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="create-portfolio">
        <?= Html::a(Yii::t('app', 'Создать работу'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php Pjax::begin([
        'id' => 'pjax-list',
    ]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"{summary}\n{items}\n{pager}",
        'summary' => 'Показано {count} из {totalCount} работ',
        'emptyText' => 'Работ нет',
        'summaryOptions' => [
            'tag' => 'span',
            'class' => 'summary'
        ],
        'columns' => [
            'title',
            'content:ntext',
            [
                'attribute' => 'img',
                'value' => function($model) {
                    $imgs = '';
                    foreach (explode(',' , $model->img) as $img){
                        $imgs .= !empty($img) ? '<img src="/admin/'.$img.'" class="img-preview-portfolio"/>' : 'нету фото';
                    };
                    return $imgs;
                },
                'format'    => 'raw',
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'buttons' => [
                    'delete' => function ($url) {
                        return Html::a('', false, ['class' => 'glyphicon glyphicon-trash delete-portfolio-item' , 'pjax-container' => 'pjax-list', 'delete-url'     => $url, 'title' => 'Delete']);

                    },
                ],
            ],
        ],
    ]); ?>
    <?php
    $this->registerJs("
        $('.delete-portfolio-item').click(function(e) {
        $('.alert-success').children().children().trigger('click');
            e.preventDefault();
            var url = $(this).attr('delete-url');
            var id = url.split('=');
            var pjaxContainer = $(this).attr('pjax-container');
            $.ajax({
                url: '/admin/portfolio/delete',
                data: { id: id[1] },
                type: 'GET',
                success: function (res) {
                    $('.breadcrumb').after('<div class=\"alert alert-success alert-dismissible\" role=\"alert\">'+res+'<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button></div>');
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
