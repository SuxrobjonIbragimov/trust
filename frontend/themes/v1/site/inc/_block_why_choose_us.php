<?php

use backend\models\post\PostCategories;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\models\post\PostCategories */
/* @var $modelItems \backend\models\post\Posts */
/* @var $item \backend\models\post\Posts */

$modelItems = $model->getActivePosts()->orderBy(['weight' => SORT_ASC, 'title' => SORT_ASC])->limit(PostCategories::ACTIVE_CHILD_LIMIT)->all();
?>
<?php if (!empty($modelItems)): ?>
    <!--========================================================================-->
    <!-- |COMPANY CONVENIENCE| -->
    <!--========================================================================-->
    <section class="section-company-confortablity">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-8 col-md-10 col-sm-10 col-12 row gy-4 align-items-stretch">
                    <h2 class="text-secondary fw-bold text-center text-uppercase mb-4"><?= $model->name; ?></h2>
                    <?php foreach ($modelItems as $key => $item): ?>
                        <div class="col-md-<?= ($key == 0) || ($key == 3) ? '7' :'' ?><?= ($key == 1) || ($key == 2) ? '5' :'' ?> col-12">
                            <div class="card h-100 py-2 px-3">
                                <div class="card-body text-gray p-0">
                                    <div class="card-title-gen d-flex align-items-center justify-content-start mb-2">
                                        <i class="text-primary bx <?= $item->icon ?> bx-lg me-2"></i>
                                        <h5 class="card-title"><?= $item->title; ?></h5>
                                    </div>
                                    <p class="card-text"><?= Html::encode($item->summary) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>