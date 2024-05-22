<?php

use backend\models\insurance\InsuranceProductItem;
use backend\modules\admin\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\insurance\InsuranceProductItem */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Insurance Product Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="insurance-product-item-info">
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
                ]); ?>
        </p>


        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'insurance_product_id',
                    'format' => 'raw',
                    'value' => function (InsuranceProductItem $model) {
                        return Html::a($model->insuranceProduct->title, ['/insurance-product/view', 'id' => $model->insuranceProduct->id], ['data-pjax' => 0]);
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
                'title',
//                'description:ntext',
//                'parent_id',
//                'image',
//                'icon',
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
