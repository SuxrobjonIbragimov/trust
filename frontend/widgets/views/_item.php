<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $items yii\web\View */
/* @var $itemsClass yii\web\View */
/* @var $imageClass yii\web\View */
/* @var $is_hurry yii\web\View */
/* @var $is_mini yii\web\View */
/* @var $imgSizes array yii\web\View */
/* @var $author yii\web\View */

?>

<?php if (!empty($items)) :?>
    <?php foreach ($items as $item):?>
        <?php /* @var $item \backend\models\post\Posts */ ?>
        <?php if (!empty($item) && !$is_mini):?>
            <div class="<?= $itemsClass ?> col-lg-12 col-md-12 col-sm-10 col-12 my-2 border-primary-custom br-18 latest-news__bShadow-0818-8 bg-light my-3 p-4 height-256 flex-wrap">
                <div class="news-image col-lg-3 col-md-3 col-sm-12 col-12 border-primary-custom">
                    <?= Html::img($item->image, ['alt' => $item->title, 'class' => [$imageClass . ' p-2']])?>
                    <?php if (!empty($item->categories[0])):?>
                        <?= Html::a('<span class="'.$item->category->slug.'">'.$item->category->name.'</span>', ['/post/category', 'slug' => $item->category->slug]) ?>
                    <?php endif;?>
                </div>
                <div class="ln-news-text col-lg-8 col-md-8 col-sm-12 col-12 ps-3">
                    <h4><?= Html::a($item->title, ['/post/view/', 'slug' => $item->slug]) ?></h4>
                    <span class="text-info my-2 d-block"><?= Yii::$app->formatter->asDate($item->created_at, 'long')?></span>
                    <p class="lh-lg"><?= mb_substr(strip_tags($item->summary),0,75)?>...</p>
                </div>
            </div>
        <?php else:?>
            <div class="<?= $itemsClass ?>">
                <div class="sidebar-img"><?= Html::img($item->image, ['alt' => $item->title, 'class' => 'post-image'])?></div>
                <div class="sidebar-text">
                    <h3><?= Html::a($item->name, ['/post/view', 'slug' => $item->slug]) ?></h3>
                    <p><?= Yii::$app->formatter->asDate($item->created_at, 'long')?></p>
                </div>
            </div>
        <?php endif;?>
    <?php endforeach;?>
<?php endif;?>