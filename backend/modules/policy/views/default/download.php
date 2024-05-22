<?php

use app\common\widgets\CustomAlert;
use app\widgets\ActionsWidget;
use yii\widgets\Breadcrumbs;

?>

<main class="middle">
    <div class="policy-download">
        <?php if (!is_mobile_app()) :?>
            
            <div class="container">
                <?= Breadcrumbs::widget([
                    'homeLink' => [
                        'label' => Yii::t('policy','Home page'),
                        'url' => Yii::$app->homeUrl,
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'itemTemplate' => "<li  class='breadcrumbs__item'>{link}</li>\n",
                    'activeItemTemplate' => "<li  class='breadcrumbs__item breadcrumbs__item--active'>{link}</li>\n",
                ]) ?>
            </div>
        <?php endif;?>

        <div class="container">
            <div class="flex-center-block contact-us-page__container mb-3">
                
            </div>
        </div>
        <div class="container">
            <h1 class="title title--center news__title"><?= $this->title ?></h1>

            <div class="flex-center-block contact-us-page__container mb-3">

                <div class="policy-default-index">
                    <h1><?= $this->context->action->uniqueId ?></h1>
                    <p>
                        This is the view content for action "<?= $this->context->action->id ?>".
                        The action belongs to the controller "<?= get_class($this->context) ?>"
                        in the "<?= $this->context->module->id ?>" module.
                    </p>
                    <p>
                        You may customize this page by editing the following file:<br>
                    </p>
                </div>

            </div>
        </div>
</main>