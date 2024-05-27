<?php

/* @var $this yii\web\View */

use common\widgets\CustomAlert;
use yii\widgets\Breadcrumbs;
use yii2mod\notify\BootstrapNotify;
/* @var $content string */
?>

<main style="min-height:50vh;">
    <?php if ((is_front())):?>
        <?= $content ?>
    <?php else:?>
        <section class="other-inner-page py-3">
            <div class="container pb-3">
                <nav aria-label="breadcrumb">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumb'],
                        'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>",
                        'activeItemTemplate' => "<li class='breadcrumb-item active'>{link}</li>\n",
                    ]) ?>
                </nav>
                <h4 class="main-title title-center"><?= $this->title ?></h4>
                <?= $content ?>
            </div>
        </section>
    <?php endif;?>

    <?= CustomAlert::widget() ?>

</main>
