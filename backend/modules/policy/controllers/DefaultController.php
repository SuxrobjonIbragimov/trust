<?php

namespace frontend\modules\policy\controllers;

use app\common\library\payment\models\PaymentTransaction;
use backend\modules\policy\models\PolicyTravel;
use kartik\mpdf\Pdf;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `policy` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param $h
     * @return mixed|\yii\web\Response
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDownload($h)
    {
        $id_model = _model_decrypt($h);
        if (!empty($id_model['id']) && !empty($id_model['formName'])) {
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            $model = $modelClassName::findOne($id_model['id']);

            /* @var $model PolicyTravel */
            if (!empty($model->policyOrder) && $model->policyOrder->payment_status == PaymentTransaction::STATUS_PAYMENT_PAID) {

                $type = mb_strtolower($model->policyOrder->getProductName());
                // get your HTML raw content without any layouts or scripts
                $content = $this->renderPartial('_pdf_generate_'.$type,[
                    'model' => $model,
                    'type' => $type,
                ]);

                $cssList = [
                    'travel' => [
                        'cssFile' => '@webroot/media/css/pdf_travel.css',
                        'cssInline' => '*,body{font-family:thsarabun;font-size:14pt}',
                    ],
                    'kasko' => [
                        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                        'cssInline' => $this->renderPartial('_css_'.$type,[
                        ]),
                    ],
                ];

//                return $content;
                $title = Yii::t('policy','Download policy - {policy}',['policy' => $model->fullPolicyNumber]);

                // setup kartik\mpdf\Pdf component
                $pdf = new Pdf([
                    // set to use core fonts only
                    'mode' => Pdf::MODE_UTF8,
                    // A4 paper format
                    'format' => Pdf::FORMAT_A4,
                    // portrait orientation
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    // stream to browser inline
                    'destination' => Pdf::DEST_BROWSER,

                    'marginLeft' => ($type == 'travel') ? 0 : null,
                    'marginRight' => ($type == 'travel') ? 0 : null,
                    'marginTop' => ($type == 'travel') ? 0 : null,
                    'marginBottom' => ($type == 'travel') ? 0 : null,

                    'filename' => $model->fullPolicyNumber . '.pdf',
                    // your html content input
                    'content' => $content,
                    // format content from your own css file if needed or use the
                    // enhanced bootstrap css built by Krajee for mPDF formatting
                    'cssFile' => !empty($cssList[$type]['cssFile']) ? $cssList[$type]['cssFile'] : null,
                    'cssInline' => !empty($cssList[$type]['cssInline']) ? $cssList[$type]['cssInline'] : null,
                    // set mPDF properties on the fly
                    'options' => ['title' => $title],
                    // call mPDF methods on the fly
                    'methods' => [
//                        'SetHeader'=>['Krajee Report Header'],
//                        'SetFooter'=>['{PAGENO}'],
                    ]
                ]);


                /**
                 * Add new custom font in Mpdf library
                 * link: https://mpdf.github.io/fonts-languages/fonts-in-mpdf-7-x.html
                 */
                $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
                $fontData = $defaultFontConfig['fontdata'];

                /**
                 * We set more options as showing in "vendors/kartik-v/yii2-mpdf/src/Pdf.php/Pdf/options" method
                 * What we do, we merge the options array to the existing one.
                 */
                $pdf->options = array_merge($pdf->options , [
                    'fontDir' => array_merge($fontDirs, [ Yii::$app->basePath . '/web/media/fonts']),  // make sure you refer the right physical path
                    'fontdata' => array_merge($fontData, [
                        'thsarabun' => [
                            'R' => 'Noah-Regular.ttf',
                            'I' => 'Noah-Medium.ttf',
                            'B' => 'Noah-Bold.ttf',
                        ]
                    ])
                ]);
                // return the pdf output as per the destination setting
                return $pdf->render();
            } else {
                throw new BadRequestHttpException(Yii::t('policy','Policy not payed yet'));
            }
        }

        throw new NotFoundHttpException(Yii::t('policy','Policy not found'));
    }

    public function actionHash($h = null,$decode=true, $id = null, $name = null)
    {
        if (Yii::$app->user->can('admin')) {
            $result = null;
            if ($h && $decode) {
                $result = _model_decrypt($h);
            } elseif ($id && $name) {
                $param['id'] = $id;
                $param['formName'] = $name;
                $result = _model_encrypt(null,$param);
            }
            dd($result);
        }
        return $this->goHome();
    }

    public function actionTmp(array $param = [])
    {
        if (empty($param)) {
            return $this->redirect(['default/tmp',"param[id]" => 29, "param[formName]" => 'PolicyTravel']);
        } else {
            d($param);
            $result = _model_encrypt(null,$param);
            dd($result);
        }
    }
}
