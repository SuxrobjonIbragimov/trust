<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\policy\models\PolicyTravel */
/* @var $modelTravellers backend\modules\policy\models\PolicyTravelTraveller */

$this->title = Yii::t('policy','Download policy {policy}',['policy' => $model->fullPolicyNumber]);
$this->params['breadcrumbs'][] = $this->title;
$type = $model->policyOrder->getProductName();
?>

<div class="container-fluid pdf">
    <?php if ($type == 'TRAVEL') :?>
        <div class="row" style="margin: 0;padding:0 -15px;">
            <div class="header" style="float: left; width: 75%;padding-left: 15px;">
                <h2 style="font-size: 18px;">Полис страхования путешествующих</h2>
                <h2 style="font-size: 18px;">International Travel Insurance policy</h2>
            </div>
            <div style="text-align: right;float: left; width: 22%;">
                <img class="img img-responsive" src="/media/images/logo-png.png" />
            </div>
            <div style="clear: both"></div>
            <p style="color: red; padding-left: 490px; ">Номер полиса: <?=$model->fullPolicyNumber?></p>
        </div>
        <table class="table table-bordered mt-4 mb-0" style="margin: 0 -15px;margin-top: 10px;">
            <tr>
                <th colspan=2>Данные страхователя / Data of insurer</th>
            </tr>
            <tr>
                <td>
                    <p class="text-bold m-0">Страхователь / Insurer</p>
                    <p class="upper m-0"><?=$model->appFullName?></p>
                </td>
                <td>
                    <p class="text-bold m-0">Контактный номер / Contact number</p>
                    <p class="upper m-0"><?=$model->app_phone?></p>
                </td>
            </tr>
        </table>
        <?php if (!empty($model->policyTravelTravellers)) :?>
            <table class="table table-bordered mt-0 mb-0" style="margin: 0 -15px;">
                <tr>
                    <th colspan=3>Данные застрахованных лиц / Data of insured persons</th>
                </tr>
                <?php foreach($model->policyTravelTravellers as $index => $tr): ?>
                    <tr>
                        <td>
                            <?php if($index == 0) echo "<p class='text-bold m-0'>Застрахованные лица / Insured persons</p>"; ?>
                            <p class="upper m-0"><?=($index + 1) . '.' . $tr->first_name?></p>
                        </td>
                        <td>
                            <?php if($index == 0) echo "<p class='text-bold m-0'>Дата рождения / Date of birth</p>";?>
                            <p class="upper m-0"><?=date('d.m.Y', strtotime($tr->birthday))?></p>
                        </td>
                        <td>
                            <?php if($index == 0) echo "<p class='text-bold m-0'>Детали паспорта / Passport details</p>";?>
                            <p class="upper m-0"><?=$tr->pass_sery . ' ' . $tr->pass_num?></p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <table class="table table-bordered" style="margin: 0 -15px;">
                <tr>
                    <th colspan=3>Условия страхования / Insurance terms</th>
                </tr>
                <tr>
                    <td>
                        <p class="text-bold m-0">Территория страхования / Territory of cover</p>
                        <p class="upper m-0"><?php echo implode(', ', json_decode($model->country_ids)); ?></p>
                    </td>
                    <td>
                        <p class="text-bold m-0">Период страхования / Insurance period</p>
                        <p class="upper m-0">c <?=date('d.m.Y', strtotime($model->start_date))?> до <?=date('d.m.Y', strtotime($model->end_date))?></p>
                    </td>
                    <td>
                        <p class="text-bold m-0">Дней / Days</p>
                        <p class="upper m-0"><?=$model->days?></p>
                    </td>
                </tr>

            </table>
            <?php $sum= 60000; ?>
            <table class="table table-bordered mt-0 mb-0" style="margin: 0 -15px;">
                <tr>
                    <td>
                        <p class="text-bold m-0">Общее страховое покрытие / Total sum insured</p>
                        <p class="upper m-0"><?=number_format($sum,0,","," ")?> EUR</p>
                    </td>
                    <td>
                        <p class="text-bold m-0">Программа / Program</p>
                        <p class="upper m-0"><?=$model->program_id?></p>
                    </td>
                    <td>
                        <p class="text-bold m-0">Особые условия / Special conditions</p>
                        <p class="upper m-0"><?php if($model->program_id == 6 || $model->program_id == 7) echo "COVID - 19";
                            echo '/' . $model->purpose_id; ?></p>
                    </td>
                    <td>
                        <p class="text-bold m-0">Страховая премия / Insurance premium</p>
                        <p class="upper m-0">
                            <?=number_format($model->amount_uzs,0,","," ")?> UZS
                        </p>
                    </td>
                </tr>
            </table>

        <?php endif;?>

        <div class="row" style="margin-top: 1rem;">
            <div style="float:left;width:73%;padding-left:15px;">
                <h5 style="font-size:12px;"><?=Yii::t('policy','Генеральный директор ООО «INSURANCE NAME» FIO.')?></h5>
            </div>
            <div style="float:right;width:25%;text-align:right;">
                <div class="podpis"><img src="/images/pdf/lifepodpis.png" width="120px" /></div>
                <div class="pechat"><img src="/images/pdf/pechat.png" /></div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row assistance">
            <p style="font-size: 12px;">Чтобы получить необходимую медицинскую помощь в путешествии, при внезапном заболевании и/или несчастном случае, обращайтесь в круглосуточные сервисные центры Службы Ассистанса следующими способами</p>
            <div style="float:left;width:48%;">
                <div>
                    <div style="float:left;width:24%; margin-top: 40px;">
                        <p style="font-size: 12px;"><span><img width="20px" src="/uploads/countries/109.png" /></span> Турция</p>
                    </div>


                    <div class="clearfix"></div>
                </div>

            </div>

        </div>


        <div class="row" style="margin-top: 8px;">
            <div style="float:left;width:33%;">
                <h5>В любых других странах</h5>
            </div>
            <div style="float:left;width:23%;">
                <img src="/images/pdf/allianz_assistance_logo.png" class="img img-responsive" />
            </div>
            <div class="contacts" style="float:left;width:43%;">
                <div>
                    <div style="float:left;width:49%;">
                        <p style="font-size:10px;text-align: center;" class="m-0"><span class="c-icon"><i class="fa fa-telegram"></i></span><img width="20px" src="/images/pdf/phone.png" /> +7 (495) 212 21 43</p>
                    </div>

                    <div style="float:left;width:49%;">
                        <p style="font-size:10px;text-align: right; color: red;" class="m-0"><span class="c-icon"><i class="fa fa-envelope"></i></span><img width="20px" src="/images/pdf/email.png" /> assistance.ru@allianz.com</p>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row assistance" style="margin-bottom: 6px;">
            <div style="float:left;width:98%;">
                <p style="font-size:10px;text-align: right;text-decoration: underline; color: red;" class="m-0">Бесплатный интернет звонок: www.allianz-worldwide-partners.ru</p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row" style="margin-top: 4px;">
            <div style="width: 60%; float: left; font-size: 14px;text-transform: uppercase;">
                Желаем вам приятного путешествия
            </div>
            <div style="width: 40%; font-size:14px; float: right; text-align: right;">
                Серия и номер полиса: <?=$model->fullPolicyNumber?><br>Дата выдачи: <?php
                echo date('d.m.Y', $model->policyOrder->paymentTransaction->created);
                ?>
            </div>
            <div style="clear: both"></div>
        </div>

    <?php elseif ($type == 'KASKO'):?>

        <?php /* @var $model backend\modules\policy\models\PolicyKasko */?>
        <div class="row" style="margin: 0;padding:0 -15px;">
            <div class="header" style="float: left; width: 70%;padding-left: 15px;">
                <h2 style="font-size: 18px;">Полис добровольного страхования транспортных средств</h2>
            </div>
            <div style="text-align: right;float: left; width: 22%;">
                <img class="img img-responsive" src="/media/images/logo-png.png" />
            </div>
            <div style="clear: both"></div>
            <p style="color: #00aa59; padding-left: 490px; ">Номер полиса: <?=$model->fullPolicyNumber?></p>
        </div>
        <table class="table table-bordered mt-4 mb-0" style="margin: 0 -15px;margin-top: 10px;">
            <tr>
                <th colspan=4>Данные страхователя</th>
            </tr>
            <tr>
                <td colspan="2">
                    <p class="text-bold m-0">Страхователь</p>
                    <p class="upper m-0"><?=$model->appFullName?></p>
                </td>
                <td colspan="2">
                    <p class="text-bold m-0">Контактный номер</p>
                    <p class="upper m-0">+<?=$model->app_phone?></p>
                </td>
            </tr>
            <tr style="border-top: none;">
                <th colspan=4>Данные транспортного средства</th>
            </tr>
            <tr>
                <td>
                    <p class='text-bold' style='margin-bottom: 5px;'>Производитель</p>
                    <p class="upper"><?= !empty($model->carComplectation->carModel->carBrand->name) ? $model->carComplectation->carModel->carBrand->name : Yii::t('policy','unknown') ?></p>
                </td>
                <td colspan="2">
                    <p class='text-bold' style='margin-bottom: 5px;'>Модель автомобиля</p>
                    <p class="upper"><?= !empty($model->carComplectation->carModel->name) ? $model->carComplectation->carModel->name : Yii::t('policy','unknown') ?></p>
                </td>
                <td>
                    <p class='text-bold' style='margin-bottom: 5px;'>Год выпуска</p>
                    <p class="upper"><?=$model->car_year ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p class='text-bold' style='margin-bottom: 5px;'>Государственный номер</p>
                    <p class="upper"><?=$model->car_number?></p>
                </td>
                <td colspan="2">
                    <?php
                    $price = $model->carComplectation->price;
                    $current_year = date('Y');
                    for($i = $model->car_year; $i < $current_year; $i++) {
                        $price = $price * 0.95;
                    }
                    ?>
                    <p class='text-bold' style='margin-bottom: 5px;'>Полная стоимость автомобиля</p>
                    <p class="upper"><?=number_format($price, 0,","," ")?> сум</p>
                </td>
            </tr>
        </table>


        <div class="row" style="margin-top: 1rem;">
            <div style="float:left;width:73%;padding-left:15px;">
                <h5 style="font-size:12px;"><?=Yii::t('policy','Генеральный директор ООО «INSURANCE NAME» FIO.')?></h5>
            </div>
            <div style="float:right;width:25%;text-align:right;">
                <div class="podpis"><img src="/images/pdf/lifepodpis.png" width="120px" /></div>
                <div class="pechat"><img src="/images/pdf/pechat.png" /></div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row" style="margin-top: 4px;">
            <div style="width: 60%; float: left; font-size: 14px;text-transform: uppercase;">
                Желаем вам uspex
            </div>
            <div style="width: 40%; font-size:14px; float: right; text-align: right;">
                Серия и номер полиса: <?=$model->fullPolicyNumber?><br>Дата выдачи: <?php
                echo date('d.m.Y', $model->policyOrder->paymentTransaction->created);
                ?>
            </div>
            <div style="clear: both"></div>
        </div>

    <?php endif;?>
</div>
