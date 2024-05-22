<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\translatemanager\models\Language */

$this->title = Yii::t('language', 'Create {modelClass}', [
    'modelClass' => 'Language',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('language', 'Languages'), 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-create box box-success">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>