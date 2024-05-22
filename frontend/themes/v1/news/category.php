<?php

use frontend\widgets\ItemWidget;
use frontend\widgets\SocialNetworksWidget;
use frontend\widgets\SubscribeWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\jui\SliderInput;
use yii\widgets\LinkPager;
use rmrevin\yii\fontawesome\FA;

/** @var $this yii\web\View */
/** @var $model \backend\modules\news\models\News */
/** @var $popularModels \backend\modules\news\models\News */
/** @var $category \backend\modules\news\models\NewsCategory */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $pagination \yii\data\Pagination */
/** @var $quantityText string */
/** @var $brandName string */

//Pjax::begin(['id' => 'pjax_news', 'timeout' => 2000]);

$queryParams = Yii::$app->request->queryParams;

if ($category != null) {
    $title = $category->name;
    $this->title = $category->meta_title;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Новосты'), 'url' => ['category', 'slug' => 'all']];
    if (($parent = $category->parent) != null) {
        if ($parent->parent != null)
            $this->params['breadcrumbs'][] = ['label' => $parent->parent->name, 'url' => ['category', 'slug' => $parent->parent->slug]];

        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'slug' => $parent->slug]];
    }
    $this->params['breadcrumbs'][] = $title;

    $this->params['meta_type'] = 'news-category';
    $this->params['meta_url'] = Yii::$app->request->hostInfo . '/news/category/' . $category->slug;
    $this->params['meta_image'] = Yii::$app->request->hostInfo . $category->image;
    if ($category->meta_keywords)
        $this->params['meta_keywords'] = $category->meta_keywords;
    if ($category->meta_description)
        $this->params['meta_description'] = $category->meta_description;
    if (!isset($queryParams['page']) || (isset($queryParams['page']) && $queryParams['page'] == 1))
        $this->params['footer_text'] = $category->body;

} else {
    $title = Yii::t('frontend', 'Все новосты');
    $this->title = $title;
    $this->params['breadcrumbs'][] = $title;
}

$this->params['container_class'] = 'category';

?>

<div class="ln-left">
    <div class="latest-news-top menu p-buttom30">
        <div class="title">
            <h1><?= $title ?></h1>
        </div>
    </div>
    <div class="ln-container">
        <?php  if (!empty($dataProvider->models)) { ?>
            <?= ItemWidget::widget(['items' => $dataProvider->models, 'itemsClass' => 'ln-news'])?>
        <?php } else {
            echo '<br><h4 class="col-md-12">' . Yii::t('frontend', 'Результатов не найдено.') . '</h4>';
        } ?>
    </div>

    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => '<i class="fas fa-angle-double-left"></i>',
        'prevPageLabel' => '<i class="fas fa-chevron-left"></i>',
        'nextPageLabel' => '<i class="fas fa-chevron-right"></i>',
        'lastPageLabel' => '<i class="fas fa-angle-double-right"></i>',
        'maxButtonCount' => 7,
    ]) ?>
</div>
<div class="ln-right">
    <div class="ln-sedebar">
        <?php  if (!empty($popularModels)) { ?>
            <h3 class="sidebar-title">
                <?=Yii::t('frontend','Популярные')?>
            </h3>
            <?= ItemWidget::widget(['items' => $popularModels, 'itemsClass' => 'sidebar-news', 'is_mini' => true])?>
        <?php } ?>
    </div>
    <div class="ln-sedebar">
        <?= SubscribeWidget::widget([]);?>
    </div>
</div>

<?php //Pjax::end() ?>
