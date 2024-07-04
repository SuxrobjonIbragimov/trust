<?php

use backend\models\post\PostCategories;
use backend\models\post\PostFile;
use yii\helpers\Html;
use backend\models\post\Posts;

/* @var $this yii\web\View */
/* @var $model Posts */
/* @var $postFiles PostFile */
/* @var $item PostFile */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $model->category->name, 'url' => ['category', 'slug' => $model->category->slug]];
$this->params['breadcrumbs'][] = $this->title;

$this->params['meta_type'] = 'post';
$this->params['meta_url'] = Yii::$app->request->hostInfo . '/post/view/' . $model->slug;
$this->params['meta_image'] = Yii::$app->request->hostInfo . $model->image;
if ($model->meta_keywords)
    $this->params['meta_keywords'] = $model->meta_keywords;
if ($model->meta_description)
    $this->params['meta_description'] = $model->meta_description;
?>

<div class="deals post-view">
    <?php if ($model->category->key != PostCategories::KEY_GALLERY): ?>
        <p class="summary">
            <?= htmlspecialchars($model->summary )?>
        </p>
        <div class="description">
            <?= $model->body ?>
        </div>
    <?php endif; ?>
    <?php if ($model->category->key == PostCategories::KEY_GALLERY):?>
        <?php $postFiles = $model->getPostFiles()->orderBy(['position' => SORT_ASC])->limit(PostFile::ITEMS_LIMIT)->all(); ?>
        <?php if (!empty($postFiles)):?>
            <div class="row mt-3">
                <?php foreach ($postFiles as $item):?>
                    <div class="col-sm-4 my-3">
                        <a href="<?= $item->path; ?>" data-fancybox="gallery" class="">
                            <img class="border-primary-custom latest-news__bShadow-0818-8 br-18 w-100 h-100 m-0" src="<?= $item->path; ?>" alt="<?= $model->title; ?>">
                        </a>
                    </div>
                <?php endforeach;?>
            </div>
        <?php endif;?>
    <?php endif;?>
</div>
