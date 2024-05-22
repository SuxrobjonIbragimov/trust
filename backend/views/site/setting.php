<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 18/08/17
 * Time: 13:31
 */

/* @var $this yii\web\View */
/* @var $model \common\models\Settings */
/* @var $models \common\models\Settings */
/* @var $setting \common\models\Settings */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use rmrevin\yii\fontawesome\FA;
use backend\modules\admin\components\Helper;

$this->title = Yii::t('views', 'Site Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-default">
    <div class="box-header">
        <?php
        if (Helper::checkRoute('setting-create')) {
            Modal::begin([
                'header' => '<h3>' . Yii::t('views', 'Create setting') . '</h3>',
                'toggleButton' => [
                    'label' => Yii::t('views', 'Create setting'),
                    'class' => 'btn btn-success'
                ],
            ]);

            $form = ActiveForm::begin(['action' => ['setting-create'], 'method' => 'POST']);

            echo $form->field($model, 'key')->textInput(['maxlength' => true])
                . $form->field($model, 'label')->textInput(['maxlength' => true])
                . $form->field($model, 'type')->dropDownList($model->getTypeList())
                . $form->field($model, 'required')->checkbox()
                . Html::submitButton(Yii::t('views', 'Create'), ['class' => 'btn btn-success']);

            ActiveForm::end();
            Modal::end();
        } ?>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-lg-6 col-md-10">
                <?php
                if (!empty($models)) {
                    echo Html::beginForm();
                    foreach ($models as $setting) { ?>
                        <div class="form-group<?= $setting->required ? ' required' : '' ?>">
                            <label class="control-label" for="<?= $setting->key ?>"><?= $setting->label ?>
                                (<i><?= $setting->key ?></i>)
                            </label>
                            <div class="input-group">
                                <?php $setting->getTypeField() ?>
                                <div class="input-group-btn">
                                    <?= Html::a('<i class="fa fa-trash"></i>', ['setting-delete', 'id' => $setting->id], [
                                        'class' => 'btn btn-danger',
                                        'data' => [
                                            'confirm' => Yii::t('views', 'Are you sure you want to delete this item?'),
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    <?php }
                    echo Html::submitButton(Yii::t('views', 'Save'), ['class' => 'btn btn-warning'])
                        . Html::endForm();
                } ?>
            </div>
        </div>
    </div>
</div>
