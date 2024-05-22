<?php

/* @var $this yii\web\View */
/* @var $title string */
/* @var $logo string */
/* @var $metaKeywords string */
/* @var $metaDescription string */
/* @var $footerText string */
/* @var $slider \backend\models\sliders\Sliders */
/* @var $product_list_main \backend\models\insurance\InsuranceProduct */
/* @var $home_about_us_part \backend\models\parts\HtmlParts */
/* @var $why_choose_us \backend\models\post\PostCategories */
/* @var $companies_served \backend\models\post\PostCategories */
/* @var $partners \backend\models\post\PostCategories */
/* @var $comments \backend\models\comment\Comments */
/* @var $latest_news \backend\models\post\PostCategories */
/* @var $online_voting \backend\models\post\PostCategories */
/* @var $modelVote \backend\models\post\Posts */
/* @var $online_voted boolean */
/* @var $modelFeedback \backend\models\review\Contact */

$this->title = $title;

$this->params['meta_type'] = 'page';
$this->params['meta_url'] = Yii::$app->request->hostInfo;
$this->params['meta_image'] = $logo;
if ($metaKeywords !== null)
    $this->params['meta_keywords'] = $metaKeywords;
if ($metaDescription !== null)
    $this->params['meta_description'] = $metaDescription;

if ($footerText !== null)
    $this->params['footer_text'] = $footerText;

?>

<!--Header Intro-->
<?= $this->render('inc/_block_header_intro',[
        'slider' => $slider
]) ?>
<!--Header Intro END-->

<?php if (!empty($product_list_main)):?>
    <!--SECTION LATEST NEWS-->
    <?= $this->render('inc/_block_product_list',[
        'model' => $product_list_main,
    ]) ?>
    <!--SECTION LATEST NEWS END-->
<?php endif; ?>

<?php if (!empty($home_about_us_part)):?>
    <!--SECTION SAFE-->
    <?= $this->render('inc/_block_about_us',[
    'model' => $home_about_us_part,
    ]) ?>
    <!--SECTION SAFE END-->
<?php endif; ?>

<?php if (!empty($why_choose_us)):?>
    <!--SECTION ADVANTAGE SIDE-->
    <?= $this->render('inc/_block_why_choose_us',[
            'model' => $why_choose_us,
    ]) ?>
    <!--SECTION ADVANTAGE SIDE END-->
<?php endif; ?>


<?php if (!empty($latest_news)):?>
    <!--SECTION LATEST NEWS-->
    <?= $this->render('inc/_block_latest_news',[
        'model' => $latest_news,
    ]) ?>
    <!--SECTION LATEST NEWS END-->
<?php endif; ?>


<?php if (!empty($comments)): ?>
    <!--SECTION COMMENTS-->
    <?= $this->render('inc/_block_comments', [
        'model' => $comments,
    ]) ?>
    <!--SECTION COMMENTS END-->
<?php endif; ?>


<?php if (!empty($online_voting)):?>
    <!--SECTION VOTING-->
    <?= $this->render('inc/_block_voting_feedback',[
        'online_voted' => $online_voted,
        'model' => $modelVote,
        'online_voting' => $online_voting,
    ]) ?>
    <!--SECTION VOTING END-->
<?php endif; ?>


<?php if (!empty($our_services)): ?>
    <!--SECTION OUR COMPETENCIES-->
    <?= $this->render('inc/_block_our_competencies', [
        'model' => $our_services,
    ]) ?>
    <!--SECTION OUR COMPETENCIES END-->
<?php endif; ?>


<?php if (!empty($partners)):?>
    <!--SECTION OUR PARTNER-->
    <?= $this->render('inc/_block_partners',[
        'model' => $partners,
    ]) ?>
    <!--SECTION OUR PARTNER END-->
<?php endif; ?>


<?php if (!empty($modelFeedback)): ?>
    <!--SECTION OUR COMPETENCIES-->
    <?= $this->render('inc/_block_contact_us', [
        'modelFeedback' => $modelFeedback,
    ]) ?>
    <!--SECTION OUR COMPETENCIES END-->
<?php endif; ?>





