<?php
use yii\helpers\Html;

$this->title = 'Портфолио';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portfolio-index">
    <div class="container">
        <div class="row">
            <?php foreach ($portfolios as $portfolio): ?>
            <?php $main_img = explode(',' ,$portfolio->img)[0]; ?>
                <div class="col-md-6">
                    <div class="block-portfolio">
                        <div class="overlay-portfolio">
                            <span class="portfolio-name"><?= $portfolio->title; ?></span>
                        </div>
                        <?= Html::img('/admin/'. $main_img .'', ['class' => 'portfolio_img']); ?>
                        <input type="hidden" id="item-id" value="<?= $portfolio->id; ?>">
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
    <div class="container">
        <?= Html::img('/admin/images/staticimg/AjaxLoader2.gif' , ['class' => 'portfolio-loader']) ?>
    </div>
</div>
<div class="items-overlay-portfolio"></div>