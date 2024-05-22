<?php

namespace frontend\modules\payment\controllers;

use common\library\click\Click;
use common\library\click\ClickMerchantApi;
use common\library\click\exceptions\ClickException;
use backend\modules\policy\models\PolicyOrder;
use common\library\payment\models\PaymentTransaction;
use common\library\request_logger\models\RequestHistory;
use backend\modules\policy\models\PolicyTravel;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use Yii;

class ClickController extends \yii\web\Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['cancel',],
                'rules' => [
                    [
                        'actions' => ['cancel',],
                        'allow' => true,
                        'roles' => ['accessDashboard'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id == 'prepare' || $action->id == 'complete') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $click = new Click();
        return $this->render('index', ['click' => $click]);
    }

    /**
     * @return false|string
     */
    public function actionPrepare()
    {
        $request = file_get_contents('php://input');
        $request = json_decode($request, true);
        $request = Yii::$app->request->post();
        $click = new Click();
        $check_reuqest = $click->request_check($request);
        if ($check_reuqest['error']<0) {
            $reply_params = $check_reuqest;
        } else {
            $reply_params = $click->prepare($request);
        }
        $action = !empty($request['action']) ? $request['action'] : 0;
        $params = [
            'method' => $action,
            'request' => $request,
            'post' => Yii::$app->request->post(),
            'get' => Yii::$app->request->get(),
            'response' => $reply_params,
        ];

        set_history($params,$reply_params,'click_notify_prepare');
        return json_encode($reply_params);

    }

    /**
     * @return false|string
     */
    public function actionComplete()
    {
        $request = file_get_contents('php://input');
        $request = json_decode($request, true);
        $request = Yii::$app->request->post();
        $click = new Click();
        $check_reuqest = $click->request_check($request);
        if ($check_reuqest['error']<0) {
            $reply_params = $check_reuqest;
        } else {
            $reply_params = $click->complete($request);
        }
        $action = !empty($request['action']) ? $request['action'] : 0;
        $params = [
            'method' => $action,
            'request' => $request,
            'post' => Yii::$app->request->post(),
            'get' => Yii::$app->request->get(),
            'response' => $reply_params,
        ];

        set_history($params,$reply_params,'click_notify_complete');
        return json_encode($reply_params);

    }


    public function actionPay($h)
    {
        $click = new Click();
        $id_model = _model_decrypt($h);
        if (!empty($id_model['id']) && !empty($id_model['formName'])) {
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            $model = $modelClassName::findOne($id_model['id']);
            /* @var $model PolicyTravel */
            if (!empty($model->policyOrder)) {
                $content = [
                    'order_id' => (!empty($id_model['formName']) && $id_model['formName'] == "PolicyOsgo") ? $model->ins_anketa_id : $model->policyOrder->id,
                    'total' => $model->policyOrder->total_amount,
                ];
                $click->performTransaction($content);
                $paylink = $click->generatePayLink([
                    'h' => $h,
                    'osgo' => (!empty($id_model['formName']) && $id_model['formName'] == "PolicyOsgo") ? 'osgo' : null,
                ]);
                return $this->redirect($paylink);
            }
        }
        Yii::$app->session->addFlash('error', Yii::t('policy','Order not found'));
        return $this->goBack();
    }

    public function actionCancel($h)
    {
        $click = new ClickMerchantApi();
        $id_model = _model_decrypt($h);
        if (!empty($id_model['id']) && !empty($id_model['formName'])) {
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            $model = $modelClassName::findOne($id_model['id']);
            /* @var $model PolicyTravel */
            if (!empty($model->policyOrder)) {
                $content = [
                    'payment_id' => $model->policyOrder->paymentTransaction->click_paydoc_id,
                ];
                $click->setParams($content);
                $click->setMethod(ClickMerchantApi::METHOD_PAYMENT_CANCEL);
                $click->setRequestMethod('DELETE');
                $response = $click->sendRequest();
                Yii::warning("\n\nClick Cancel RESPONSE");
                Yii::warning($response);
                if ((is_array($response) && array_key_exists('error_code',$response)) && $response['error_code'] == ClickException::ERROR_NO) {
                    $model->policyOrder->paymentTransaction->status = PaymentTransaction::STATUS_PAYMENT_CANCELLED_AFTER_COMPLETE;
                    if (!$model->policyOrder->paymentTransaction->save()) {
                        if (LOG_DEBUG_SITE) {
                            dd($model->policyOrder->paymentTransaction->errors);
                        }
                        Yii::warning("\n\nClick Cancel paymentTransaction");
                        Yii::warning($model->policyOrder->paymentTransaction->errors);
                    }
                }
                return json_encode($response);
            }
        }
        Yii::$app->session->addFlash('error', Yii::t('policy','Order not found'));
        return $this->goBack();
    }

}
