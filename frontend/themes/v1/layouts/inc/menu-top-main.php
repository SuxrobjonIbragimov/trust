<?php

use backend\models\menu\MenuItems;
use backend\models\menu\Menus;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $item MenuItems */
$menu_front_header = Menus::findOne(['key' => 'front_header', 'status' => Menus::STATUS_ACTIVE]);
?>

<?php if ((($menuItems = $menu_front_header->getMenuItemsActive()->all()) !== null) ): ?>
    <ul class=" d-flex align-items-center dropdown-toggles pb-lg-2">
        <?php foreach ($menuItems as $key_item => $item) : ?>
            <?php if (!empty($item->menuItemsActive)): ?>
                <li>
                    <a class="fs-6 fw-normal text-secondary px-3 pb-2 dropdown-toggle" href="javascript:void(0)" id="navbarDropdown-<?= $key_item ?>">
                        <?= $item->label ?>
                    </a>
                    <div class="dropdown-menu w-100 bg-white d-block position-absolute p-3" aria-labelledby="navbarDropdown-<?= $key_item ?>">
                        <div class="row w-100">
                            <div class="col-xl-3">
                                <ul class="d-flex align-items-start flex-column row-gap-2">
                                    <?php foreach ($item->menuItemsActive as $item2) : ?>
                                        <?= Html::a($item2->label, [$item2->url], ['class' => 'fs-7 text-secondary']) ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            <?php else: ?>
                <li>
                    <?= Html::a($item->label, [$item->url], ['class' => 'fs-6 fw-normal text-secondary px-3 pb-2 text-nowrap']) ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>