<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$link = Yii::$app->urlManager->createAbsoluteUrl(['customer/confirm-email', 'id' => $user->id, 'key' => $user->auth_key]);
?>
Hello <?= $user->username ?>,

Follow the link below to confirm your email:

<?= $link ?>
