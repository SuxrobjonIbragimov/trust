<?php

/* @var $this yii\web\View */
/* @var $model backend\models\post\PostCategories */

use backend\modules\admin\components\Helper;

$this->title = Yii::t('views', 'Update Post Category: ') . $model->name;
if (Helper::checkRoute('index')) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Post Categories'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('views', 'Update');
?>
<div class="post-categories-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
