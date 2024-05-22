<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\modules\news\models\NewsRibbon;

/* @var $slider \backend\models\sliders\Sliders */
/* @var $item \backend\models\sliders\SliderItems */

$item = $slider->getSliderActiveItems()->one();
?>
<?php if (!empty($item)):?>
    <section class="ad-banner">
        <a href="<?= ($item->link == '#' || empty($item->link)) ? 'javascript:void(0)' : $item->link?>" target="_blank">
            <?= Html::img($item->image, ['alt' => $item->title, 'class' => ''])?>
        </a>
    </section>
<?php endif; ?>