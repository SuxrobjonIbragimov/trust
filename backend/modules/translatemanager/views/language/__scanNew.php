<?php

/* @var $this \yii\web\View */
/* @var $newDataProvider \yii\data\ArrayDataProvider */

use yii\grid\GridView;

?>

<?php if ($newDataProvider->totalCount > 0) : ?>

    <?=

    GridView::widget([
        'id' => 'added-source',
        'dataProvider' => $newDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'category',
            'message',
        ],
    ]);

    ?>

<?php endif ?>
