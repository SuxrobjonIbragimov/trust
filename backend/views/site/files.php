<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 4/18/17
 * Time: 2:03 PM
 */

/* @var $this yii\web\View */

use mihaildev\elfinder\ElFinder;

$this->title = Yii::t('views', 'Site Files');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-warning">
    <div class="box-body">
        <?= ElFinder::widget([
            'containerOptions' => [
                'style' => 'height: 750px'
            ]
        ]) ?>
    </div>
</div>