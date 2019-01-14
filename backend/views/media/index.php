<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.01.19
 * Time: 0:30
 */
use yii\bootstrap\Html;

$this->title = Yii::t('app', 'Медиатека');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="row">
        <?php foreach ($imgs as $img): ?>
        <div class="col-md-3">
            <?= Html::a(Html::img($img , ['alt' => basename($img), 'class' => 'media-img' ]), $img, ['rel' => 'fancybox' , 'class' => 'link-media-img'])?>
        </div>
        <?php endforeach; ?>
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

