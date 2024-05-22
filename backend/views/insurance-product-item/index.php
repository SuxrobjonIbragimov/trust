<?php

use backend\models\insurance\InsuranceProductItem;
use backend\modules\admin\components\Helper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\insurance\InsuranceProductItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('model', 'Insurance Product Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-product-item-index box box-default">
    <div class="box-body">
        <p class="btn-group">
            <?php if (Helper::checkRoute('create'))
                echo Html::a(Yii::t('views', 'Create'), ['create'], ['class' => 'btn btn-success']); ?>
        </p>

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'insurance_product_id',
                    'format' => 'raw',
                    'value' => function (InsuranceProductItem $model) {
                        return Html::a($model->insuranceProduct->title, ['/insurance-product/view', 'id' => $model->insuranceProduct->id], ['data-pjax' => 0]);
                    },
                ],
                'title',
                'type',
//                'description:ntext',
                //'parent_id',
                //'image',
                //'icon',
                //'weight',
                //'status',
                'created_at:datetime',
                //'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>


</div>
