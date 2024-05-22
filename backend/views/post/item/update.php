<?php

/* @var $this yii\web\View */
/* @var $model backend\models\post\Posts */
/* @var $modelCategory backend\models\post\PostCategories */

use backend\modules\admin\components\Helper;

$this->title = Yii::t('views', 'Update: ') . $model->title;
if (Helper::checkRoute('index')) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Post Categories'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = ['label' => $model->category->name, 'url' => ['view', 'id' => $model->category_id]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view-item', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('views', 'Update');
?>
<div class="posts-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
        'modelCategory' => $modelCategory,
    ]) ?>

</div>
