<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model \backend\models\parts\HtmlParts */

?>
<?php if (!empty($model->body)):?>
    <!-- |ABOUT| -->
    <!--========================================================================-->
    <section class="section-about my-5">
        <div class="container">
            <div class="col-12">
                <div class="row">
                <div class="col-lg-6">
                    <img src="/themes/v1/images/about.jpg" class="w-100 h-100 rounded-3 object-fit-cover background-attachment" alt="About">
                </div>
                <div
                        class="col-lg-5 offset-lg-1 offset-0 d-flex align-items-start justify-content-center flex-column mt-lg-0 mt-4"><!-- offset-lg-1 offset-0-->
                    <h2 class="text-primary fw-bold text-uppercase mb-4"><?=Yii::t('frontend','О компании')?></h2>
                    <div class="text-gray text-justify fs-6">
                        <?php print ($model->summary); ?>
                    </div>
                    <a href="<?= Url::to(['/page/about'])?>" class="media-btn-class btn btn-outline-secondary rounded-2 text-uppercase fs-5 fw-bold px-4 py-2 mt-2"><?=Yii::t('frontend','Подробнее')?></a>
                </div>
            </div>
            </div>
        </div>
    </section>
<?php endif; ?>