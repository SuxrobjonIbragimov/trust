<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItems */

$this->title = $model->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->menu->name, 'url' => ['view', 'id' => $model->menu_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-items-view box box-info">
    <div class="box-body">

        <p class="btn-group">
            <?php
            if (Helper::checkRoute('update-item'))
                echo Html::a(Yii::t('views', 'Update'), ['update-item', 'id' => $model->id], ['class' => 'btn btn-primary']);

            if (Helper::checkRoute('delete-item'))
                echo Html::a(Yii::t('views', 'Delete'), ['delete-item', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('views', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]); ?>
        </p>

        <div class="row">
            <div class="col-lg-4 col-md-8">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        [
                            'attribute' => 'parent_id',
                            'format' => 'raw',
                            'value' => $model->parent_id ? Html::a($model->parent->label, ['view-item', 'id' => $model->parent_id]) : $model->parent_id,
                        ],
                        'label',
                        'url',
                        'class',
                        [
                            'attribute' => 'icon',
                            'format' => 'raw',
                            'value' => $model->icon ? Html::img($model->icon, ['alt' => $model->label, 'width' => '200']) : '',
                        ],
                        'description:ntext',
                        'weight',
                        'created_at:datetime',
                        'updated_at:datetime',
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => $model->getStatusName(),
                        ],
                    ],
                ]) ?>
            </div>
        </div>

    </div>
</div>
