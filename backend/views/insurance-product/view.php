<?php

use backend\models\insurance\InsuranceProduct;
use backend\models\insurance\InsuranceProductItem;
use backend\modules\admin\components\Helper;
use backend\widgets\ActionsApply;
use backend\widgets\PageSize;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\insurance\InsuranceProduct */
/* @var $searchModel \backend\models\insurance\InsuranceProductItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Insurance Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="insurance-product-view  box box-default">
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
                        'id',
                        'title',
                        'subtitle',
                        [
                            'attribute' => 'legal_type_ids',
                            'format' => 'raw',
                            'value' => function (InsuranceProduct $model) {
                                $result = '';
                                if (!empty($categories = $model->legalTypes)) {
                                    foreach ($categories as $category) {
                                        $result .= Html::tag('p', $category->name_ru, ['id' => $category->id, 'class' => 'list-group-item', ]);
                                    }
                                }

                                return '<div class="list-group col-lg-8 col-md-10">' . $result . '</div>';
                            },
                        ],
                        'slug',
                        'summary:ntext',
                        'description:ntext',
                        [
                            'attribute' => 'icon',
                            'format' => 'raw',
                            'value' => !empty($model->icon) ? Html::img($model->icon, ['alt' => 'post', 'width' => 250]) : $model->icon,
                        ],
                        [
                            'attribute' => 'image',
                            'format' => 'raw',
                            'value' => !empty($model->image) ? Html::img($model->image, ['alt' => 'post', 'width' => 250]) : $model->image,
                        ],
                        [
                            'attribute' => 'meta_title',
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'meta_keywords',
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'meta_description',
                            'format' => 'ntext',
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'views',
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'weight',
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'status',
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        'created_at:datetime',
                        [
                            'attribute' => 'updated_at',
                            'format' => 'datetime',
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                    ],
                ]) ?>
            </div>
        </div>

        <h3><?= Html::encode(Yii::t('views', 'Items')) ?></h3>
        <p>
            <?php if (Helper::checkRoute('create-item'))
                echo Html::a(Yii::t('views', 'Create'), ['create-item', 'insurance_product_id' => $model->id], ['class' => 'btn btn-success']); ?>
        </p>

        <div class="row">
            <div class="col-lg-10">
                <?php

                ActionsApply::begin([
                    'action' => ['apply-items', 'insurance_product_id' => $model->id],
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
                            'value' => function (InsuranceProductItem $data) {
                                return Html::a($data->title, ['view-item', 'id' => $data->id], ['data-pjax' => 0]);
                            },
                        ],
                        [
                            'attribute' => 'type',
                            'format' => 'raw',
                            'filter' => InsuranceProductItem::_getTypeList(),
                            'value' => function (InsuranceProductItem $data) {
                                return $data->_getTypeName();
                            },
                        ],
                        [
                            'attribute' => 'weight',
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'filter' => InsuranceProductItem::getStatusArray(),
                            'value' => function (InsuranceProductItem $data) {
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
                ActionsApply::end();
                ?>
            </div>
        </div>

    </div>
</div>
