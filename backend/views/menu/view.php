<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use kartik\select2\Select2;
use backend\widgets\PageSize;
use backend\widgets\ActionsApply;
use backend\models\menu\MenuItems;
use wbraganca\fancytree\FancytreeWidget;
use backend\modules\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\Menus */
/* @var $searchModel backend\models\menu\MenuItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dataTree array */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menus-view box box-info">
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
            <div class="col-lg-4 col-md-8">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'key',
                        'name',
                        'description:ntext',
                        'created_at:datetime',
                        'updated_at:datetime',
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => $model->getStatusName(),
                        ],
                    ],
                ]) ?>

            </div>
        </div>

    </div>
</div>

<div class="menus-view box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Html::encode(Yii::t('views', 'Menu Items')) ?></h3>
    </div>
    <div class="box-body">
        <p>
            <?php if (Helper::checkRoute('create-item'))
                echo Html::a(Yii::t('views', 'Create'), ['create-item', 'menu_id' => $model->id], ['class' => 'btn btn-success']); ?>
        </p>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1" data-toggle="tab">
                        <i class="fa fa-th"></i>&nbsp;<b> <?= Yii::t('views', 'Grid') ?></b></a>
                </li>
                <li>
                    <a href="#tab_2" data-toggle="tab">
                        <i class="fa fa-tree"></i>&nbsp;<b> <?= Yii::t('views', 'Tree') ?></b></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane grid active" id="tab_1">

                    <?php ActionsApply::begin([
                        'action' => ['apply-items', 'menu_id' => $model->id],
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
                                [
                                    'attribute' => 'parent_id',
                                    'format' => 'raw',
                                    'filter' => Select2::widget([
                                        'data' => MenuItems::getMenuItemsArray($model->id),
                                        'model' => $searchModel,
                                        'attribute' => 'parent_id',
                                        'theme' => Select2::THEME_DEFAULT,
                                        'options' => ['placeholder' => ''],
                                        'pluginOptions' => ['allowClear' => true],
                                    ]),
                                    'value' => function (MenuItems $data) {
                                        return $data->parent_id ? Html::a($data->parent->label, ['view-item', 'id' => $data->parent_id], ['data-pjax' => 0]) : $data->parent_id;
                                    },
                                    'options' => ['width' => '250'],
                                ],
                                [
                                    'attribute' => 'label',
                                    'format' => 'raw',
                                    'value' => function (MenuItems $data) {
                                        return Html::a($data->label, ['view-item', 'id' => $data->id], ['data-pjax' => 0]);
                                    },
                                ],
                                'url',
                                'weight',
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'filter' => MenuItems::getStatusArray(),
                                    'value' => function (MenuItems $data) {
                                        return $data->getStatusName();
                                    },
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
                        ]); ?>
                        <?php Pjax::end(); ?>
                    </div>
                    <?php ActionsApply::end(); ?>
                </div>
                <div class="tab-pane tree" id="tab_2">

                    <?php Pjax::begin(['id' => 'data_tree']); ?>
                    <?= (!empty($dataTree) && Helper::checkRoute('drag-drop')) ? FancytreeWidget::widget([
                        'options' => [
                            'source' => $dataTree,
                            'extensions' => ['dnd'],
                            'dnd' => [
                                'preventVoidMoves' => true,
                                'preventRecursiveMoves' => true,
                                'autoExpandMS' => 400,
                                'dragStart' => new JsExpression('function(node, data) {
                                    return true;
                                }'),
                                'dragEnter' => new JsExpression('function(node, data) {
                                    return true;
                                }'),
                                'dragDrop' => new JsExpression('function(node, data) {
                                    var overlay = $("#overlay");
                                    var hitMode = data.hitMode;
                                    var nodeId = data.otherNode.key;
                                    var otherNodeId = node.key;
                                    overlay.show();
                                    data.otherNode.moveTo(node, hitMode);
                                    $.ajax({
                                        url: "drag-drop",
                                        type: "post",
                                        data: {
                                            hit_mode: hitMode,
                                            id: nodeId,
                                            other_id: otherNodeId
                                        },
                                        success: function (data) {
                                            $.pjax.reload({
                                                container: "#data_grid"
                                            }).done(function () {
                                                $.pjax.reload({
                                                    container: "#data_tree"
                                                }).done(function () {
                                                    overlay.hide();
                                                });
                                            });
                                        }
                                    });
                                    
                                }'),
                            ],
                        ]
                    ]) : Yii::t('views', 'No results found.'); ?>
                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>


    </div>
</div>