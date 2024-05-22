<?php

use backend\models\menu\MenuItems;
use backend\models\menu\Menus;
use common\models\Settings;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $item MenuItems */
$siteName = Settings::getValueByKey(Settings::KEY_SITE_NAME);
$menu_front_header = Menus::findOne(['key' => 'front_header', 'status' => Menus::STATUS_ACTIVE]);
?>
<?php if ((($menuItems = $menu_front_header->getMenuItemsActive()->all()) !== null) ): ?>
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><?= $siteName ?? Yii::t('header','Site menu'); ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="container-lg container-fluid">
            <div class="row row-gap-3">
                <?php foreach ($menuItems as $key_item => $item) : ?>
                    <?php if (!empty($item->menuItemsActive)): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                            <div class="d-flex flex-column row-gap-2">
                                <h5 class="fs-6 text-primary fw-bolder"><?= $item->label ?></h5>
                                <?php foreach ($item->menuItemsActive as $item2) : ?>
                                    <?= Html::a($item2->label, [$item2->url], ['class' => 'w-max-content fs-6 text-secondary']) ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                            <?= Html::a($item->label, [$item->url], ['class' => 'fs-6 text-primary fw-bolder']) ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>