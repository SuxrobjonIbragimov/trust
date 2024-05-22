<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\policy\models\search\PolicyTravelTravellerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('views', 'Policy Travel Travellers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="policy-travel-traveller-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('views', 'Create Policy Travel Traveller'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'policy_travel_id',
            'first_name',
            'surname',
            'birthday',
            //'pass_sery',
            //'pass_num',
            //'pinfl',
            //'phone',
            //'email:email',
            //'address',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
