<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\models\parts\HtmlParts */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="html-parts-view box box-info">
    <div class="box-body">

        <p class="btn-group">
            <?php
            if (Helper::checkRoute('update'))
                echo Html::a(Yii::t('views', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);

            if (Helper::checkRoute('delete'))
                echo Html::a(Yii::t('views', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('views', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]); ?>
        </p>

        <div class="row">
            <div class="col-md-8">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'key',
                        'name',
                        'body:ntext',
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
