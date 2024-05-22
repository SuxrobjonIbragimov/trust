<?php

use backend\models\page\SourceCounter;
use backend\modules\admin\components\Helper;
use backend\modules\telegram\models\BotUser;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\telegram\models\BotUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('telegram', 'Bot Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php
        if (Helper::checkRoute('create'))
            echo Html::a(Yii::t('views', 'Create'), ['create'], ['class' => 'btn btn-success', 'data-pjax' => 0]);

        if (Helper::checkRoute('message') && Yii::$app->user->can('accessAdministrator'))
            echo Html::a(Yii::t('views', 'Send Message All'), ['message'], ['class' => 'btn btn-warning', 'data-pjax' => 0]);
        ?>
    </p>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                't_id',
//                'is_bot:boolean',
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
                [
                    'attribute' => 'language_code',
                    'format' => 'raw',
                    'visible' => (Yii::$app->user->can('administrator')),
                ],
                //'callback_data',
                //'current_product',
                [
                    'attribute' => 'current_step_type',
                    'format' => 'raw',
                    'visible' => (Yii::$app->user->can('administrator')),
                ],
                //'current_step_val:ntext',
                //'message_id_l',
                //'message_id_d',
                //'message_id_e',
                //'is_premium',
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
                //'info',
                //'is_admin:boolean',
                //'status',
                'created_at:datetime',
                //'updated_at',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => Helper::filterActionColumn(['view', 'message', 'update', 'delete',]),
                    'buttons' => [
                        'message' => function($url, $model) {
                            $options = [
                                'title' => Yii::t('Telegram', 'Send Message'),
                                'aria-label' => Yii::t('Telegram', 'Send Message'),
                                'data-pjax' => '0',
                            ];
                            return Html::a('<i class="fab fa-telegram"></i>', $url, $options);
                        }
                    ]
                ],
            ],
        ]); ?>

    </div>

</div>
