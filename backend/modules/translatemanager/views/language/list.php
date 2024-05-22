<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use backend\modules\admin\components\Helper;
use backend\modules\translatemanager\models\Language;

/* @var $this \yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\modules\translatemanager\models\searches\LanguageSearch */

$this->title = Yii::t('language', 'Languages');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box box-default">
    <div class="box-body">
        <p class="btn-group">
            <?php
            if (Helper::checkRoute('create'))
                echo Html::a(Yii::t('language', 'Create'), ['create'], ['class' => 'btn btn-success']);

            if (Helper::checkRoute('import'))
                echo Html::a(Yii::t('language', 'Import'), ['import'], ['class' => 'btn btn-warning']);

            if (Helper::checkRoute('export'))
                echo Html::a(Yii::t('language', 'Export'), ['export'], ['class' => 'btn btn-danger']); ?>
        </p>
        <div id="languages">

            <?php
            Pjax::begin(['id' => 'languages']);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'language_id',
                    'name_ascii',
                    [
                        'format' => 'raw',
                        'filter' => Language::getStatusNames(),
                        'attribute' => 'status',
                        'filterInputOptions' => ['class' => 'form-control', 'id' => 'status'],
                        'content' => function ($language) {
                            return Html::activeDropDownList($language, 'status', Language::getStatusNames(), [
                                'class' => 'status form-control',
                                'id' => $language->language_id,
                                'data-url' => Url::to(['change-status'])
                            ]);
                        },
                    ],
                    [
                        'format' => 'raw',
                        'attribute' => Yii::t('language', 'Statistic'),
                        'content' => function ($language) {
                            return '<span class="statistic"><span style="width:' . $language->gridStatistic
                                . '%"></span><i>' . $language->gridStatistic . '%</i></span>';
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => Helper::filterActionColumn(['translate', 'view', 'update', 'delete']),
                        'buttons' => [
                            'translate' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-list-alt"></span>'
                                    , ['language/translate', 'language_id' => $model->language_id],
                                    ['title' => Yii::t('language', 'Translate'), 'data-pjax' => '0',]);
                            },
                        ],
                    ],
                ],
            ]);
            Pjax::end(); ?>
        </div>
    </div>
</div>