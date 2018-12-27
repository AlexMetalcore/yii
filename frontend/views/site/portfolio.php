<?php
use yii\helpers\Html;

$this->title = 'Портфолио';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row">
        <?php foreach ($portfolios as $portfolio): ?>
            <div class="col-md-6">
                <div class="block-portfolio">
                    <div class="overlay-portfolio">
                        <span class="portfolio-name"><?= $portfolio->title; ?></span>
                    </div>
                    <?= Html::img('/admin/'. $portfolio->getMainImg().'', ['class' => 'portfolio_img']); ?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
