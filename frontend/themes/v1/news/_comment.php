<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \backend\models\comment\Comments */

?>

<div class="c-img">
    <?= Html::img($model->author->image, ['alt' => 'avatar', 'class' => 'avatar-image'])?>
</div>
<div class="c-text">
    <p class="c-user">
        <a href="javascript:void(0)"><?= $model->author->first_name . '&nbsp;' . $model->author->last_name ?></a>
        <span class="c-time"><?= Yii::$app->formatter->asDate($model->created_at, 'full') ?></span>
    </p>
    <p><?= $model->text ?></p>
    <p class="b-border p-buttom30">
        <a href="<?= \yii\helpers\Url::current(['pid' => $model->id])?>"><?=Yii::t('news','Ответить')?></a>
    </p>
    <?php if (!empty($model->activeChilds)):?>
        <?php foreach ($model->activeChilds as $comment):?>
            <div class="comment p-top30">
                <div class="c-img">
                    <?= Html::img($model->author->image, ['alt' => 'avatar', 'class' => 'avatar-image'])?>
                </div>
                <div class="c-text">
                    <p class="c-user">
                        <a href="javascript:void(0)"><?= $model->author->first_name . '&nbsp;' . $model->author->last_name ?></a>
                        <span class="c-time"><?= Yii::$app->formatter->asDate($model->created_at, 'full') ?></span>
                    </p>
                    <p><?= $model->text ?></p>
                    <p class="b-border p-buttom30">
                        <a href="<?= \yii\helpers\Url::current(['pid' => $model->id])?>"><?=Yii::t('news','Ответить')?></a>
                    </p>
                </div>
            </div>
        <?php endforeach;?>
    <?php endif;?>
</div>