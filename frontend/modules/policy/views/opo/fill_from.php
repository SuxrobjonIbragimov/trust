<?php

use frontend\modules\policy\assets\OsgorAsset;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyOsgo */
/* @var $modelPage \backend\models\page\Pages */
/* @var $logo string */


OsgorAsset::register($this);

$this->title = !empty($modelPage->name) ? $modelPage->name : Yii::t('policy', 'Онлайн оформление полиса ОПО');
$this->params['breadcrumbs'][] = !empty($modelPage->name) ? $modelPage->name : Yii::t('policy', 'Онлайн оформление полиса ОПО');

$this->params['meta_title'] = !empty($modelPage->meta_title) ? $modelPage->meta_title : null;
$this->params['meta_type'] = 'page';
$this->params['meta_url'] = Yii::$app->request->hostInfo . \yii\helpers\Url::current();
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

<?php if (!empty($modelPage->body)): ?>
    <?php $this->params['footer_text'] = $modelPage->body; ?>
<?php endif; ?>

<?php if (!empty($modelPage->image)): ?>
    <?php $this->params['header_bg_image'] = $modelPage->image; ?>
<?php endif; ?>

<section class="calculator-policy">
    <div class="policy-osgo-create">
        <div class="flex-center-block contact-us-page__container my-3">
            <?= $this->render('_form_anketa', [
                'model' => $model,
            ]) ?>
        </div>
</section>
