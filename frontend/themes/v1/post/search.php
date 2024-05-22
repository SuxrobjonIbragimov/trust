<?php

use frontend\widgets\SubscribeWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use kartik\typeahead\Typeahead;

/** @var $this yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $model \backend\models\post\Posts */
/** @var $pagination \yii\data\Pagination */
/** @var $text string */

$this->title = Yii::t('frontend', 'Результат поиска');

$this->params['breadcrumbs'][] = $this->title;
$this->params['container-class'] = 'search-page';
?>

<div class="ln-left my-3">
    <div class="news-search">
        <?= Html::beginForm(['post/search'], 'get', ['class' => 'search-form']) ?>
        <div class="input-group">
            <?= Typeahead::widget([
                'name' => 'text',
                'options' => [
                    'placeholder' => Yii::t('app','Поиск...'),
                    'required' => true,
                    'type' => 'search',
                    'class' => 'dropdown-search-input border-gray',
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
                            'url' => Url::to(['post/post-list']) . '?q=%QUERY',
                            'wildcard' => '%QUERY'
                        ]
                    ]
                ],
                'pluginEvents' => [
                    'typeahead:select' => 'function(event, data) {
                            location.href = "/post/view/" + data["key"];
                        }',
                ]
            ]) ?>
            <?= Html::submitButton(Yii::t('app','Поиск'),
                ['class' => 'dropdown-search-btn']) ?>
        </div>
        <?= Html::endForm() ?>
    </div>
    <div class="latest-news-top my-2 menu">
        <div class="title">
            <h4><?=Yii::t('app','Результаты поиска для')?>: <?= $text ?> </h4>
        </div>
    </div>
    <div class="qty">
        <p>
            <?= ($dataProvider->totalCount > 0) ? '<strong>Найдено:</strong> ' . $dataProvider->totalCount : 'Результатов не найдено' ?>
        </p>
    </div>
    <div class="an-left row justify-content-center">
        <?php  if (!empty($dataProvider->models)) { ?>
            <?= \frontend\widgets\ItemWidget::widget(['items' => $dataProvider->models, 'itemsClass' => 'post', 'imageClass' => 'post-img'])?>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
    <?= LinkPager::widget([
            'pagination' => $pagination,

        'options' => [
            'tag' => 'ul',
            'class' => 'pagination justify-content-center fs-4',
            'id' => 'pager-container',
        ],
        //First option value
        'firstPageLabel' => '<svg xmlns="http://www.w3.org/2000/svg" style="fill:#3e96fc;"><path d="m8.121 12 4.94-4.939-2.122-2.122L3.879 12l7.06 7.061 2.122-2.122z"></path><path d="M17.939 4.939 10.879 12l7.06 7.061 2.122-2.122L15.121 12l4.94-4.939z"></path></svg>',
        //Last option value
        'lastPageLabel' => '<svg xmlns="http://www.w3.org/2000/svg" style="fill: #3e96fc;"><path d="m13.061 4.939-2.122 2.122L15.879 12l-4.94 4.939 2.122 2.122L20.121 12z"></path><path d="M6.061 19.061 13.121 12l-7.06-7.061-2.122 2.122L8.879 12l-4.94 4.939z"></path></svg>',
        //Previous option value
        'prevPageLabel' => '<svg xmlns="http://www.w3.org/2000/svg" style="fill:#3e96fc;"><path d="M13.939 4.939 6.879 12l7.06 7.061 2.122-2.122L11.121 12l4.94-4.939z"></path></svg>',
        //Next option value
        'nextPageLabel' => '<svg xmlns="http://www.w3.org/2000/svg" style="fill:#3e96fc;"><path d="M10.061 19.061 17.121 12l-7.06-7.061-2.122 2.122L12.879 12l-4.94 4.939z"></path></svg>',
        //Current Active option value
        'activePageCssClass' => 'page-active',

        // Css for each options. Links
        'linkOptions' => ['class' => 'page-link fw-bold'],
        'disabledPageCssClass' => 'disabled',

        // Customzing CSS class for navigating link
        'pageCssClass' => ['class' => 'page-item'],
        'prevPageCssClass' => 'page-back',
        'nextPageCssClass' => 'page-next',
        'firstPageCssClass' => 'page-first',
        'lastPageCssClass' => 'page-last',
        ]) ?>
    <div class="clearfix"></div>
</div>