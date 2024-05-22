<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use backend\models\menu\Menus;
use backend\widgets\ActionsApply;
use backend\modules\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\menu\MenusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('views', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menus-index box box-default">
    <div class="box-body">

        <p>
            <?php if (Helper::checkRoute('create'))
                echo Html::a(Yii::t('views', 'Create'), ['create'], ['class' => 'btn btn-success']); ?>
        </p>
        <div class="row">
            <div class="col-lg-10">
                <?php ActionsApply::begin(); ?>
                <div class="table-responsive">
                    <?php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            ['class' => 'yii\grid\CheckboxColumn'],

                            [
                                'attribute' => 'id',
                                'options' => ['width' => '70'],
                            ],
                            [
                                'attribute' => 'name',
                                'format' => 'raw',
                                'value' => function (Menus $data) {
                                    return Html::a($data->name, ['view', 'id' => $data->id], ['data-pjax' => 0]);
                                },
                            ],
                            'key',
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'filter' => Menus::getStatusArray(),
                                'value' => function (Menus $data) {
                                    return $data->getStatusName();
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
                <?php ActionsApply::end(); ?>
            </div>
        </div>
    </div>
</div>