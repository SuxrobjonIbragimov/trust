<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $languages yii\web\View */
/* @var $short yii\web\View */
/* @var $native yii\web\View */
/* @var $flag yii\web\View */
/* @var $is_front yii\web\View */
/* @var $is_mobile yii\web\View */
/* @var $container_tag yii\web\View */
/* @var $container_class yii\web\View */

?>

<?php if (!$is_front && !$is_mobile):?>
    <?php foreach ($languages  as $language => $object):?>
        <?php $language_key = $object['language_id'];?>
        <<?= $container_tag; ?> class="<?= $language ?>  <?= $container_class; ?> <?= (Yii::$app->language == $language)? 'active' : ''; ?>">
        <?php
        $lang_name = ($native) ? $object['native'] : $object['name'];
        $lang_name = ($short) ? $language : $lang_name ;
        ?>
        <?php if ($flag):?>
            <?php $img = Html::img('@web/themes/v1/images/svg/group-top/flag-'.$language.'.svg', ['alt' => '', 'class' => 'nav-icon-flag--svg', 'width' => 30]);?>
            <?= Html::a($img.' '.$lang_name, Url::current(['language-picker-language' => $language_key])); ?>
        <?php else:?>
            <?= Html::a($lang_name,Url::current(['language-picker-language' => $language_key])); ?>
        <?php endif;?>
        </<?= $container_tag; ?>>
    <?php endforeach;?>
<?php elseif($is_front && !$is_mobile): ?>
    <?php $cur_lang = _lang();?>
    <?php $cur_lang_object = $languages[$cur_lang];?>

    <?php
    $lang_name = ($native) ? $cur_lang_object['native'] : $cur_lang_object['name'];
    $lang_name = ($short) ? mb_strtoupper($cur_lang) : ($lang_name) ;
    ?>

    <a class="nav-link-sm d-flex align-items-center dropdown-toggle dropdown-arrow dropdown-toggle-not pe-3 text-gray fs-7 text-decoration-none" href="#"
       id="navbarDropdownLang" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php if ($flag):?>
            <?php $img = Html::img('@web/themes/v1/images/svg/group-top/flag-'.$cur_lang.'.svg', ['alt' => '', 'class' => 'nav-icon-flag--svg',]);?>
            <div class="nav-icon-flag">
                <?= $img; ?>
            </div>
        <?php endif;?>
        <?= $lang_name; ?>
        <!--<div class="nav-icon-arrow-down">
            <svg class="nav-icon-arrow-down--svg" width="7" height="4" viewbox="0 0 7 4" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M7 0.461212C7 0.588735 6.95598 0.690755 6.86792 0.767269L3.81132 3.8661C3.72327 3.95537 3.62264 4 3.50943 4C3.39623 4 3.28931 3.95537 3.18868 3.8661L0.132076 0.767269C0.0440252 0.678002 4.0907e-08 0.569607 4.24277e-08 0.442083C4.39484e-08 0.314559 0.0440252 0.21254 0.132076 0.136025C0.220126 0.0595112 0.320755 0.0148778 0.433962 0.00212551C0.54717 -0.0106268 0.654088 0.0340064 0.754717 0.136025L3.50943 2.9288L6.26415 0.136026C6.3522 0.0467587 6.45283 0.00212558 6.56604 0.00212558C6.67925 0.00212558 6.77987 0.0467587 6.86792 0.136026C6.95598 0.225292 7 0.333688 7 0.461212Z" />
            </svg>
        </div>-->
    </a>
    <ul class="dropdown-menu dropdown-transition text-gray fs-7" aria-labelledby="navbarDropdownLang" style="min-width: auto">

        <?php foreach ($languages as $language => $object):?>
            <?php $language_key = $object['language_id'];?>
            <?php
            $lang_name = ($native) ? $object['native'] : $object['name'];
            $lang_name = ($short) ? mb_strtoupper($language) : $lang_name ;
            ?>
            <li>
                <?php if ($flag):?>
                    <?php $img = Html::img('@web/themes/v1/images/svg/group-top/flag-'.$language.'.svg', ['alt' => '', 'class' => 'nav-icon-flag--svg',]);?>
                    <?= Html::a($img.' '.$lang_name, Url::current(['language-picker-language' => $language_key]), ['class' => $language .'  '.$container_class]); ?>
                <?php else:?>
                    <?= Html::a($lang_name,Url::current(['language-picker-language' => $language_key]), ['class' => $language .' '.$container_class]); ?>
                <?php endif;?>
            </li>
        <?php endforeach;?>
    </ul>

<?php elseif($is_mobile): ?>
    <?php foreach ($languages as $language => $object):?>
        <?php $language_key = $object['language_id'];?>
        <?php
        $lang_name = ($short) ? $language : $object['name'];
        ?>
        <?= Html::a($lang_name,Url::current(['language-picker-language' => $language_key]),['class' => (_lang() == $language) ? 'active' : '']); ?>
    <?php endforeach;?>
<?php else:?>
    <div class="active-language">
        <span><?= ($short) ? _lang() : $languages[_lang()]['name'] ?></span>
    </div>
    <?php unset($languages[_lang()])?>
    <div class="popup-language">
        <?php foreach ($languages as $language => $object):?>
            <?php $language_key = $object['language_id'];?>
            <?php
            $lang_name = ($native) ? $object['native'] : $object['name'];
            $lang_name = ($short) ? $language : $object['name'] ;
            ?>
            <?= Html::a($lang_name,Url::current(['language-picker-language' => $language_key])); ?>
        <?php endforeach;?>
    </div>
<?php endif;?>
