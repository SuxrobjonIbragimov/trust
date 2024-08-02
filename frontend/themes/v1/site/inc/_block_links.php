<?php

/**
 *
 * @var $useful_links PostCategories
 */

use backend\models\post\PostCategories;
?>
<!--========================================================================-->
<!-- |COMMENT | SWIPER| -->
<!--========================================================================-->
<section class="bg-secondary position-relative">
    <div id="particles-js" class="position-absolute top-0 start-0 z-index-1"></div>
    <div class="container py-5">
        <h2 class="text-white fw-bold text-center text-uppercase mb-4"><?= $useful_links->name; ?></h2>
        <div class="swiper commentUsers pt-5">
            <div class="swiper-wrapper bg-secondary pt-5">
                <?php foreach ($useful_links->posts ?? [] as $post): ?>
                    <div class="swiper-slide bg-secondary">
                        <div class="card comment-user-card w-100 mx-3">
                            <div class="card-body d-flex z-index-9 flex-column justify-content-between">
                                <p class="card-text text-gray fs-6"><?= $post->summary ?></p>
                                <a href="<?= $post->source_link ?>"
                                   class="fs-7 text-lowercase d-flex align-items-center ms-auto text-secondary fw-bold">
                                    <?= $post->source_link; ?>
                                    <i class='bx bx-right-arrow-alt ms-1'></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div
                class="swiper-button-next bg-white swiper-new-btn-next shadow-hover border border-gray border-1 rounded-3 px-4 py-2 me-3 shadow-lg">
                <i class="bx text-gray bx-right-arrow-alt"></i>
            </div>
            <div
                class="swiper-button-prev bg-white swiper-new-btn-prev shadow-hover border border-gray border-1 rounded-3 px-4 py-2 shadow-lg">
                <i class="bx text-gray bx-left-arrow-alt"></i>
            </div>
        </div>
    </div>
</section>