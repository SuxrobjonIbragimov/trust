<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\telegram\models\BotUser */

$this->title = Yii::t('telegram', 'Create Bot User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('telegram', 'Bot Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-user-create">

    <div class="box box-default">
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
