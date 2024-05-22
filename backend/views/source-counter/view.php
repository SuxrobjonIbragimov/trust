<?php

use backend\models\page\SourceCounter;
use backend\modules\policy\models\PolicyOsgo;
use yii\db\Expression;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\page\SourceCounter */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('model', 'Source Counters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="source-counter-view">

    <p>
        <?= Html::a(Yii::t('model', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('model', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('model', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
                    $all_count_buy = PolicyOsgo::find()
                        ->where(['source' => $data->id])
                        ->andWhere(['!=', 'policy_series', $null])
                        ->count('id');
                    return "All - <b>{$all_count}</b><br>Buyed - <b>{$all_count_buy}</b>";
                },
            ],
            'weight',
            'status',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
