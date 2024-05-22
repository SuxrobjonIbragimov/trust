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

    <select class="nice-select nice-select-default text-gray fs-7">
<!--    <ul class="text-gray fs-7">-->
        <?php foreach ($languages as $language => $object):?>
            <?php $language_key = $object['language_id'];?>
            <?php
            $lang_name = ($native) ? $object['native'] : $object['name'];
            $lang_name = ($short) ? mb_strtoupper($language) : $lang_name ;
            $link = Html::a($lang_name, Url::current(['language-picker-language' => $language_key]), ['class' => $language . ' ' . $container_class]);
            ?>
            
            <option value="<?= $language_key ?>" <?= ($language == $cur_lang) ? 'selected' : '' ?> ><?= $link ?></option>
        <?php endforeach;?>
    </select>
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
