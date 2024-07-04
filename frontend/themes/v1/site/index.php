<?php


use backend\models\comment\Comments;
use backend\models\insurance\InsuranceProduct;
use backend\models\parts\HtmlParts;
use backend\models\post\PostCategories;
use backend\models\post\Posts;
use backend\models\review\Contact;
use backend\models\sliders\Sliders;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $logo string */
/* @var $metaKeywords string */
/* @var $metaDescription string */
/* @var $footerText string */
/* @var $slider Sliders */
/* @var $product_list_main InsuranceProduct */
/* @var $home_about_us_part HtmlParts */
/* @var $why_choose_us PostCategories */
/* @var $companies_served PostCategories */
/* @var $partners PostCategories */
/* @var $comments Comments */
/* @var $latest_news PostCategories */
/* @var $useful_links PostCategories */
/* @var $online_voting PostCategories */
/* @var $modelVote Posts */
/* @var $online_voted boolean */
/* @var $modelFeedback Contact */

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

<?php if (!empty($partners)):?>
    <!--SECTION OUR PARTNER-->
    <?= $this->render('inc/_block_partners',[
        'model' => $partners,
    ]) ?>
    <!--SECTION OUR PARTNER END-->
<?php endif; ?>

<?php if (!empty($latest_news)):?>
    <!--SECTION LATEST NEWS-->
    <?= $this->render('inc/_block_latest_news',[
        'model' => $latest_news,
    ]) ?>
    <!--SECTION LATEST NEWS END-->
<?php endif; ?>


<?php if (!empty($useful_links)): ?>
    <!--SECTION COMMENTS-->
    <?= $this->render('inc/_block_links', [
        'useful_links' => $useful_links,
    ]) ?>
    <!--SECTION COMMENTS END-->
<?php endif; ?>





