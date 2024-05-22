<?php

namespace frontend\controllers;

use backend\models\review\Contact;
use Yii;
use yii\web\Controller;
use common\models\Settings;
use backend\models\page\Pages;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;

class PageController extends Controller
{
    /**
     * Displays about page.
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about', [
            'model' => $this->findPage('about'),
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
        ]);
    }

    /**
     * Displays contact page.
     * @return mixed
     */
    public function actionContact()
    {
        $modelForm = new ContactForm();
        if ($modelForm->load(Yii::$app->request->post())) {
            $modelForm->type = Contact::TYPE_CONTACT;
            if (($modelContact = $modelForm->save()) != false) {
                $message_tmp = Yii::t('frontend','Благодарим Вас за обращение к нам. Мы ответим вам как можно скорее.');
                $message_tmp .= Yii::t('frontend','Your application ID: {id}.', ['id' => $modelContact->id]);
                Yii::$app->session->addFlash('success', $message_tmp);
                return $this->refresh();
            }  else {
                Yii::$app->session->addFlash('error', Yii::t('frontend', 'При отправке вашего сообщения произошла ошибка.'));
            }

        }

        return $this->render('contact', [
            'modelForm' => $modelForm,
            'model' => $this->findPage('contact'),
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
        ]);
    }

    public function actionCheckStatus()
    {
        $modelForm = new Contact();
        $response = null;
        if ($modelForm->load(Yii::$app->request->post())) {
            $response = $modelForm->getAppInfo();
            if (isset($response['status']) && empty($response['status'])) {
                Yii::$app->session->addFlash('error',Yii::t('frontend','Application not found'));
            }
        }

        return $this->render('check_status_app', [
            'modelForm' => $modelForm,
            'response' => $response,
            'statistics' => $modelForm->getStatistics(),
            'model' => $this->findPage('check_status_app'),
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
        ]);
    }

    /**
     * Displays Page model.
     * @param string $slug
     * @return mixed
     */
    public function actionView($slug)
    {
        return $this->render('view', [
            'model' => $this->findPage($slug),
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
        ]);
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
