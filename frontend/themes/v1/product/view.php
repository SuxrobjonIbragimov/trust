<?php

use backend\models\insurance\InsuranceProduct;
use backend\models\insurance\InsuranceProductItem;
use yii\helpers\Html;
use backend\models\post\Posts;

/* @var $this yii\web\View */
/* @var $model InsuranceProduct */

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;

$this->params['meta_type'] = 'page';
$this->params['meta_url'] = Yii::$app->request->hostInfo . '/product/view/' . $model->slug;
$this->params['meta_image'] = Yii::$app->request->hostInfo . $model->image;
if ($model->meta_keywords)
    $this->params['meta_keywords'] = $model->meta_keywords;
if ($model->meta_description)
    $this->params['meta_description'] = $model->meta_description;

$what_included = $model->getInsuranceProductItems(InsuranceProductItem::TYPE_WHAT_INCLUDED)->all();
$what_to_do = $model->getInsuranceProductItems(InsuranceProductItem::TYPE_WHAT_TO_DO)->all();

?>

<?php
$path = 'styles/product.css';
$v = '?v=0.0.1';
$file_path = Yii::getAlias('@webroot/themes/v1/'.$path);
if (file_exists($file_path)) {
    $v = '?v=' . filemtime($file_path);
}
$path .=$v;
$this->registerCssFile("@web/themes/v1/{$path}", [
    'depends' => [\frontend\assets\V1Asset::className()],
], 'css-dd-theme-product');

?>
<div class="deals product-view">
    <?= $this->render('_product_top_info')?>

    <span class="summary">
        <?= $model->summary ?>
    </span>

    <div class="desc">
        <?= $model->description ?>
    </div>

    <?php if (!empty($model->calc_link) && ($model->calc_link != '#')) : ?>
        <div class="d-flex justify-content-center align-items-center">
            <a href="<?= !empty($model->calc_link) ? $model->calc_link : '#' ?>"
               class="btn btn-primary rounded-2 d-inline-block rounded-0 text-uppercase fs-5 fw-bold px-4 py-2 mt-2"><?= Yii::t('frontend', 'Купить') ?></a>
        </div>
    <?php endif; ?>

</div>
