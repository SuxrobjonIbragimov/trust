<?php

/* @var $this yii\web\View */
/* @var $model \backend\models\post\Posts */
/* @var $total_votes int */

use yii\helpers\Html;

$total_votes = !empty($model->category->totalVotes) ? $model->category->totalVotes : 0;
?>


<div class="single-blog files-item my-3 ">
    <div class="w-100 row m-0 align-items-center justify-content-between latest-news__bShadow-0818-8 bg-light br-18 border-primary-custom" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-delay="300" data-pjax="0">
        <div class="col-sm-12 mt-lg-2 mt-md-2 mt-sm-4">
            <?php if (!empty($model)):?>
                <?php $current_percent = ($total_votes) ? round($model->views/$total_votes*100, 2) : 0; ?>
                <div class="section-feedback-our-customers__text py-0">
                    <?= Html::encode($model->title); ?>
                    <div class="container py-0">
                        <div class="row align-items-center">
                            <progress class="col-9 pt-2" id="vote-<?= $model->id?>" value="<?= $current_percent; ?>" max="100"> <?= $current_percent; ?>% </progress>
                            <span class="col-3 py-0"><?php echo $model->views; ?> (<?= $current_percent; ?>%)</span>
                        </div>
                    </div>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>