<?php

namespace frontend\modules\payment\controllers;

use backend\modules\policy\models\PolicyOsgo;
use common\library\payment\models\PaymentTransaction;
use backend\modules\policy\models\PolicyTravel;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `payment` module
 */
class PaymentController extends Controller
{

    /**
     * @param $h
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionIndex($h)
    {
//        Yii::warning("\n\n\nHASh\n");
//        Yii::warning("\n$h\n");
        $id_model = _model_decrypt($h);
//        Yii::warning("\n\n\n_decrypt\n");
//        Yii::warning($id_model);
        if (!empty($id_model['id']) && !empty($id_model['formName'])) {
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            /* @var $modelClassName PolicyOsgo */
            /* @var $model PolicyOsgo */
            $model = $modelClassName::findOne($id_model['id']);
        }
        if (!empty($model)) {
            if (!empty($model->policyOrder->paymentTransaction) && $model->policyOrder->paymentTransaction->status == PaymentTransaction::STATUS_PAYMENT_PAID) {
                throw new BadRequestHttpException(Yii::t('policy','Policy already paid'));
            } elseif (!empty($model->policyOrder->paymentTransaction) && ( $model->policyOrder->paymentTransaction->status == PaymentTransaction::STATUS_PAYMENT_CANCELLED_AFTER_COMPLETE || $model->policyOrder->paymentTransaction->status == PaymentTransaction::STATUS_PAYMENT_CANCELED )) {
                throw new BadRequestHttpException(Yii::t('policy','Payment cancelled'));
            }
//            Yii::warning($model->attributes);
            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();
            $program = $session->get('model_travel_program');

            $payments = PaymentTransaction::getPaymentTypeImgArray();
            $payment_type_list = [];
            foreach ($payments as $type => $payment) {
                switch ($type) {
                    case PaymentTransaction::PAYMENT_TYPE_CLICK:
                        $payment_type_list[$type] = [
                            'name' => $payment,
                            'url' => '/payment/click/pay',
                            'h' => $h
                        ];
                        break;
                    case PaymentTransaction::PAYMENT_TYPE_PAYME:
                        $payment_type_list[$type] = [
                            'name' => $payment,
                            'url' => '/payment/payme/pay',
                            'h' => $h
                        ];
                        break;
                    case PaymentTransaction::PAYMENT_TYPE_APELSIN:
                        $payment_type_list[$type] = [
                            'name' => $payment,
                            'url' => '/payment/apelsin/pay',
                            'h' => $h
                        ];
                        break;
                }
            }
            return $this->render('index',[
                'model' => $model,
                'program' => $program,
                'payment_type_list' => $payment_type_list,
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('policy','Policy not found'));
        }
    }
}
