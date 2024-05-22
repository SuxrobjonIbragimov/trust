<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\telegram\models\BotUser */

$this->title = Yii::t('telegram', 'Update Bot User: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('telegram', 'Bot Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('telegram', 'Update');
?>
<div class="bot-user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
