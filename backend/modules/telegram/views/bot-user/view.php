<?php

use backend\models\page\SourceCounter;
use backend\modules\admin\components\Helper;
use backend\modules\telegram\models\BotUser;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\telegram\models\BotUser */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('telegram', 'Bot Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bot-user-view">

    <p class="btn-group">
        <?php
        if (Helper::checkRoute('update'))
            echo Html::a(Yii::t('views', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary', 'data-pjax' => 0]);

        if (Helper::checkRoute('delete'))
            echo Html::a(Yii::t('views', 'Delete'), ['delete', 'id' => $model->id], [
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
            't_id',
            'is_bot:boolean',
            'first_name',
            'last_name',
            't_username',
            [
                'attribute' => 'phone',
                'format' => 'raw',
                'value' => function (BotUser $data) {
                    $full_str = '+'.$data->phone;
                    return mask_to_phone_format($full_str);
                },
            ],
            'language_code',
            [
                'attribute' => 'callback_data',
                'format' => 'ntext',
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            [
                'attribute' => 'current_product',
                'format' => 'ntext',
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            [
                'attribute' => 'current_step_type',
                'format' => 'ntext',
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            [
                'attribute' => 'current_step_val',
                'format' => 'ntext',
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            [
                'attribute' => 'message_id_l',
                'format' => 'raw',
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            [
                'attribute' => 'message_id_d',
                'format' => 'raw',
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            [
                'attribute' => 'message_id_e',
                'format' => 'raw',
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            'is_premium:boolean',
            [
                'attribute' => 'source',
                'format' => 'raw',
                'value' => function (BotUser $data) {
                    $modelSource = SourceCounter::findOne(['id' => intval($data->source)]);
                    $full_str = !empty($modelSource->name) ? $modelSource->name. " ({$modelSource->id})" : $data->source;
                    return !empty($modelSource->t_id) ? Html::a($full_str,['/source-counter/view', 'id' => $modelSource->id]) : null;
                },
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            [
                'attribute' => 'info',
                'format' => 'raw',
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            [
                'attribute' => 'is_admin',
                'format' => 'boolean',
                'visible' => (Yii::$app->user->can('administrator')),
            ],
            'status',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
