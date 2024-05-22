<?php

use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\widgets\LinkPager;
use rmrevin\yii\fontawesome\FA;
use backend\models\post\PostCategories;

/* @var $this yii\web\View */
/* @var $model PostCategories */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;

$this->params['meta_type'] = 'post-category';
$this->params['meta_url'] = Yii::$app->request->hostInfo . '/post/category/' . $model->slug;
if ($model->meta_keywords)
    $this->params['meta_keywords'] = $model->meta_keywords;
if ($model->meta_description)
    $this->params['meta_description'] = $model->meta_description;
if (!empty($model->image))
    $this->params['meta_image'] = Yii::$app->request->hostInfo . $model->image;
if ($model->description)
    $this->params['footer_text'] = $model->description;
?>

<div class="deals posts-category">
    <?php Pjax::begin(); ?>
    <?= ListView::widget([
        'dataProvider' => $model->getPostsDataProvider(),
        'options'   => [
            'class' => 'row',
        ],
        'layout' => "{items}\n<div align='center'>{pager}</div>",
        'itemView' => '_item',
        'itemOptions' => ['class' => 'col-md-6 col-lg-4'],
        'pager' => [
            'class' => LinkPager::className(),
            'firstPageLabel' => FA::icon(FA::_ANGLE_DOUBLE_LEFT),
            'prevPageLabel' => FA::icon(FA::_ANGLE_LEFT),
            'nextPageLabel' => FA::icon(FA::_ANGLE_RIGHT),
            'lastPageLabel' => FA::icon(FA::_ANGLE_DOUBLE_RIGHT),
            'maxButtonCount' => 7,
        ]
    ]) ?>
    <?php Pjax::end(); ?>

</div>
