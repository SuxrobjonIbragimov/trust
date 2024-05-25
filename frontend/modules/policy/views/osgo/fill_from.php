<?php

use frontend\modules\policy\assets\OsgoAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyOsgo */
/* @var $modelDrivers backend\modules\policy\models\PolicyOsgoDriver */
/* @var $modelPage \backend\models\page\Pages */
/* @var $logo string */


OsgoAsset::register($this);

$this->title = $modelPage->name;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('policy', 'Calculate Policy'), 'url' => ['calculate']];
$this->params['breadcrumbs'][] = $modelPage->name;

$this->params['meta_type'] = 'page';
$this->params['meta_url'] = Yii::$app->request->hostInfo . \yii\helpers\Url::current();
if (!empty($modelPage->image)) {
    $this->params['meta_image'] = Yii::$app->request->hostInfo . $modelPage->image;
} else {
    $this->params['meta_image'] = $logo;
}
if ($modelPage->meta_keywords)
    $this->params['meta_keywords'] = $modelPage->meta_keywords;
if ($modelPage->meta_description)
    $this->params['meta_description'] = $modelPage->meta_description;

?>

<?php if (!empty($modelPage->image)):?>
    <?php $this->params['header_bg_image'] = $modelPage->image; ?>
<?php endif;?>

<section class="calculator-policy">
    <div class="policy-osgo-create">

            <div class="flex-center-block contact-us-page__container my-3">
                <?= $this->render('_form_anketa', [
                    'model' => $model,
                    'modelDrivers' => $modelDrivers,
                ]) ?>

            </div>
    </div>
</section>
