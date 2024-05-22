<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \backend\models\page\Pages */
/* @var $logo string */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $model->name;

$this->params['meta_type'] = 'page';
$this->params['meta_url'] = Yii::$app->request->hostInfo . '/page/' . $model->url;
$this->params['meta_image'] = $logo;
if ($model->meta_keywords)
    $this->params['meta_keywords'] = $model->meta_keywords;
if ($model->meta_description)
    $this->params['meta_description'] = $model->meta_description;

$this->params['container_class'] = 'search-page';

?>

<?php if (!empty($model->image)):?>
    <div class="sp-image">
        <?= Html::img($model->image, ['alt' => $model->name, 'class' => 'img'])?>
    </div>
<?php endif;?>

<div class="deals page-view">
    <?= $model->body ?>
</div>
