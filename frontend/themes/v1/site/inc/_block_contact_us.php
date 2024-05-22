<?php


/* @var $modelFeedback \backend\models\review\Contact */
?>
<section class="section-feedback-our-customers my-3 pb-0" id="voteOurCustomers" style="">
    <div class="container">
        <div class="section-latest-news__content position-relative" style="">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="flex-centered--row">
                        <h2 class="sections-primary-title text-center fw-bold mb-4 text-uppercase"><?= Yii::t('frontend', 'Обратная связь') ?></h2>
                    </div>
                    <div class="mb-3 mx-auto w-100">
                        <div class="flex-center-block mb-3 mx-auto">
                            <?= $this->render('_block_feedback', [
                                'model' => $modelFeedback,
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>