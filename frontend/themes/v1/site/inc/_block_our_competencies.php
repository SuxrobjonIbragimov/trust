<?php

use backend\models\post\PostCategories;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\models\post\PostCategories */
/* @var $modelItems \backend\models\post\Posts */
/* @var $item \backend\models\post\Posts */
$modelItems = $model->getActivePosts()->orderBy(['weight' => SORT_ASC])->limit(PostCategories::ACTIVE_CHILD_OUR_COMPETENCIES)->all();
?>
<?php if (!empty($modelItems)): ?>
    <!--========================================================================-->
    <!-- |OUR ADVANTAGES| -->
    <!--========================================================================-->
    <section>
        <div class="">
            <div class="advantages-bg">
                <div class="container py-5">
                    <div class="advantages">
                        <h2 class="title advantages-title text-primary mb-5"><?= $model->name; ?></h2>
                        <!-- <div class="text">Почему стоит выбрать именно нас</div> -->
                        <div class="row">
                            <?php $i = 0 ?>
                            <?php foreach ($modelItems as $key => $item): ?>
                                <?php $i++ ?>
                                <?php if ($i == 1) {
                                    $isShow = ' show ';
                                } else {
                                    $isShow = '';
                                }
                                if ($isShow != true) {
                                    $isCollapse = '  collapsed ';
                                } else {
                                    $isCollapse = '';
                                }
                                ?>
                                <div class="advantages-article col-lg-3 col-md-6 col-12 border-1 <?= ($key == 0) ? 'border-start': '' ?> border-end
                                text-center border-primary">
                                    <div
                                            class="advantages-article-title d-flex align-items-center justify-content-center text-primary">
                                        <span data-number=" <?= $item->number_pointer?> " data-string =" <?= ($key == 1) ? ' ming+ ': ''?> "  class="counter-number me-2"> <?= $item->number_pointer?></span>
                                        <div class="counter-text"> <?= ($key == 1) ? ' ming+ ': ''?> </div>
                                    </div>
                                    <div class="advantages-article-text text-primary"><?= $item->title; ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>