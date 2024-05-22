<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\widgets\LinkPager;
use backend\models\post\PostCategories;

/* @var $this yii\web\View */
/* @var $filterData array */
/* @var $y int */
/* @var $model PostCategories */
/* @var $dataProvider \yii\data\ActiveDataProvider */

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

$render_item = '_item';
$key_type = PostCategories::getKeyTypeArray($model->key);
$render_item .= !empty($key_type) ? '_' . $key_type : null;
$render_item_class = PostCategories::getKeyColClassArray($key_type);

?>

<?php
$path = 'styles/posts.css';
$v = '?v=0.0.1';
$file_path = Yii::getAlias('@webroot/themes/v1/' . $path);
if (file_exists($file_path)) {
    $v = '?v=' . filemtime($file_path);
}
$path .= $v;
$this->registerCssFile("@web/themes/v1/{$path}", [
    'depends' => [\frontend\assets\V1Asset::className()],
], 'css-dd-theme-posts');

$map_data = [];
?>
<div class="deals posts-category category-<?= $model->key ?> mt-3 py-3">

    <?php if ($key_type == PostCategories::KEY_FILES):?>
        <?php if (!empty($filterData)):?>
            <div class="d-flex flex-wrap align-items-center file-link-block year gap-2 col-md-12 col-sm-12">
                <?php foreach ($filterData as $year => $value_year):?>
                    <?php if (!empty($year)):?>
                        <a href="<?= Url::current(['y' => $year])?>" class="btn btn-warning <?= ($year == $y) ? 'active' : ''?>"><?= $year ?></a>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        <?php endif;?>
    <?php endif;?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'class' => 'row align-items-stretch justify-content-lg-between justify-content-center g-3',
        ],
        'layout' => "{items}\n<div align='center'>{pager}</div>",
        'itemView' => $render_item,
        'itemOptions' => ['class' => $render_item_class],
        'pager' => [
            'class' => LinkPager::className(),
            'maxButtonCount' => 7,
            'options' => [
                'tag' => 'ul',
                'class' => 'pagination justify-content-center my-3 pt-3',
                'id' => 'pager-container',
            ],
            'disabledListItemSubTagOptions' => [
                'class' => 'page-link',
            ],
            //Current Active option value
            'activePageCssClass' => 'page-active',

            // Css for each options. Links
            'linkOptions' => ['class' => 'page-link'],
            'disabledPageCssClass' => 'page-item disabled',

            // Customzing CSS class for navigating link
            'pageCssClass' => ['class' => 'page-item'],
            'prevPageCssClass' => 'page-item page-back',
            'nextPageCssClass' => 'page-item page-next',
            'firstPageCssClass' => 'page-item page-first',
            'lastPageCssClass' => 'page-item page-last',
        ]
    ]) ?>

</div>