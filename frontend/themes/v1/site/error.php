<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use kartik\typeahead\Typeahead;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>
<div class="page-nf">

    <h3 class="my-2">
        <?= nl2br(Html::encode($message)) ?>
    </h3>

    <div class="text-center">
        <a href="<?= Url::home()?>" class="btn btn-safe"><?=Yii::t('frontend','На главную')?></a>
    </div>
</div>
