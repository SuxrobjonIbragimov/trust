<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\telegram\models\BotUser */
/* @var $modelMessage backend\modules\telegram\models\BotUserMessage */

$this->title = Yii::t('telegram', 'Create message');
$this->params['breadcrumbs'][] = ['label' => Yii::t('telegram', 'Bot Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-user-create">

    <div class="box box-default">
        <div class="box-body">
            <?= $this->render('_form_message', [
                'model' => $model,
                'modelMessage' => $modelMessage,
            ]) ?>
        </div>
    </div>

</div>
