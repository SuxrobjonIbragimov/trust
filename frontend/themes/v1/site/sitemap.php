<?php

/* @var $this yii\web\View */
/* @var $menuItems \backend\models\menu\MenuItems */

use yii\helpers\Html;

$this->title = Yii::t('frontend','Карта сайта');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-map">
    <?php if (!empty($menuItems)):?>
        <ul class="row">
            <?php foreach ($menuItems as $item) : ?>
                <li class="col-md-4  my-3">
                    <?= Html::a($item->label, [$item->url], ['class' => 'nav-link-sitemap fw-bold']) ?>
                    <?php if (!empty($item->menuItemsActive)): ?>
                        <ul class="inner-list ms-5">
                            <?php foreach ($item->menuItemsActive as $item2) : ?>
                                <li class="nav-item-custom my-2">
                                    <?= Html::a($item2->label, [$item2->url], ['class' => 'nav-link-sitemap']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif;?>
</div>
