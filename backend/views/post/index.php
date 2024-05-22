<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use backend\models\post\PostCategories;
use backend\modules\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\post\PostCategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('views', 'Post Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-categories-index box box-default">
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
                        [
                            'attribute' => 'name',
                            'format' => 'raw',
                            'value' => function (PostCategories $data) {
                                return Html::a($data->name, ['view', 'id' => $data->id], ['data-pjax' => 0]);
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'filter' => PostCategories::getStatusArray(),
                            'value' => function (PostCategories $data) {
                                return $data->getStatusName();
                            },
                            'visible' => (Yii::$app->user->can('administrator')),
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
                            'visible' => (Yii::$app->user->can('administrator')),
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
