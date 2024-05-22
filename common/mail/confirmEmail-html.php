<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$link = Yii::$app->urlManager->createAbsoluteUrl(['customer/confirm-email', 'id' => $user->id, 'key' => $user->auth_key]);
?>
<div class="activate-user">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to confirm your email:</p>

    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
