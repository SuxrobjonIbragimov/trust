<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use backend\models\sliders\Sliders;
use backend\modules\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\sliders\SlidersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('views', 'Sliders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sliders-index box box-default">
    <div class="box-body">

        <p>
            <?php if (Helper::checkRoute('create'))
                echo Html::a(Yii::t('views', 'Create'), ['create'], ['class' => 'btn btn-success']); ?>
        </p>

        <div class="row">
            <div class="col-lg-8 col-md-10">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'id',
                            'options' => ['width' => '70'],
                        ],
                        'key',
                        [
                            'attribute' => 'name',
                            'format' => 'raw',
                            'value' => function (Sliders $data) {
                                return Html::a($data->name, ['view', 'id' => $data->id], ['data-pjax' => 0]);
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'filter' => Sliders::getStatusArray(),
                            'value' => function (Sliders $data) {
                                return $data->getStatusName();
                            },
                            'visible' => (Yii::$app->user->can('administrator')),
                        ],
                        [
                            'attribute' => 'Items',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function (Sliders $data) {
                                return count($data->sliderItems);
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
                            'template' => Helper::filterActionColumn(['view', 'update', 'delete'])
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
