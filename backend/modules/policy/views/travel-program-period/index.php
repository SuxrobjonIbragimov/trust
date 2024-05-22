<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\policy\models\search\PolicyTravelProgramPeriodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('views', 'Policy Travel Program Periods');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-program-period-index">

    <p>
        <?= Html::a(Yii::t('views', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'policy_travel_program_id',
            'day_min',
            'day_max',
            'value',
            'is_fixed:boolean',
            //'weight',
            //'status',
            'created_at:datetime',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
