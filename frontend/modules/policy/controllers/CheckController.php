<?php

namespace frontend\modules\policy\controllers;

use backend\modules\policy\models\CheckPolicy;
use backend\modules\policy\models\PolicyOsgo;
use common\library\payment\models\PaymentTransaction;
use backend\modules\policy\models\HandBookIns;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `policy` module
 */
class CheckController extends Controller
{

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($sery=null,$number=null)
    {
        $model = new CheckPolicy([
            'scenario' => CheckPolicy::SCENARIO_SITE,
        ]);
        if (!empty($sery) && !empty($number)) {
            $model = new CheckPolicy([
                'policy_series' => $sery,
                'policy_number' => Html::encode($number),
            ]);
            $model->policy_series = $sery;
            $model->policy_number = Html::encode($number);
        }
        $response = null;
        if (($model->load(Yii::$app->request->post()) || ( !empty($model->policy_series) && !empty($model->policy_number) )) && $model->validate()) {
            $response = $model->getPolicyInfo();
            if (isset($response['RESULT']) && $response['RESULT'] > 0) {
                Yii::$app->session->addFlash('error',Yii::t('policy','Policy not found'));
            }
        }
        return $this->render('index',[
            'model' => $model,
            'response' => $response,
        ]);
    }

    public function actionStatus($h)
    {
        $id_model = _model_decrypt($h);
        if (!empty($id_model['id']) && !empty($id_model['formName'])) {
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            $model = $modelClassName::findOne($id_model['id']);
            /* @var $model PolicyOsgo */
            if (!empty($model->policyOrder)) {
                return $this->render('status',[
                    'model' => $model,
                    'h' => $h,
                ]);
            }
        }
        Yii::$app->session->addFlash('error', Yii::t('policy','Order not found'));
        return $this->goBack();
    }

    public function actionTmp()
    {
        $request = file_get_contents('php://input');
        $request = json_decode($request, true);
        $params = [
            'php_input' => $request,
            'post' => Yii::$app->request->post(),
            'get' => Yii::$app->request->get(),
        ];
        _send_error('actionTmp', json_encode($params));
        return json_encode($params);
    }

    public function actionCheckPayment($h=null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = [
            'status' => false,
            'message' => Yii::t('frontend','Transaction not found'),
        ];

        if (Yii::$app->request->post() || Yii::$app->request->getRawBody()) {
            $post = Yii::$app->request->post();
            if (empty($post)) {
                $post = json_decode(Yii::$app->request->getRawBody(), true);
            }
            $_h = !empty($post['h']) ? $post['h'] : '';
            $id_model = _model_decrypt($_h);
            if (!empty($id_model['id']) && !empty($id_model['formName'])) {
                $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
                $model = $modelClassName::findOne($id_model['id']);
                /* @var $model PolicyOsgo */
                if (!empty($model->policyOrder)) {
                    if ($id_model['formName'] == "PolicyOsgo" || $id_model['formName'] == "PolicyTravel") {
                        $handBookService = new HandBookIns();
                        $handBookService->setBaseUrl(EBASE_URL_INS_TR);
                        $handBookService->setLogin(TR_LOGIN);
                        $handBookService->setPassword(TR_PASSWORD);
                        $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
                        $handBookService->setMethod(HandBookIns::METHOD_CHECK_ANKETA_STATUS);

                        $handBookService->setParams([
                            'anketa_id' => !empty($model->ins_anketa_id) ? $model->ins_anketa_id : null,
                        ]);

                        $data = $handBookService->sendRequestIns();
                        if (!empty($data) && is_array($data) && empty($data['result'])) {
                            $data = array_change_key_case($data, CASE_UPPER);
                            if (!empty($data['POLICY_SERY']) && !empty($data['POLICY_NUMBER'])) {
                                $model->policy_series = trim($data['POLICY_SERY']);
                                $model->policy_number = trim($data['POLICY_NUMBER']);
                                $model->ins_policy_id = isset($data['POLICY_ID']) && !empty($data['POLICY_ID']) ? trim($data['POLICY_ID']) : null;
                            } elseif(!empty($data['STATUS_PAYMENT']) && $data['STATUS_PAYMENT'] == PaymentTransaction::STATUS_PAYMENT_PAID && empty($data['POLICY_SERY'])) {
                                $title = "CheckController actionCheckPayment policy number is EMPTY";
                                Yii::warning("\n\n\n{$title}");
                                Yii::warning($data);
                                _send_error($title,json_encode($data, JSON_UNESCAPED_UNICODE));
                            }
                            $model->ins_log = json_encode($data);
                            if (!$model->save(false)) {
                                $title = "CheckController actionCheckPayment modelPolicy policy number not saved";
                                Yii::warning("\n\n\n{$title}");
                                Yii::warning($model->errors);
                                _send_error($title,json_encode($model->errors, JSON_UNESCAPED_UNICODE));
                            }
                            if (!empty($data['STATUS_PAYMENT']) && ($data['STATUS_PAYMENT'] != $model->policyOrder->payment_status)) {
                                $model->policyOrder->payment_status = $data['STATUS_PAYMENT'];
                                if (!empty($data['PAYMENT_TYPE'])) {
                                    $model->policyOrder->payment_type = PaymentTransaction::getPaymentTypeFromInsType($data['PAYMENT_TYPE']);
                                }
                                if (!$model->policyOrder->save()) {
                                    $title = "CheckController actionCheckPayment order status not saved";
                                    Yii::warning("\n\n\n{$title}");
                                    Yii::warning($model->policyOrder->errors);
                                    _send_error($title,json_encode($model->policyOrder->errors, JSON_UNESCAPED_UNICODE));
                                }
                            }
                            if ($model->policyOrder->payment_status == PaymentTransaction::STATUS_PAYMENT_PAID) {
                                $response['status'] = true;
                                $response['message'] = Yii::t('frontend','Transaction success');
                            }
                        } elseif (!empty($data['result'])) {
                            $response['ERROR'] = $data['result'];
                            $response['ERROR_MESSAGE'] = !empty($data['result_message']) ? $data['result_message'] : Yii::t('policy','Data not found by pinfl');
                        } else {
                            $title = Yii::t('policy','Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                            _send_error("CheckController actionCheckPayment - ".$title, json_encode($data,JSON_UNESCAPED_UNICODE));
                            $response['ERROR'] = 422;
                            $response['ERROR_MESSAGE'] = $title;
                        }

                    }
                }
            }

        }

        return $response;
    }
}
