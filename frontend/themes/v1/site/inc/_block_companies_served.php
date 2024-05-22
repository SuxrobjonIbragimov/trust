<?php

use backend\models\post\PostCategories;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\models\post\PostCategories */
/* @var $modelItems \backend\models\post\Posts */
/* @var $item \backend\models\post\Posts */

$modelItems = $model->getActivePosts()->orderBy(['weight' => SORT_ASC, 'title' => SORT_ASC])->limit(PostCategories::ACTIVE_CHILD_LIMIT)->all();
?>
<?php if (!empty($modelItems)):?>
    <section class="section-build-slider" id="buildSlider">
        <div class="container">
            <div class="splide position-relative" id="splide2" role="group2" aria-label="<?= $model->name; ?>"
                 data-aos="zoom-in" data-aos-delay="300">
                <h2 class="section-build-slider__h2">
                    <?= $model->name; ?>
                </h2>
                <div class="splide__arrows build-slider__arrows splide__arrows--ltr">
                    <button class="splide__arrow build-slider__arrow build-slider__prev splide__arrow--prev" type="button"
                            aria-label="Previous slide" aria-controls="splide01-track">
                        <svg class="build-slider__svg-icon" width="6" height="10" viewbox="0 0 6 10" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M0.691817 -4.89618e-07C0.883103 -5.06341e-07 1.03613 0.0628924 1.1509 0.188678L5.79915 4.55526C5.93305 4.68104 6 4.8248 6 4.98652C6 5.14825 5.93305 5.30099 5.79915 5.44474L1.1509 9.81132C1.017 9.93711 0.854411 10 0.663125 10C0.471839 10 0.31881 9.93711 0.204039 9.81132C0.0892677 9.68554 0.0223178 9.54178 0.00318903 9.38005C-0.0159393 9.21833 0.0510105 9.06559 0.204039 8.92183L4.3932 4.98652L0.204038 1.05121C0.0701381 0.925426 0.00318828 0.781671 0.00318827 0.619946C0.00318825 0.458221 0.0701381 0.314465 0.204038 0.188678C0.337938 0.0628925 0.500531 -4.72896e-07 0.691817 -4.89618e-07Z" />
                        </svg>
                    </button>
                    <button class="splide__arrow build-slider__arrow build-slider__next splide__arrow--next" type="button"
                            aria-label="Next slide" aria-controls="splide01-track">
                        <svg class="build-slider__svg-icon" width="6" height="10" viewbox="0 0 6 10" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M0.691817 -4.89618e-07C0.883103 -5.06341e-07 1.03613 0.0628924 1.1509 0.188678L5.79915 4.55526C5.93305 4.68104 6 4.8248 6 4.98652C6 5.14825 5.93305 5.30099 5.79915 5.44474L1.1509 9.81132C1.017 9.93711 0.854411 10 0.663125 10C0.471839 10 0.31881 9.93711 0.204039 9.81132C0.0892677 9.68554 0.0223178 9.54178 0.00318903 9.38005C-0.0159393 9.21833 0.0510105 9.06559 0.204039 8.92183L4.3932 4.98652L0.204038 1.05121C0.0701381 0.925426 0.00318828 0.781671 0.00318827 0.619946C0.00318825 0.458221 0.0701381 0.314465 0.204038 0.188678C0.337938 0.0628925 0.500531 -4.72896e-07 0.691817 -4.89618e-07Z" />
                        </svg>
                    </button>
                </div>
                <div class="splide__track overflow-visible">
                    <ul class="splide__list">
                        <?php foreach ($modelItems as $item):?>
                            <li class="splide__slide">
                                <div class="section-build-slider__row">
                                    <div class="section-build-slider__left">
                                        <a class="section-build-slider__anchor" href="<?= Url::to(['/post/view', 'slug' => $item->slug])?>" data-pjax="0">
                                            <?= Html::encode($item->title) ?>
                                            <div class="section-build-slider__anchor-icon">
                                                <svg width="20" height="16" viewbox="0 0 20 16" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M12.2597 0L10.8394 1.46796L16.1551 6.962H1.00431C0.449644 6.962 0 7.42673 0 8C0 8.57327 0.449644 9.038 1.00431 9.038H16.1551L10.8394 14.532L12.2597 16L20 8L12.2597 0Z"
                                                          fill="#3E96FC" />
                                                </svg>
                                            </div>
                                        </a>
                                        <p class="section-build-slider__p">
                                            <?= Html::encode($item->summary) ?>
                                        </p>
                                    </div>
                                    <div class="section-build-slider__right position-relative">
                                        <div class="section-build-slider__img-box br-30 w-100 h-100">
                                            <img class="section-build-slider__img" src="<?= $item->image; ?>" alt="create">
                                        </div>
                                        <div class="section-build-slider__decorTop svg-none">
                                            <svg width="106" height="138" viewbox="0 0 106 138" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 37L9 37M5 33L5 41" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M33 37L41 37M37 33L37 41" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M65 37L73 37M69 33L69 41" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M97 37L105 37M101 33L101 41" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M97 101L105 101M101 97L101 105" stroke="#F3745A" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M97 133L105 133M101 129L101 137" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M65 101L73 101M69 97L69 105" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M65 133L73 133M69 129L69 137" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M1 5L9 5M5 1L5 9" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M33 5L41 5M37 1L37 9" stroke="#3E96FC" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M65 5L73 5M69 1L69 9" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M97 5L105 5M101 1L101 9" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M97 69L105 69M101 65L101 73" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                                <path d="M65 69L73 69M69 65L69 73" stroke="#DBE0E7" stroke-width="2"
                                                      stroke-linecap="round" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>