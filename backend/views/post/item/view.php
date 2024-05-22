<?php

use backend\models\post\PostCategories;
use backend\models\post\Posts;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\models\post\Posts */

$this->title = $model->title;
if (Helper::checkRoute('index')) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Post Categories'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = ['label' => $model->category->name, 'url' => ['view', 'id' => $model->category_id]];
$this->params['breadcrumbs'][] = $this->title;

$visible_field_in_types = Posts::_getVisibilityFields();
$category_key = PostCategories::getKeyTypeArray($model->category->key);

?>

<div class="posts-view box box-info">
    <div class="box-body">

        <p class="btn-group">
            <?php
            if (Helper::checkRoute('update-item'))
                echo Html::a(Yii::t('views', 'Update'), ['update-item', 'id' => $model->id], ['class' => 'btn btn-primary']);

            if (Helper::checkRoute('delete-item'))
                echo Html::a(Yii::t('views', 'Delete'), ['delete-item', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('views', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]);

            ?>
        </p>

        <div class="row">
            <div class="col-md-10 col-lg-8">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        [
                            'attribute' => 'category_id',
                            'format' => 'raw',
                            'value' => !empty($model->category->name) ? $model->category->name : $model->category_id,
                        ],
                        'title',
                        [
                            'attribute' => 'slug',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['slug']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'work_position',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['work_position']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'address',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['address']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'work_phone',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['work_phone']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'work_days',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['work_days']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'work_time',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['work_time']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'latitude',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['latitude']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'longitude',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['longitude']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'image',
                            'format' => 'raw',
                            'value' => Html::img($model->image, ['alt' => 'post', 'width' => 250]),
                            'visible' => (in_array($category_key, $visible_field_in_types['image']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'file',
                            'format' => 'raw',
                            'value' => Html::a($model->file, $model->file,['target' => '_blank']),
                            'visible' => (in_array($category_key, $visible_field_in_types['file']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'summary',
                            'format' => 'ntext',
                            'visible' => (in_array($category_key, $visible_field_in_types['summary']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'body',
                            'format' => 'ntext',
                            'visible' => (in_array($category_key, $visible_field_in_types['body']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'meta_title',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['meta_title']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'meta_keywords',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['meta_title']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'meta_description',
                            'format' => 'ntext',
                            'visible' => (in_array($category_key, $visible_field_in_types['meta_title']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'weight',
                            'format' => 'raw',
                            'visible' => (in_array($category_key, $visible_field_in_types['weight']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => $model->getStatusName(),
                            'visible' => (in_array($category_key, $visible_field_in_types['status']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => 'datetime',
                            'visible' => (in_array($category_key, $visible_field_in_types['created_at']) || Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'updated_at',
                            'format' => 'datetime',
                            'visible' => (in_array($category_key, $visible_field_in_types['updated_at']) || Yii::$app->user->can('administrator')),
                        ],
                    ],
                ]) ?>
            </div>
        </div>

    </div>
</div>
