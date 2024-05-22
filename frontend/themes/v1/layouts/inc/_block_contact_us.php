<?php

use common\models\Settings;
use yii\helpers\Url;

/** @var string $phone */

$phone = Settings::getValueByKey(Settings::KEY_MAIN_PHONE);
?>

<!--========== SECTION CONTACT US ==========-->
<section class="section-contact-us" id="contactUs">
    <div class="container">
        <div class="section-contact-us__content position-relative">
            <div class="section-contact-us__decorTop svg-none">
                <svg width="170" height="170" viewbox="0 0 170 170" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M105 37L97 37M101 33L101 41" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M137 37L129 37M133 33L133 41" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M72.9998 37L64.9998 37M68.9998 33L68.9998 41" stroke="#DBE0E7" stroke-width="2"
                          stroke-linecap="round" />
                    <path d="M40.9998 37L32.9998 37M36.9998 33L36.9998 41" stroke="#3E96FC" stroke-width="2"
                          stroke-linecap="round" />
                    <path d="M9 37L1 37M5 33L5 41" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M9 101L1 101M5 97L5 105" stroke="#F3745A" stroke-width="2" stroke-linecap="round" />
                    <path d="M9 133L1 133M5 129L5 137" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M9 165L1 165M5 161L5 169" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M40.9998 101L32.9998 101M36.9998 97L36.9998 105" stroke="#DBE0E7" stroke-width="2"
                          stroke-linecap="round" />
                    <path d="M73 101L65 101M69 97L69 105" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M40.9998 133L32.9998 133M36.9998 129L36.9998 137" stroke="#DBE0E7" stroke-width="2"
                          stroke-linecap="round" />
                    <path d="M40.9998 165L32.9998 165M36.9998 161L36.9998 169" stroke="#DBE0E7" stroke-width="2"
                          stroke-linecap="round" />
                    <path d="M73 133L65 133M69 129L69 137" stroke="#3E96FC" stroke-width="2" stroke-linecap="round" />
                    <path d="M105 5L97 5M101 1L101 9" stroke="#F3745A" stroke-width="2" stroke-linecap="round" />
                    <path d="M169 5L161 5M165 1L165 9" stroke="#3E96FC" stroke-width="2" stroke-linecap="round" />
                    <path d="M72.9998 5L64.9998 5M68.9998 1L68.9998 9" stroke="#DBE0E7" stroke-width="2"
                          stroke-linecap="round" />
                    <path d="M137 5L129 5M133 1L133 9" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M40.9998 5L32.9998 5M36.9998 1L36.9998 9" stroke="#DBE0E7" stroke-width="2"
                          stroke-linecap="round" />
                    <path d="M9 5L1 5M5 1L5 9" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M9 69L1 69M5 65L5 73" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M40.9998 69L32.9998 69M36.9998 65L36.9998 73" stroke="#DBE0E7" stroke-width="2"
                          stroke-linecap="round" />
                    <path d="M72.9998 69L64.9998 69M68.9998 65L68.9998 73" stroke="#DBE0E7" stroke-width="2"
                          stroke-linecap="round" />
                    <path d="M105 69L97 69M101 65L101 73" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>
            <div class="section-contact-us__decorBottom svg-none">
                <svg width="138" height="106" viewbox="0 0 138 106" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M101 1L101 9M105 5L97 5" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M101 33L101 41M105 37L97 37" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M101 65L101 73M105 69L97 69" stroke="#3E96FC" stroke-width="2" stroke-linecap="round" />
                    <path d="M101 97L101 105M105 101L97 101" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M37 97L37 105M41 101L33 101" stroke="#F3745A" stroke-width="2" stroke-linecap="round" />
                    <path d="M5 97L5 105M9 101L1 101" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M37 65L37 73M41 69L33 69" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M5 65L5 73M9 69L1 69" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M133 1L133 9M137 5L129 5" stroke="#3E96FC" stroke-width="2" stroke-linecap="round" />
                    <path d="M133 33L133 41M137 37L129 37" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M133 65L133 73M137 69L129 69" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M133 97L133 105M137 101L129 101" stroke="#DBE0E7" stroke-width="2"
                          stroke-linecap="round" />
                    <path d="M69 97L69 105M73 101L65 101" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M69 65L69 73M73 69L65 69" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                    <path d="M69 33L69 41M73 37L65 37" stroke="#DBE0E7" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>
            <div class="section-contact-us__title"><?=Yii::t('frontend','Нужна помощь или информация?')?>
                <br>
                <?=Yii::t('frontend','Не стесняйтесь связаться с нами')?>
            </div>
            <div class="section-contact-us__row">
                <div class="gen-col">
                    <div class="section-contact-us__col border-primary-custom contact-us__col-width bgColor-White br-18">
                        <div class="section-contact-us__icon">
                            <svg class="section-contact-us__svg" width=" 36" height="36" viewbox="0 0 36 36" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.8 10.8C21.2322 10.8 22.6057 11.3689 23.6184 12.3816C24.6311 13.3943 25.2 14.7678 25.2 16.2C25.2 16.6774 25.3896 17.1352 25.7272 17.4728C26.0648 17.8104 26.5226 18 27 18C27.4774 18 27.9352 17.8104 28.2728 17.4728C28.6104 17.1352 28.8 16.6774 28.8 16.2C28.8 13.8131 27.8518 11.5239 26.164 9.83604C24.4761 8.14821 22.1869 7.2 19.8 7.2C19.3226 7.2 18.8648 7.38964 18.5272 7.72721C18.1896 8.06477 18 8.52261 18 9C18 9.47739 18.1896 9.93523 18.5272 10.2728C18.8648 10.6104 19.3226 10.8 19.8 10.8Z"
                                    fill="#3E96FC" />
                                <path
                                    d="M19.8 3.6C23.1417 3.6 26.3466 4.9275 28.7095 7.29045C31.0725 9.65341 32.4 12.8583 32.4 16.2C32.4 16.6774 32.5896 17.1352 32.9272 17.4728C33.2648 17.8104 33.7226 18 34.2 18C34.6774 18 35.1352 17.8104 35.4728 17.4728C35.8104 17.1352 36 16.6774 36 16.2C36 11.9035 34.2932 7.78296 31.2551 4.74487C28.217 1.70678 24.0965 0 19.8 0C19.3226 0 18.8648 0.189642 18.5272 0.527208C18.1896 0.864773 18 1.32261 18 1.8C18 2.27739 18.1896 2.73523 18.5272 3.07279C18.8648 3.41036 19.3226 3.6 19.8 3.6ZM35.55 25.038C35.451 24.749 35.2801 24.49 35.0534 24.2853C34.8266 24.0807 34.5515 23.937 34.254 23.868L23.454 21.402C23.1608 21.3355 22.8557 21.3435 22.5664 21.4253C22.2772 21.507 22.013 21.6599 21.798 21.87C21.546 22.104 21.528 22.122 20.358 24.354C16.4758 22.5651 13.365 19.4415 11.592 15.552C13.878 14.4 13.896 14.4 14.13 14.13C14.3401 13.915 14.493 13.6508 14.5747 13.3615C14.6565 13.0723 14.6645 12.7672 14.598 12.474L12.132 1.8C12.063 1.50245 11.9193 1.22737 11.7147 1.00064C11.51 0.773914 11.251 0.603004 10.962 0.504C10.5416 0.353861 10.1076 0.245341 9.666 0.18C9.21105 0.0745113 8.7468 0.0142188 8.28 0C6.08401 0 3.97796 0.872355 2.42516 2.42516C0.872355 3.97796 0 6.08401 0 8.28C0.00952545 15.6289 2.93308 22.674 8.12952 27.8705C13.326 33.0669 20.3711 35.9905 27.72 36C28.8073 36 29.884 35.7858 30.8886 35.3697C31.8932 34.9536 32.806 34.3437 33.5748 33.5748C34.3437 32.806 34.9536 31.8932 35.3697 30.8886C35.7858 29.884 36 28.8073 36 27.72C36.0005 27.2618 35.9644 26.8044 35.892 26.352C35.8163 25.9049 35.7019 25.4652 35.55 25.038ZM27.72 32.4C21.3244 32.3952 15.1922 29.8525 10.6698 25.3301C6.1475 20.8078 3.60477 14.6756 3.6 8.28C3.60474 7.04025 4.09933 5.85262 4.97598 4.97598C5.85262 4.09933 7.04025 3.60474 8.28 3.6H8.874L10.8 11.952L9.828 12.456C8.28 13.266 7.056 13.914 7.704 15.318C8.7592 18.3052 10.4666 21.0199 12.7021 23.2648C14.9377 25.5097 17.6452 27.2284 20.628 28.296C22.14 28.908 22.734 27.774 23.544 26.208L24.066 25.218L32.4 27.126V27.72C32.3953 28.9598 31.9007 30.1474 31.024 31.024C30.1474 31.9007 28.9597 32.3953 27.72 32.4Z"
                                    fill="#3E96FC" />
                            </svg>
                        </div>
                        <div class="section-contact-us__title-col"><?=Yii::t('frontend','Связаться с нами')?></div>
                        <div class="section-contact-us__title-text">
                            <?=Yii::t('frontend','Свяжитесь с нами по любому вопросу. Мы поможем ответить на ваше сообщение как можно скорее')?>
                        </div>
                        <a href="<?= Url::to(['/page/contact'])?>" class="section-contact-us__btn"><?=Yii::t('frontend','Связаться с нами')?></a>
                    </div>
                </div>
                <div class="gen-col">
                    <div class="section-contact-us__col border-primary-custom contact-us__col-width bgColor-White br-18">
                        <div class="section-contact-us__icon">
                            <svg class="section-contact-us__svg" width=" 36" height="36" viewbox="0 0 36 36" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M32.7273 11.4533C33.5953 11.4533 34.4277 11.7982 35.0414 12.412C35.6552 13.0257 36 13.8582 36 14.7262V21.272C36 22.14 35.6552 22.9725 35.0414 23.5863C34.4277 24.2001 33.5953 24.5449 32.7273 24.5449H30.9895C30.5905 27.7086 29.0508 30.618 26.6592 32.7271C24.2676 34.8362 21.1886 35.9999 18 36V32.7271C20.6039 32.7271 23.1012 31.6926 24.9425 29.8513C26.7838 28.0099 27.8182 25.5125 27.8182 22.9084V13.0898C27.8182 10.4857 26.7838 7.9883 24.9425 6.14695C23.1012 4.30559 20.6039 3.27113 18 3.27113C15.3961 3.27113 12.8988 4.30559 11.0575 6.14695C9.21623 7.9883 8.18182 10.4857 8.18182 13.0898V24.5449H3.27273C2.40475 24.5449 1.57232 24.2001 0.95856 23.5863C0.344804 22.9725 0 22.14 0 21.272V14.7262C0 13.8582 0.344804 13.0257 0.95856 12.412C1.57232 11.7982 2.40475 11.4533 3.27273 11.4533H5.01055C5.40987 8.28993 6.94977 5.38095 9.3413 3.27223C11.7328 1.16352 14.8116 0 18 0C21.1884 0 24.2672 1.16352 26.6587 3.27223C29.0502 5.38095 30.5901 8.28993 30.9895 11.4533H32.7273ZM11.0618 24.1931L12.7964 21.4176C14.3559 22.3947 16.1597 22.9114 18 22.9084C19.8403 22.9114 21.6441 22.3947 23.2036 21.4176L24.9382 24.1931C22.8588 25.4959 20.4538 26.1851 18 26.1813C15.5462 26.1851 13.1412 25.4959 11.0618 24.1931Z"
                                    fill="#3E96FC" />
                            </svg>
                        </div>
                        <div class="section-contact-us__title-col"><?=Yii::t('frontend','Обслуживание клиентов')?></div>
                        <div class="section-contact-us__title-text">
                            <?=Yii::t('frontend','Позвоните нам, чтобы поговорить с членом нашей команды. Мы всегда рады помочь вам.')?>
                        </div>
                        <a href="tel:<?= $phone; ?>" class="section-contact-us__btn"><?=Yii::t('frontend','Звоните сейчас')?></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>