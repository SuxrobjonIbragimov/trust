<?php

use backend\modules\admin\components\Helper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\insurance\InsuranceProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('model', 'Insurance Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-product-index   box box-default">
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
                'title',
                'subtitle',
    //            'slug',
    //            'summary:ntext',
                //'description:ntext',
                //'parent_id',
                //'image',
                //'icon',
                //'meta_title',
                //'meta_keywords',
                //'meta_description:ntext',
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
                    'class' => 'yii\grid\ActionColumn',
                    'template' => Helper::filterActionColumn(['view', 'update', 'delete'])
                ],
            ],
        ]); ?>
    </div>

</div>
