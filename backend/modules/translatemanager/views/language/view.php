<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\modules\translatemanager\models\Language */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('language', 'Languages'), 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-view box box-info">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-6">
                <p class="btn-group">
                    <?php
                    if (Helper::checkRoute('update'))
                        echo Html::a(Yii::t('language', 'Update'), ['update', 'id' => $model->language_id], ['class' => 'btn btn-primary']);

                    if (Helper::checkRoute('delete'))
                        echo Html::a(Yii::t('language', 'Delete'), ['delete', 'id' => $model->language_id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('language', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]); ?>
                </p>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'language_id',
                        'language',
                        'country',
                        'name',
                        'name_ascii',
                        [
                            'label' => Yii::t('language', 'Status'),
                            'value' => $model->getStatusName(),
                        ],
                        [
                            'label' => Yii::t('language', 'Translation status'),
                            'value' => $model->getGridStatistic() . '%',
                        ],
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>