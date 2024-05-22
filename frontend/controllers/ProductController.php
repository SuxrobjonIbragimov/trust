<?php

namespace frontend\controllers;

use backend\models\insurance\InsuranceProduct;
use backend\models\page\Pages;
use backend\models\review\Contact;
use backend\modules\handbook\models\HandbookLegalType;
use common\models\Settings;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProductController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'legal_type_list' => HandbookLegalType::_getItemsList(),
            'type' => Yii::$app->request->get('type') ?? 1
        ]);
    }

    /**
     * Post View
     * @param $slug
     * @return mixed
     */
    public function actionView($slug)
    {
        return $this->render('view', [
            'model' => $this->findModel($slug)
        ]);
    }

    public function actionInsuranceCase()
    {
        $modelForm = new Contact(['scenario' => Contact::SCENARIO_CLAIM]);

        if ($modelForm->load(Yii::$app->request->post())) {
            $modelForm->type = Contact::TYPE_CLAIM;
            if ($modelForm->save()) {
                Yii::$app->session->addFlash('success', 'Successfully sent');
                $modelForm = new Contact();
            } else {
                Yii::$app->session->addFlash('error', $modelForm->errors);
            }
        }
        return $this->render('claim', [
            'modelForm' => $modelForm,
            'model' => $this->findPage('insurance-case'),
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
        ]);
    }

    /**
     * Find Posts model.
     * @param string $slug
     * @return InsuranceProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($slug)
    {
        if (($model = InsuranceProduct::findOne(['slug' => $slug, 'status' => InsuranceProduct::STATUS_ACTIVE])) !== null) {
            $model->views += 1;
            $model->save();

            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
        }
    }

    /**
     * Find Pages model.
     * @param string $url
     * @return Pages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPage($url)
    {
        if (($model = Pages::findOne(['url' => $url, 'status' => Pages::STATUS_ACTIVE])) !== null)
            return $model;
        else
            throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }
}
