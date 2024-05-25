<?php

use backend\models\page\Pages;
use backend\modules\policy\models\PolicyTravel;
use backend\modules\policy\models\PolicyTravelTraveller;
use frontend\modules\policy\assets\TravelAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $program array */
/* @var $logo string */
/* @var $modelPage Pages */
/* @var $model PolicyTravel */
/* @var $modelTravellers PolicyTravelTraveller */

TravelAsset::register($this);

$this->title = !empty($modelPage->name) ? $modelPage->name : Yii::t('policy','Онлайн оформление полиса TRAVEL');
$this->params['breadcrumbs'][] = !empty($modelPage->name) ? $modelPage->name : Yii::t('policy','Онлайн оформление полиса TRAVEL');

$this->params['meta_title'] = !empty($modelPage->meta_title) ? $modelPage->meta_title : null;
$this->params['meta_type'] = 'page';
$this->params['meta_url'] = Yii::$app->request->hostInfo . Url::current();
if (!empty($modelPage->image)) {
    $this->params['meta_image'] = Yii::$app->request->hostInfo . $modelPage->image;
} else {
    $this->params['meta_image'] = $logo;
}
if (!empty($modelPage->meta_keywords))
    $this->params['meta_keywords'] = $modelPage->meta_keywords;
if (!empty($modelPage->meta_description))
    $this->params['meta_description'] = $modelPage->meta_description;

?>

<?php if (!empty($modelPage->body)):?>
    <?php $this->params['footer_text'] = $modelPage->body; ?>
<?php endif;?>

<?php if (!empty($modelPage->image)):?>
    <?php $this->params['header_bg_image'] = $modelPage->image; ?>
<?php endif;?>

<section class="calculator-policy">
    <div class="policy-osgo-create">
        <div class="container">
            <div class="flex-center-block contact-us-page__container my-3">
                <?= $this->render('_form_anketa', [
                    'model' => $model,
                    'program' => $program,
                    'modelTravellers' => $modelTravellers,
                ]) ?>

            </div>
        </div>
</section>
