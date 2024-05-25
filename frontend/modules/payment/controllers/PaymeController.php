<?php

namespace frontend\modules\payment\controllers;

use Yii;
use common\library\paycom\Paycom\PaycomSubscribeForm;
use common\library\paycom\Paycom\PaycomApplication;
use common\library\payment\models\PaymentTransaction;
use backend\modules\policy\models\PolicyTravel;
use yii\web\Controller;
use yii\web\Response;

class PaymeController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id == 'index') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = file_get_contents('php://input');
        $request = json_decode($request, true);
        $action = 'payme_billing';
        $paycom = new PaycomApplication();
        $reply_params = $paycom->run();
        $params = [
            'method' => $action,
            'request' => $request,
            'post' => Yii::$app->request->post(),
            'get' => Yii::$app->request->get(),
            'response' => $reply_params,
        ];

        set_history($params, $reply_params, 'payme_merchant_reply');
        return $reply_params;
    }

    public function actionPay($h)
    {
        $id_model = _model_decrypt($h);
        if (!empty($id_model['id']) && !empty($id_model['formName'])) {
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            $model = $modelClassName::findOne($id_model['id']);
            /* @var $model PolicyTravel */
            if (!empty($model->policyOrder)) {
                $params = [
                    'order_id' => $model->ins_anketa_id,
                    'product_name' => $model->policyOrder->productName,
                    'amount' => $model->policyOrder->total_amount*100,
                    'h' => $h,
                    'osgo' => (!empty($id_model['formName']) && $id_model['formName'] == "PolicyOsgo") ? 'osgo' : null,
                ];
                $paymentModel = new PaycomSubscribeForm(['scenario' => PaycomSubscribeForm::SCENARIO_CARD_INFO, 'step' => PaycomSubscribeForm::STEP_CARD_INFO]);
                if (($paylink = $paymentModel->generatePayLink($params))) {
//                    dd($paylink);
                    $model->policyOrder->payment_status = PaymentTransaction::STATUS_PAYMENT_WAIT;
                    if (!$model->policyOrder->save()) {
                        _send_error('Policy Order not saved in payme pay',json_encode($model->policyOrder->errors,JSON_UNESCAPED_UNICODE));
                    }
                    return $this->redirect($paylink);
                }
                if ($paymentModel->load(Yii::$app->request->post())) {
                    if ($paymentModel->step == PaycomSubscribeForm::STEP_VERIFICATION) {
                        $paymentModel->scenario = PaycomSubscribeForm::SCENARIO_VERIFICATION;
                    }
                    switch ($paymentModel->step) {
                        case PaycomSubscribeForm::STEP_CARD_INFO:
                            if ($paymentModel->createPayment($model->policyOrder)) {
                                if ($paymentModel->getVerifyCode($model->policyOrder)) {
                                    $paymentModel->step = PaycomSubscribeForm::STEP_VERIFICATION;
                                    $model->policyOrder->payment_status = PaymentTransaction::STATUS_PAYMENT_WAIT;
                                }
                            }
                            break;
                        case PaycomSubscribeForm::STEP_VERIFICATION:
                            if ($paymentModel->verifyPayment($model->policyOrder)) {
                                if ($paymentModel->receiptsCreate($model->policyOrder)) {
                                    if ($paymentModel->receiptsPay($model->policyOrder)) {
                                        if ($paymentModel->receiptsPay($model->policyOrder)) {
                                            $paymentModel->step = PaycomSubscribeForm::STEP_VERIFICATION;
                                        }
                                        return $this->redirect(['/policy/check/status', 'h' => $h]);
                                    }
                                }
                            }
                            break;
                    }
                }
                return $this->render('pay',[
                    'model' => $model,
                    'paymentModel' => $paymentModel,
                    'h' => $h,
                ]);
            }
        }
        Yii::$app->session->addFlash('error', Yii::t('payment','Order not found'));
        return $this->goBack();
    }
}
