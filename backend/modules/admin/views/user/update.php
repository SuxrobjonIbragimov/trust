<?php

use backend\modules\admin\models\form\User as UserForm;

/* @var $this yii\web\View */
/* @var $model UserForm */

$this->title = Yii::t('rbac-admin', 'Update') . ' : ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
