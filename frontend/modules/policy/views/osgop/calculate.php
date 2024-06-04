<?php

use frontend\modules\policy\assets\OsgoAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyOsgo */
/* @var $modelPage \backend\models\page\Pages */
/* @var $logo string */

OsgoAsset::register($this);

$this->title = !empty($modelPage->name) ? $modelPage->name : Yii::t('policy','Онлайн оформление полиса ОСГОВТС');
$this->params['breadcrumbs'][] = !empty($modelPage->name) ? $modelPage->name : Yii::t('policy','Онлайн оформление полиса ОСГОВТС');

$this->params['meta_title'] = $modelPage->meta_title;
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

<?php if (!empty($modelPage->body)):?>
    <?php $this->params['footer_text'] = $modelPage->body; ?>
<?php endif;?>

<?php if (!empty($modelPage->image)):?>
    <?php $this->params['header_bg_image'] = $modelPage->image; ?>
<?php endif;?>

<main class="middle">
    <div class="policy-travel-create">
        <div class="container">
            <div class="flex-center-block contact-us-page__container my-3">

                <?= $this->render('_form_calc', [
                    'model' => $model,
                ]) ?>

            </div>
        </div>
</main>
