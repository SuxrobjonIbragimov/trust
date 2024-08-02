<?php

use backend\models\post\PostCategories;
use yii\helpers\Url;

/* @var $model PostCategories */

?>
<?php if (!empty($model->description)): ?>
    <!-- |ABOUT| -->
    <!--========================================================================-->
    <section class="section-about my-5">
        <div class="container">
            <div class="col-12 row">
                <div class="col-lg-6">
                    <img src="<?= $model->image ?>"
                         class="w-100 h-100 rounded-3 object-fit-cover" alt="About">
                </div>
                <div class="col-lg-5 offset-lg-1 offset-0 d-flex align-items-start justify-content-center flex-column mt-lg-0 mt-4">
                    <!-- offset-lg-1 offset-0-->
                    <h3 class="text-secondary fw-bold text-uppercase mb-4"><?= $model->name ?></h3>
                    <p class="text-gray fs-5">
                        <?php print ($model->description); ?>
                    </p>
                    <a href="<?= Url::to(['/page/about']) ?>"
                       class="btn btn-outline-primary rounded-2 text-uppercase fs-5 fw-bold px-4 py-2 mt-2"><?= Yii::t('frontend', 'Подробнее') ?></a>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>