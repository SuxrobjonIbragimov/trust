<?php

/* @var $this yii\web\View */
/* @var $model backend\models\post\PostCategories */

use backend\modules\admin\components\Helper;

$this->title = Yii::t('views', 'Create Post Category');
if (Helper::checkRoute('index')) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('views', 'Post Categories'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-categories-create box box-success">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
