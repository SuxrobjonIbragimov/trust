<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\widgets\LinkPager;
use backend\models\post\PostCategories;

/* @var $this yii\web\View */
/* @var $filterData array */
/* @var $y int */
/* @var $model PostCategories */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;

$this->params['meta_type'] = 'post-category';
$this->params['meta_url'] = Yii::$app->request->hostInfo . '/post/category/' . $model->slug;
if ($model->meta_keywords)
    $this->params['meta_keywords'] = $model->meta_keywords;
if ($model->meta_description)
    $this->params['meta_description'] = $model->meta_description;
if (!empty($model->image))
    $this->params['meta_image'] = Yii::$app->request->hostInfo . $model->image;
if ($model->description)
    $this->params['footer_text'] = $model->description;

$render_item = '_item';
$key_type = PostCategories::getKeyTypeArray($model->key);
$render_item .= !empty($key_type) ? '_' . $key_type : null;
$render_item_class = PostCategories::getKeyColClassArray($key_type);

?>

<?php
$path = 'styles/posts.css';
$v = '?v=0.0.1';
$file_path = Yii::getAlias('@webroot/themes/v1/' . $path);
if (file_exists($file_path)) {
    $v = '?v=' . filemtime($file_path);
}
$path .= $v;
$this->registerCssFile("@web/themes/v1/{$path}", [
    'depends' => [\frontend\assets\V1Asset::className()],
], 'css-dd-theme-posts');

$map_data = [];
?>

<div class="deals posts-category category-<?= $model->key ?> mt-3 py-3">

    <?php if ($key_type == PostCategories::KEY_FILES):?>
        <?php if (!empty($filterData)):?>
            <div class="d-flex flex-wrap align-items-center file-link-block year gap-2 col-md-12 col-sm-12">
                <?php foreach ($filterData as $year => $value_year):?>
                    <?php if (!empty($year)):?>
                        <a href="<?= Url::current(['y' => $year])?>" class="btn btn-warning <?= ($year == $y) ? 'active' : ''?>"><?= $year ?></a>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        <?php endif;?>
    <?php endif;?>
    <div class="row">
        <div class="col-12 deals posts-category category-<?= $model->key ?> ">
            <?php if ($model->key == PostCategories::KEY_BRANCHES): ?>
                <div class="col-12">
                    <?php foreach ($dataProvider->models as $model_branch): ?>
                        <?php
                        if (!empty($model_branch->latitude) && !empty($model_branch->longitude)) {
                            $phone_str = '';
                            if (!empty($model_branch->work_phone)) {
                                $phones = explode(',', $model_branch->work_phone);
                                $phone_str = '<ul class="list d-flex flex-row flex-wrap p-0">';
                                foreach ($phones as $index_p => $phone_c) {
                                    if (!empty($phone_c)) {
                                        $phone = clear_phone_full($phone_c);
                                        $phone_mask = mask_to_phone_format($phone);
                                        $phone_str .= '<li class="me-2">';
                                        $phone_str .= '<a href="tel:+' . $phone . '" target="_blank" class="d-flex flex-row mt-3 border-bottom-animation-on-hover w-max-content text-decoration-none" data-pjax="0">
                                    <div class="head-contact-icon">
                                        <svg class="nav-icon-info_svg" width="14" height="13" viewbox="0 0 14 13" fill="var(--bs-primary)">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M2.51378 1.03399C2.42805 0.954394 2.28906 0.954393 2.20334 1.03399C0.816827 2.32147 0.66083 4.35973 1.83732 5.81634L2.51531 6.65575C3.7425 8.17512 5.19602 9.52482 6.83226 10.6644L7.73625 11.2939C9.30491 12.3864 11.5 12.2415 12.8865 10.954C12.9722 10.8744 12.9722 10.7454 12.8865 10.6658L10.8903 8.81217C10.6854 8.62193 10.3532 8.62193 10.1483 8.81217L9.47989 9.43288C9.18778 9.70413 8.74152 9.77137 8.37203 9.59982C6.33369 8.65345 4.6809 7.11871 3.66173 5.22597C3.47698 4.88287 3.5494 4.46849 3.84151 4.19725L4.50997 3.57654C4.71485 3.38629 4.71485 3.07785 4.50997 2.88761L2.51378 1.03399ZM1.46142 0.345065C1.95689 -0.115021 2.76022 -0.115022 3.2557 0.345065L5.2519 2.19868C5.86653 2.7694 5.86653 3.69474 5.2519 4.26547L4.62921 4.84368C5.5393 6.50555 6.99402 7.85636 8.78373 8.70145L9.40642 8.12324C10.0211 7.55251 11.0176 7.55251 11.6322 8.12324L13.6284 9.97685C14.1239 10.4369 14.1239 11.1829 13.6284 11.643C11.8727 13.2733 9.09309 13.4567 7.10671 12.0733L6.20272 11.4438C4.48694 10.2489 2.96276 8.83356 1.67592 7.24033L0.997933 6.40092C-0.491854 4.55642 -0.294316 1.97539 1.46142 0.345065Z" />
                                        </svg>
                                    </div>
                                    <span class=" mx-1">' . $phone_mask . '</span>
                                </a>';
                                        $phone_str .= '</li>';
                                    }
                                }
                                $phone_str .= '</ul>';
                            }
                            $map_data[] = [
                                'title' => $model_branch->title,
                                'address' => $model_branch->address,
                                'phone' => $phone_str,
                                'latitude' => $model_branch->latitude,
                                'longitude' => $model_branch->longitude,
                            ];
                        }
                        ?>
                    <?php endforeach; ?>
                    <div id="big_map" style="width:100%; height:400px"></div>
                    <script>
                        ymaps.ready(init);
                        let map_data = <?php echo json_encode($map_data); ?>;
                        let coords = [];
                        var myGeoObjects = ["big_map"];

                        function init() {
                            for (var i = 0; i < map_data.length; i++) {
                                coords[i] = [
                                    map_data[i].latitude,
                                    map_data[i].longitude,
                                ]
                                myGeoObjects[i] = new ymaps.GeoObject({
                                        geometry: {
                                            type: "Point",
                                            coordinates: coords[i]
                                        },
                                        properties: {
                                            clusterCaption: map_data[i].title,
                                            balloonContentHeader: map_data[i].title,
                                            balloonContentBody: map_data[i].address,
                                            balloonContentFooter: map_data[i].phone
                                        }
                                    }, {
                                        preset: 'islands#blueIcon', //все метки красные
                                    }
                                );
                            }
                            var myClusterer = new ymaps.Clusterer({
                                preset: 'islands#invertedBlueClusterIcons',
                                clusterDisableClickZoom: false,
                            });

                            myClusterer.add(myGeoObjects);

                            var myMap = new ymaps.Map("big_map", {
                                center: [41.286824, 69.272685],
                                zoom: 6,
                            });
                            myMap.geoObjects.add(myClusterer);

                        }


                    </script>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($model->key == PostCategories::KEY_HEAD):?>
        <!--SECTION HEAD-->
        <?= $this->render('_item_head_new',[
            'models' => $dataProvider->models
        ]) ?>

    <?php else:?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'class' => 'row align-items-stretch justify-content-lg-between justify-content-center g-3',
            ],
            'layout' => "{items}\n<div align='center'>{pager}</div>",
            'itemView' => $render_item,
            'itemOptions' => ['class' => $render_item_class],
            'pager' => [
                'class' => LinkPager::className(),
                'maxButtonCount' => 7,
                'options' => [
                    'tag' => 'ul',
                    'class' => 'pagination justify-content-center my-3 pt-3',
                    'id' => 'pager-container',
                ],
                'disabledListItemSubTagOptions' => [
                    'class' => 'page-link',
                ],
                //Current Active option value
                'activePageCssClass' => 'page-active',

                // Css for each options. Links
                'linkOptions' => ['class' => 'page-link'],
                'disabledPageCssClass' => 'page-item disabled',

                // Customzing CSS class for navigating link
                'pageCssClass' => ['class' => 'page-item'],
                'prevPageCssClass' => 'page-item page-back',
                'nextPageCssClass' => 'page-item page-next',
                'firstPageCssClass' => 'page-item page-first',
                'lastPageCssClass' => 'page-item page-last',
            ]
        ]) ?>
    <?php endif;?>

</div>