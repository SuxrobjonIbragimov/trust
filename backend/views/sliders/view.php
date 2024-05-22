<?php

use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use kartik\sortable\Sortable;
use backend\widgets\PageSize;
use backend\widgets\ActionsApply;
use backend\models\sliders\SliderItems;
use backend\modules\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\models\sliders\Sliders */
/* @var $searchModel backend\models\sliders\SliderItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Sliders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sliders-view box box-info">
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
                        'key',
                        'name',
                        'created_at:datetime',
                        'updated_at:datetime',
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => $model->getStatusName(),
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>

<div class="slider-items-index box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Html::encode(Yii::t('views', 'Slider Items')) ?></h3>
    </div>
    <div class="box-body">
        <p>
            <?php if (Helper::checkRoute('create-item'))
                echo Html::a(Yii::t('views', 'Create'), ['create-item', 'slider_id' => $model->id], ['class' => 'btn btn-success']); ?>
        </p>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1" data-toggle="tab">
                        <i class="fa fa-th"></i>&nbsp;<b> <?= Yii::t('views', 'Grid') ?></b></a>
                </li>
                <li>
                    <a href="#tab_2" data-toggle="tab">
                        <i class="fa fa-sort"></i>&nbsp;<b> <?= Yii::t('views', 'Sort') ?></b></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane grid active" id="tab_1">

                    <?php ActionsApply::begin([
                        'action' => ['apply-items', 'slider_id' => $model->id],
                        'template' => '<div class="form-inline margin-bottom-5">{list}{button}'
                    ]);

                    echo PageSize::widget([
                        'template' => Helper::checkRoute('apply-items') ? '{list}&nbsp;{label}</div>' :
                            '<div class="form-inline margin-bottom-5">{list}&nbsp;{label}</div>'
                    ]); ?>
                    <div class="table-responsive">
                        <?php Pjax::begin(['id' => 'data_grid']); ?>
                        <?= GridView::widget([
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
                                'title',
                                [
                                    'attribute' => 'image',
                                    'format' => 'raw',
                                    'filter' => false,
                                    'value' => function (SliderItems $data) {
                                        return Html::img($data->image, ['width' => 200]);
                                    },
                                ],
                                'link',
                                [
                                    'attribute' => 'weight',
                                    'visible' => (Yii::$app->user->can('administrator')),
                                ],
                                'description:ntext',
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'filter' => SliderItems::getStatusArray(),
                                    'value' => function (SliderItems $data) {
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
                                ],

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => Helper::filterActionColumn(['update-item', 'delete-item']),
                                    'buttons' => [
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
                        ]); ?>
                        <?php Pjax::end(); ?>
                    </div>
                    <?php ActionsApply::end(); ?>
                </div>

                <div class="tab-pane tree" id="tab_2">

                    <div class="sortable-tab col-md-4">
                        <?php
                        $items = [];
                        if (!empty($sliderItems = $model->sliderItems)) {
                            foreach ($sliderItems as $item) {
                                array_push($items,
                                    [
                                        'content' => Html::img($item->image,
                                            ['alt' => $item->id, 'title' => $item->title, 'class' => 'img-responsive'])
                                    ]
                                );
                            }
                            echo Sortable::widget([
                                'items' => $items,
                                'pluginEvents' => [
                                    'sortupdate' => 'function(e) {
                                        var overlay = $("#overlay");
                                        overlay.show();
                                        var arr = e.target.children;
                                        var data = [];
                                        for (var i = 0; i < arr.length; i++) {
                                            data.push(arr[i].children[0].alt);
                                        }
                                        $.ajax({
                                            url: "sortable-items",
                                            type: "POST",
                                            data: {data},
                                            success: function (data) {
                                            $.pjax.reload({
                                                    container: "#data_grid"
                                                }).done(function () {
                                                    overlay.hide();
                                                });
                                            }                                        
                                        });
                                    }',
                                ]
                            ]);
                        }
                        ?>
                    </div>
                    <div class="clearfix"></div>

                </div>
            </div>
        </div>


    </div>
</div>