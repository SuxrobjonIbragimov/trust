<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Settings;
use backend\models\page\Pages;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;

class RuleController extends Controller
{
    /**
     * Displays about page.
     * @return mixed
     */
    public function actionOsgo()
    {
        $lang = _lang();
        $rule = Settings::getValueByKey('offer_osgo_'.$lang);
        if (empty($rule)) {
            $rule = Settings::getValueByKey('offer_osgo_ru');
        }
        return $this->redirect([$rule]);
    }

    /**
     * Displays about page.
     * @return mixed
     */
    public function actionTr()
    {
        $lang = _lang();
        $rule = Settings::getValueByKey('offer_travel_'.$lang);
        if (empty($rule)) {
            $rule = Settings::getValueByKey('offer_travel_ru');
        }
        return $this->redirect([$rule]);
    }

}
