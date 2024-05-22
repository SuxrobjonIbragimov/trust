<?php

use backend\models\menu\MenuItems;
use backend\models\menu\Menus;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $item MenuItems */
$menu_front_header = Menus::findOne(['key' => 'front_header', 'status' => Menus::STATUS_ACTIVE]);
?>

<?php if ((($menuItems = $menu_front_header->getMenuItemsActive()->all()) !== null) ) { ?>
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php foreach ($menuItems as $key_item => $item) : ?>
            <?php if (!empty($item->menuItemsActive)): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-primary-hover d-flex align-items-center text-dark fs-6" href="javascript:void(0)" id="navbarDropdown-<?= $key_item ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $item->label ?>
                    </a>
                    <ul class="dropdown-menu dropdown-transition" aria-labelledby="navbarDropdown-<?= $key_item ?>">
                        <?php foreach ($item->menuItemsActive as $item2) : ?>
                            <li>
                                <?= Html::a($item2->label, [$item2->url], ['class' => 'dropdown-item']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <?= Html::a($item->label, [$item->url], ['class' => 'nav-link text-dark text-primary-hover fs-6']) ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php } ?>