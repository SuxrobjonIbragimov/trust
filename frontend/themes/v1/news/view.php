<?php

use frontend\widgets\SocialNetworksWidget;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\modules\news\models\News */
/* @var $modelItem \backend\modules\news\models\News */
/* @var $popularModels \backend\modules\news\models\News */
/* @var $authorModels \backend\modules\news\models\News */
/* @var $products array */
/* @var $commentForm \backend\models\comment\Comments */
/* @var $loginForm \common\models\LoginForm */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = $model->meta_title;

$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Все новости'), 'url' => ['category', 'slug' => 'all']];
if (isset($model->categories[0])) {
    if (($parent = $model->categories[0]->parent) != null) {
        if ($parent->parent != null)
            $this->params['breadcrumbs'][] = ['label' => $parent->parent->name, 'url' => ['category', 'slug' => $parent->parent->slug]];
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'slug' => $parent->slug]];
    }
    $this->params['breadcrumbs'][] = ['label' => $model->categories[0]->name, 'url' => ['category', 'slug' => $model->categories[0]->slug]];
}
$this->params['breadcrumbs'][] = $model->name;

$this->params['meta_type'] = 'product';
$this->params['meta_url'] = Yii::$app->request->hostInfo . '/news/view/' . $model->slug;
if ($model->meta_keywords)
    $this->params['meta_keywords'] = $model->meta_keywords;
if ($model->meta_description)
    $this->params['meta_description'] = $model->meta_description;
if (!empty($model->image))
    $this->params['meta_image'] = Yii::$app->request->hostInfo . $model->image;

$this->params['container_class'] = 'np';
?>

<div class="ln-left">
    <?php if (!empty($model->categories[0])):?>
        <?= Html::a('<span class="'.$model->categories[0]->slug.'">'.$model->categories[0]->name.'</span>', ['/news/category', 'slug' => $model->categories[0]->slug]) ?>
    <?php endif;?>
    <h1 class="np-title">
        <?= $model->name ?>
    </h1>
    <div class="view">
        <span><?= Yii::$app->formatter->asDate($model->created_at, 'long')?></span>
        <div class="view-comment">
            <span>
                <i class="fas fa-eye"></i>
                <?= Yii::$app->formatter->asDecimal($model->views_counter, 0)?>
            </span>
            <a href="#add-comment">
                <i class="fas fa-comments"></i>
                <?= $dataProvider->totalCount ?>
            </a>
        </div>
    </div>
    <div class="np-img">
        <?= Html::img($model->image, ['alt' => $model->name, 'class' => 'news-image'])?>
    </div>
    <div class="np-text">
        <?= $model->summary ?>
        <?= $model->body ?>
    </div>
    <?php if (!empty($model->tags)):?>
        <div class="tags">
            <span><i class="fas fa-tags"></i> <?=Yii::t('frontend','ТЕГИ')?>:</span>
            <?php $tags = my_tag_decoder($model->tags) ?>
            <?php foreach ($tags as $tag):?>
                <a href="<?=\yii\helpers\Url::to(['news/category','slug' => 'all', 'tag' => $tag ])?>"  class='tag'>
                    #<?=$tag?>
                </a>
            <?php endforeach;?>
        </div>
    <?php endif;?>
    <div class="socials">
        <div class="social">
            <?= SocialNetworksWidget::widget(['tag_class' => 'social', 'item_class_has' => true]) ?>
        </div>
    </div>
    <?php if (!empty($authorModels)):?>
        <section class="editors b-top">
            <div class="title p-top-buttom30">
                <h2>
                    <?=Yii::t('frontend','ВЫБОР РЕДАКТОРА')?>
                </h2>
            </div>
            <div class="np-editors-carousel owl-carousel owl-theme">
                <?php foreach ($authorModels as $modelItem):?>
                    <div class="item">
                        <div class="gradient">

                        </div>
                        <?= Html::img($modelItem->image, ['alt' => $modelItem->name, 'class' => 'news-image'])?>
                        <?= Html::a('<p>'.$modelItem->name.'</p>', ['/news/view', 'slug' => $modelItem->slug]) ?>
                    </div>
                <?php endforeach;?>
            </div>
        </section>
    <?php endif;?>
    <div class="comments b-top">
        <div class="title p-top-buttom30">
            <h2>
                <?=Yii::t('frontend','комменты')?>
            </h2>
        </div>

        <?php
        if (!empty($dataProvider->models)) {
            Pjax::begin(['id' => 'pjax_comments_list']);
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n<div align='center'>{pager}</div>",
                'itemView' => '_comment',
                'itemOptions' => ['class' => 'comment b-border'],
                'pager' => ['class' => LinkPager::className()]
            ]);
            Pjax::end();
        } ?>

    </div>
    <div class="add-comment" id="add-comment">
        <?php if (Yii::$app->user->isGuest) {
//            Pjax::begin();
            echo '<br><p>'
                . Yii::t('frontend', 'Для того, чтобы оставить свой отзыв выполните {btnLogin} или {btnSignup} на сайте.', [
                    'btnLogin' => Html::tag('b', Yii::t('frontend', 'вход')),
                    'btnSignup' => Html::a('<b><i>' . Yii::t('frontend', 'зарегистрируйтесь') . '</i></b>',
                        ['customer/signup'], ['data-pjax' => 0])
                ])
                . '</p><p><b>' . Yii::t('frontend', 'Пожалуйста, заполните следующие поля для входа:')
                . '</b></p>';
            echo '<div class="row"><div class="col-md-6 col-sm-8">';
            $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['data-pjax' => true]]);
            echo $form->field($loginForm, 'username')
                . $form->field($loginForm, 'password')->passwordInput()
                . $form->field($loginForm, 'rememberMe')->checkbox(['template' => '<label class="checkbox">{input}<i></i>{labelTitle}</label>'])
                . '<p>'
                . Html::a(Yii::t('frontend', 'Забыли пароль?'), ['customer/request-password-reset'], ['data-pjax' => 0])
                . Html::submitButton(Yii::t('frontend', 'Войти'), ['class' => 'btn btn-primary pull-right', 'name' => 'login-button'])
                . '</p>';
            ActiveForm::end();
            echo '</div></div>';
//            Pjax::end();
        } else {
            echo '<div class="title p-top30">' . Yii::t('frontend', 'ДОБАВИТЬ КОММЕНТАРИЙ') . '</div>';
            $form = ActiveForm::begin(['id' => 'comment-form']);

            echo $form->field($commentForm, 'text')->textarea(['rows' => 6])
                . Html::submitButton(Yii::t('frontend', 'Комментировать'),
                    ['class' => 'btn btn-primary', 'name' => 'comment-button']);
            ActiveForm::end();
        } ?>
    </div>
</div>

<?php
$js = <<<JS
JS;
$this->registerJs($js);

?>
<!--//products-->
