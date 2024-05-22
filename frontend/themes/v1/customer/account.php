<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \frontend\models\Account */

$this->title = Yii::t('frontend', 'Учетная запись');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="deals">
    <h3 class="w3ls-title"><?= $this->title ?></h3>

    <div class="row">
        <div class="col-lg-6 col-md-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'firstName',
                    'lastName',
                    'email',
                    'phone',
                    'district',
                    'address',
                    [
                        'attribute' => 'image',
                        'format' => 'raw',
                        'value' => Html::img($model->image, ['alt' => 'avatar', 'width' => '150'])
                    ],
                ]
            ]); ?>
        </div>
    </div>
</div>