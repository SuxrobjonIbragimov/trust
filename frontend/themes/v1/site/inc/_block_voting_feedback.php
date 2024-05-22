<?php

use backend\models\post\PostCategories;
use backend\models\review\Contact;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $online_voting \backend\models\post\PostCategories */
/* @var $modelVote \backend\models\post\Posts */
/* @var $post \backend\models\post\Posts */
/* @var $online_voted boolean */
/* @var $modelFeedback \backend\models\review\Contact */

?>
<?php if (!empty($online_voting)): ?>
    <section class="section-feedback-our-customers my-3 pb-0" id="voteOurCustomers" style="">
        <div class="container py-5">
            <div class="section-latest-news__content position-relative" style="">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <h2 class="text-center fw-bold text-primary mb-4 text-uppercase"><?= Yii::t('frontend', 'Onlayn so’rovnoma') ?></h2>
                        <div class="card w-100 rounded-3 shadow px-2 pb-2 pt-4 mb-3 voice-card overflow-hidden mt-5">
                            <div class="flex-centered--row z-index-1">
                                <h2 class="sections-primary-title text-center py-3 mp-3 mb-4"><?= $online_voting->name; ?></h2>
                            </div>
                            <div class="row section-feedback-our-customers__row z-index-1" style="justify-content: space-around">
                                <?php if (!empty($model)): ?>
                                    <div class="col-12 news-card">
                                        <div class="section-feedback-our-customers__box border-primary-custom bgColor-White br-18 px-2 py-2 ps-lg-5">
                                            <?php if ($online_voted): ?>
                                                <?php $result = null; ?>
                                                <?php $total_votes = 0; ?>
                                                <?php foreach ($online_voting->activePosts as $post): ?>
                                                    <?php $total_votes += $post->views; ?>
                                                <?php endforeach; ?>
                                                <?php foreach ($online_voting->activePosts as $post): ?>
                                                    <?php $current_percent = ($total_votes) ? round($post->views / $total_votes * 100, 2) : 0; ?>
                                                    <div class="section-feedback-our-customers__text py-1">
                                                        <?= Html::encode($post->title); ?>
                                                        <div class="container py-0">
                                                            <div class="row align-items-center">
                                                                <progress class="col-9 p-3" id="vote-<?= $post->id ?>"
                                                                          value="<?= $current_percent; ?>"
                                                                          max="100"> <?= $current_percent; ?>%
                                                                </progress>
                                                                <span class="col-3 py-0"><?php echo $post->views; ?> (<?= $current_percent; ?>%)</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>

                                                <h4 class="section-contact-us__title-col pt-3">
                                                    <?= Yii::t('frontend', 'So’rovda qatnashganingiz uchun rahmat!'); ?>
                                                </h4>

                                            <?php else: ?>
                                                <?php $form = ActiveForm::begin([
                                                    'id' => 'voting-form',
                                                    'options' => [
                                                        'data-pjax' => false
                                                    ]
                                                ]); ?>
                                                <div class="field modal__field">
                                                    <?= $form->field($model, 'id')->label(false)->radioList($online_voting->getVotingList(), [
                                                        'class' => 'row',
                                                        'item' => function ($index, $label, $name, $checked, $value) {
                                                            $checked = $checked ? 'checked' : '';
                                                            return "<label class='col-12 h5 checkbox me-1 my-2'><input type='radio' class='me-1' {$checked} name='{$name}' value='{$value}'><i class='ml-05'></i>{$label}</label>";
                                                        }
                                                    ]) ?>
                                                </div>
                                                <div class="field modal__field">
                                                    <div class="page-footer-button py-3">
                                                        <div class="form-group d-flex justify-center">
                                                            <?= Html::submitButton(Yii::t('frontend', 'Vote'), ['class' => 'media-btn-class btn btn-primary rounded-2 text-uppercase fs-5 fw-bold px-4 py-2 mt-2', 'disabled' => false]) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php ActiveForm::end(); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="position-absolute w-200px h-200px z-index-0" style="bottom:-80px; right:-80px;"><!-- bottom-0 end-0-->
                                <img class="w-100 h-100 object-fit-contain" src="/themes/v1/images/logo/oriental.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>