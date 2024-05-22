<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\widgets\DetailView;
use backend\widgets\PageSize;
use backend\models\post\Posts;
use backend\widgets\ActionsApply;
use backend\modules\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\models\post\PostCategories */
/* @var $searchModel backend\models\post\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
if (Helper::checkRoute('index')) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Post Categories'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;

$visible_field_in_types = Posts::_getVisibilityFields();

?>
<div class="post-categories-view box box-info">
    <div class="box-body">

        <p class="btn-group">
            <?php
            if (Helper::checkRoute('update'))
                echo Html::a(Yii::t('views', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);

            if (Helper::checkRoute('delete'))
                echo Html::a(Yii::t('views', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('views', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]); ?>
        </p>

        <div class="row">
            <div class="col-md-8 col-lg-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
//                        'id',
                        'slug',
                        'name',
//                        'description:ntext',
//                        'meta_title',
//                        'meta_keywords',
//                        'meta_description:ntext',
                        [
                            'attribute' => 'image',
                            'format' => 'raw',
                            'value' => Html::img($model->image, ['alt' => 'post','width' => '200',]),
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => $model->getStatusName(),
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => 'date',
                            'filter' => false,
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'updated_at',
                            'format' => 'date',
                            'filter' => false,
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                    ],
                ]) ?>
            </div>
        </div>

        <h3><?= Html::encode(Yii::t('views', 'Posts')) ?></h3>
        <p>
            <?php if (Helper::checkRoute('create-item'))
                echo Html::a(Yii::t('views', 'Create'), ['create-item', 'category_id' => $model->id], ['class' => 'btn btn-success']); ?>
        </p>

        <div class="row">
            <div class="col-lg-10">
                <?php
                ActionsApply::begin([
                    'action' => ['apply-items', 'category_id' => $model->id],
                    'template' => '<div class="form-inline margin-bottom-5">{list}{button}'
                ]);

                echo PageSize::widget([
                    'template' => Helper::checkRoute('apply-items') ? '{list}&nbsp;{label}</div>' :
                        '<div class="form-inline margin-bottom-5">{list}&nbsp;{label}</div>'
                ]);

                Pjax::begin();
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'filterSelector' => 'select[name="per_page"]',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        ['class' => 'yii\grid\CheckboxColumn'],

                        [
                            'attribute' => 'id',
                            'options' => ['width' => '70'],
                        ],
                        [
                            'attribute' => 'title',
                            'format' => 'raw',
                            'value' => function (Posts $data) {
                                return Html::a($data->title, ['view-item', 'id' => $data->id], ['data-pjax' => 0]);
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'filter' => Posts::getStatusArray(),
                            'value' => function (Posts $data) {
                                return $data->getStatusName();
                            },
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => 'date',
                            'filter' => false,
                        ],
                        [
                            'attribute' => 'updated_at',
                            'format' => 'date',
                            'filter' => false,
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => Helper::filterActionColumn(['view-item', 'update-item', 'delete-item']),
                            'buttons' => [
                                'view-item' => function ($url) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('yii', 'View'),
                                        'data-pjax' => 0,
                                    ]);
                                },
                                'update-item' => function ($url) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('yii', 'Update'),
                                        'data-pjax' => 0,
                                    ]);
                                },
                                'delete-item' => function ($url) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'title' => Yii::t('yii', 'Delete'),
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'method' => 'post',
                                        ],
                                    ]);
                                }
                            ]
                        ],
                    ],
                ]);

                Pjax::end();
                ActionsApply::end(); ?>
            </div>
        </div>
    </div>
</div>
