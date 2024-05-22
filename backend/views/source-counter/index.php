<?php

use backend\models\page\SourceCounter;
use backend\modules\policy\models\PolicyOsgo;
use backend\modules\policy\models\PolicyTravel;
use yii\db\Expression;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\page\SourceCounterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('model', 'Source Counters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-counter-index">

    <p>
        <?= Html::a(Yii::t('model', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                'lang',
                [
                    'attribute' => 'code',
                    'format' => 'raw',
                    'value' => function (SourceCounter $data) {
                        $full_url = Yii::$app->request->hostInfo.'/a/'.$data->code;
                        return $full_url;
                    },
                ],
                [
                    'attribute' => 'redirect_url',
                    'format' => 'raw',
                    'value' => function (SourceCounter $data) {
                        $full_url = Yii::$app->request->hostInfo.$data->redirect_url;
                        return Html::a($full_url, $full_url, ['data-pjax' => 0, 'target' => '_blank']);
                    },
                ],
                'count',
                [
                    'label' => 'Count buyer',
                    'format' => 'raw',
                    'value' => function (SourceCounter $data) {
                        $null = new Expression('NULL');
                        $all_count = PolicyOsgo::find()->where(['source' => $data->id])->count('id');
                        $all_count_tr = PolicyTravel::find()->where(['source' => $data->id])->count('id');
                        $all_count_buy = PolicyOsgo::find()
                            ->where(['source' => $data->id])
                            ->andWhere(['!=', 'policy_series', $null])
                            ->count('id');
                        $all_count_buy_tr = PolicyTravel::find()
                            ->where(['source' => $data->id])
                            ->andWhere(['!=', 'policy_series', $null])
                            ->count('id');
                        $message = '';
                        if ($all_count) {
                            $message .= "All osgo visits - <b>{$all_count}</b><br>Buyed - <b>{$all_count_buy}</b><br>";
                        }
                        if ($all_count_tr) {
                            $message .= "All travel visits - <b>{$all_count_tr}</b><br>Buyed - <b>{$all_count_buy_tr}</b>";
                        }
                        return $message;
                    },
                ],
                //'weight',
                //'status',
                'created_at:datetime',
                'updated_at:datetime',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>

</div>
