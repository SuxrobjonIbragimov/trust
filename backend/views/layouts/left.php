<?php

/* @var $this \yii\web\View */

use backend\models\menu\Menus;
use backend\modules\admin\components\Helper;
use backend\extensions\adminlte\widgets\Menu;

?>

<aside class="main-sidebar">
    <section class="sidebar">

        <?= empty($menuItems = Menus::getAdminMenu()) ?
            Yii::t('views', '<pre class="bg-warning">Menu not found.<br>key => "{key}"</pre>', ['key' => 'admin_menu']) :
            Menu::widget([
                'options' => ['class' => 'sidebar-menu test'],
                'items' => Helper::filter($menuItems),
            ]); ?>

    </section>
</aside>