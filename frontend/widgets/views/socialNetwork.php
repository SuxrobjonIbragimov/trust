<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $socialNetworks yii\web\View */
/* @var $is_mobile yii\web\View */
/* @var $has_image yii\web\View */
/* @var $tag_class yii\web\View */
/* @var $item_class yii\web\View */
/* @var $item_class_has yii\web\View */

?>

<?php if ($has_image):?>
    <?php foreach ($socialNetworks as $id => $object):?>
        <div class="w-auto me-lg-4 me-md-3 me-sm-2 me-2">
            <?php
            $_name = Html::img($object['image'], ['alt' => $object['name'], 'class' => 'social-icons--svg']);
            ?>
            <?= Html::a($_name,Url::to($object['url']),['target' => '_blank', 'class' => 'social-icons--href', 'rel' => 'nofollow']); ?>
        </div>
    <?php endforeach;?>
<?php else:?>
    <div class="<?= $tag_class ?>">
        <?php foreach ($socialNetworks as $id => $object):?>
            <?php
            $_name = Html::tag('i', '', ['class' => [$object['fa_icon']]]);
            ?>
            <?= Html::a($_name,Url::to($object['url']),['target' => '_blank', 'class' => $object['html_item_class']]); ?>
        <?php endforeach;?>
    </div>
<?php endif;?>
