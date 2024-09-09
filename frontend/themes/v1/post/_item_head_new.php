<?php

use common\models\Settings;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $models \backend\models\post\Posts */
/* @var $model \backend\models\post\Posts */

$col_default = 'col-lg-6 col-sm-6';
$col_class_list = [
    'col-lg-4 col-sm-4',
    'col-lg-4 col-sm-4',
    'col-lg-4 col-sm-4',
    'col-lg-4 col-sm-4',
    'col-lg-4 col-sm-4',
];
?>

<?php if (!empty($models)):?>
    <div class="row justify-content-center align-content-center align-items-center">
        <?php foreach ($models as $key => $model):?>
            <?php

            $work_phone = !empty($model->work_phone) ? $model->work_phone : Settings::getValueByKey(Settings::KEY_MAIN_PHONE);
            $work_email = !empty($model->work_email) ? $model->work_email : Settings::getValueByKey(Settings::KEY_MAIN_EMAIL);
            $work_telegram = !empty($model->work_telegram) ? $model->work_telegram : Settings::getValueByKey(Settings::KEY_MAIN_TELEGRAM);

            ?>
            <div class="<?php echo !empty($col_class_list[$key]) ? $col_class_list[$key] : $col_default; ?>">
                <div class="staff justify-content-center">
                    <div class="staff__image staff__wid">
                        <img src="<?= $model->image; ?>" alt="<?= $model->title ?>" class="w-100 img-responsive">
                    </div>
                    <div class="staff__name text-bold"><?= $model->title ?></div>
                    <div class="staff__position"><?= $model->work_position; ?></div>
                    <div class="staff__position"><small><?= $model->work_days; ?></small></div>
                </div>
            </div>
            <?php if ($key == 0):?>
            <div class="clreafix"></div>
            <?php endif;?>
        <?php endforeach;?>
    </div>
<?php endif;?>