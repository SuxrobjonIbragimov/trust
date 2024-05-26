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
    <div class="nav-item-top dropdown ms-3 me-2">
        <a class="nav-link-sm d-flex align-items-center text-gray fw-bold column-gap-2 dropdown-toggle dropdown-arrow dropdown-toggle-not pe-3"
           href="#" id="navbarDropdownLang" role="button" data-bs-toggle="dropdown"
           aria-expanded="false">
            <?= $lang_name  ?>
        </a>
        <!-- Language -->
        <ul class="dropdown-menu dropdown-transition" aria-labelledby="navbarDropdownLang" style="min-width: auto">
            <?php foreach ($languages as $language => $object):?>
                <?php $language_key = $object['language_id'];?>
                <?php
                $lang_name = ($native) ? $object['native'] : $object['name'];
                $lang_name = ($short) ? mb_strtoupper($object['language']) : ($lang_name) ;
                $link = Html::a($lang_name, Url::current(['language-picker-language' => $language_key]), ['class' => $language . ' ' . $container_class]);
                ?>
                    <li>
                        <a class="<?= $language ?> dropdown-item dropdown-item-language" href="<?= Url::current(['language-picker-language' => $language_key]) ?>"><?= $lang_name ?></a>
                    </li>
            <?php endforeach;?>
        </ul>
        <!-- End Language -->
    </div>
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
