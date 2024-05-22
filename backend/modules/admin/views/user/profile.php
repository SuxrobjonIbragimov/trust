<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view box box-info">
    <div class="box-body">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-10">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'username',
                        'email:email',
                        'first_name',
                        'last_name',
                        'created_at:date',
                        'info:ntext',
                        'phone',
                        [
                            'attribute' => 'image',
                            'format' => 'raw',
                            'value' => $model->image ? Html::img($model->image, ['alt' => 'picture', 'width' => 100]) : $model->image,
                        ],
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>