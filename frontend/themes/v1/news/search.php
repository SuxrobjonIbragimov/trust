<?php

use frontend\widgets\SubscribeWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use kartik\typeahead\Typeahead;

/** @var $this yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $model \backend\modules\news\models\News */
/** @var $popularModels \backend\modules\news\models\News */
/** @var $pagination \yii\data\Pagination */
/** @var $text string */

$this->title = Yii::t('frontend', 'Результат поиска');

$this->params['breadcrumbs'][] = ['label' => 'Все новости', 'url' => ['category', 'slug' => 'all']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['container-class'] = 'search-page';
?>

<div class="ln-left">
    <div class="news-search">
        <?= Html::beginForm(['news/search'], 'get', ['class' => 'search-form']) ?>
        <div class="input-group">
            <?= Typeahead::widget([
                'name' => 'text',
                'options' => [
                    'placeholder' => 'Поиск',
                    'required' => true,
                    'type' => 'search',
                    'style' => 'border-radius: 0;',
                ],
                'value' => !empty($text) ? $text : false,
                'scrollable' => true,
                'pluginOptions' => [
                    'highlight' => true,
                    'minLength' => 2,
                ],
                'dataset' => [
                    [
                        'display' => 'value',
                        'limit' => 1000,
                        'remote' => [
                            'url' => Url::to(['news/news-list']) . '?q=%QUERY',
                            'wildcard' => '%QUERY'
                        ]
                    ]
                ],
                'pluginEvents' => [
                    'typeahead:select' => 'function(event, data) {
                            location.href = "/news/view/" + data["key"];
                        }',
                ]
            ]) ?>
            <div class="input-group-btn">
                <?= Html::submitButton(' <i class="fa fa-search"></i>', ['class' => 'btn btn-default form-btn']) ?>
            </div>
        </div>
        <?= Html::endForm() ?>
    </div>
    <div class="latest-news-top menu p-buttom30">
        <div class="title">
            <h1>Результаты поиска для: <?= $text ?> </h1>
        </div>
    </div>
    <div class="qty">
        <p>
            <?= ($dataProvider->totalCount > 0) ? '<strong>Найдено:</strong> ' . $dataProvider->totalCount : 'Результатов не найдено' ?>
        </p>
    </div>
    <div class="an-left">
        <?php  if (!empty($dataProvider->models)) { ?>
            <?= \frontend\widgets\ItemWidget::widget(['items' => $dataProvider->models, 'itemsClass' => 'news', 'imageClass' => 'news-img'])?>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
    <div class="clearfix"></div>
</div>
<div class="ln-right">
    <div class="ln-sedebar">
        <?php  if (!empty($popularModels)) { ?>
            <h3 class="sidebar-title">
                <?=Yii::t('frontend','Популярные')?>
            </h3>
            <?= \frontend\widgets\ItemWidget::widget(['items' => $popularModels, 'itemsClass' => 'sidebar-news', 'is_mini' => true])?>
        <?php } ?>
    </div>
    <div class="ln-sedebar">
        <?= SubscribeWidget::widget([]);?>
    </div>
    <div class="ln-sedebar">
        <div class="sidebar-currency">
            <div class="informers">
                <a href="https://cbu.uz/" target="_blank" title="Ўзбекистон Республикаси Марказий банки"><img src="https://cbu.uz/oz/informer/?txtclr=ffffff&brdclr=2d95e3&bgclr=2d95e3&r_choose=USD_EUR_RUB" alt=""></a>
                <a href="http://www.intermeteo.com/asia/uzbekistan/tashkent/" title="Weather in Tashkent"><img src="http://inf.intermeteo.com/c/586/9932/1126.png" alt="Weather forecast for Tashkent" width=120 height=120 border=0></a>
            </div>
        </div>
    </div>
</div>