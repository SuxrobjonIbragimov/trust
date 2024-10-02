<?php

namespace frontend\modules\policy\controllers;

use backend\models\page\Pages;
use backend\modules\handbook\models\HandbookFondRegion;
use backend\modules\policy\models\PolicyOsgo;
use common\models\Settings;
use Exception;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OpoController extends Controller
{
    /**
     * OpoController constructor.
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        if (is_mobile_app()) {
            $this->layout = '@frontend/themes/v1/layouts/webview';
        }
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['form', 'approve', 'confirm', ],
//                'rules' => [
//                    [
//                        'actions' => ['form', 'approve', 'confirm', ],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id == 'my-method') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    /**
     * Lists all PolicyOsgo models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['opo/form'], 301);
    }

    /**
     * Creates a new PolicyOsgo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCalculate($code=null)
    {
        return $this->redirect(['opo/form'], 301);
    }

    public function actionForm($h=null, $code=null)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();

        $model = null;

        $modelPage = $this->findPage('opo_anketa');

        if (!empty($h)) {
            $id_model = _model_decrypt($h);
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            $model = $modelClassName::findOne($id_model['id']);
            /* @var $model PolicyOsgo */
            if (!empty($model) && empty($model->ins_anketa_id)) {
                $model->scenario = PolicyOsgo::SCENARIO_SITE_STEP_OPO_FORM;
                $model->product_type = PolicyOsgo::PRODUCT_TYPE_OSGOR;
                $model->start_date = date('d.m.Y', strtotime($model->start_date));
                $model->end_date = date('d.m.Y', strtotime($model->end_date));
            } elseif (!empty($model->org_annual_salary)) {
                $model->org_annual_salary = round($model->org_annual_salary);
            }
        }

        if (empty($model)) {
            $model = new PolicyOsgo(['scenario' => PolicyOsgo::SCENARIO_SITE_STEP_OPO_FORM, 'product_type' => PolicyOsgo::PRODUCT_TYPE_OPO]);
            if ($session->has('model_osgor_calc')) {
                $session_model = $session->get('model_osgor_calc');
                if (!empty($session_model)) {
                    $attrs = json_decode($session_model);
                    $model->attributes = (array)$attrs;

                    $cur_date = date('d.m.Y');
                    if (strtotime($model->start_date) < strtotime($cur_date)) {
                        $session->remove('model_osgor_calc');
                    }
                    $model->_ins_amount = PolicyOsgo::INSURANCE_SUM;
                    $model->setAppPhone();

                }
            } else {
                $model->_loadDefaultValues();
            }
            $model->product_type = PolicyOsgo::PRODUCT_TYPE_OPO;

        }
        if ($session->has('model_opo_okonx_list')) {
            $session_model_osgor_okonx_list = $session->get('model_opo_okonx_list');
            if (!empty($session_model_osgor_okonx_list)) {
                $attrs = $session_model_osgor_okonx_list;
                $model->okonx_list = (array)$attrs;
            }
        }

        if (!empty(Yii::$app->session->get('source_id'))) {
            $model->source = Yii::$app->session->get('source_id');
        }
        $model->calculatePremPriceOsgor();
        if (!empty($model->app_birthday)) {
            $model->app_birthday = date('d.m.Y', strtotime($model->app_birthday));
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->product_type = PolicyOsgo::PRODUCT_TYPE_OPO;
            $model->calculatePremPriceOsgor();
            // validate all models
            $valid = $model->validate();

            $error_message = Yii::t('policy','Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
            if ($valid) {
                $model->setRegion();
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $model->setEndDate();
                    $model->start_date = date('Y-m-d', strtotime($model->start_date));
                    $model->app_phone = clear_phone_full($model->app_phone);
                    if ($flag = $model->save(false)) {
                        $transaction->commit();
                        unset($session['model_osgor_calc'],$session['model_travel_ins_amount']);
                        return $this->redirect(['osgor/approve', 'h' => _model_encrypt($model)], 301);
                    } else {
                        $session->addFlash('error', $model->errors);
                        dd($model->errors);
//                        return $this->redirect(['/payment/payment/index', 'h' => _model_encrypt($model)], 301);
                    }
                } catch (Exception $e) {
                    $title = 'Osgor model exception';
                    _send_error($title,$e->getMessage());
                    Yii::warning("\n\nOSGOR ERRORS Exception\n\n");
                    Yii::warning($e);
                    $session->addFlash('error', $error_message);
                    $transaction->rollBack();
                }
            } else {
                Yii::warning("\n\nOSGOR ERRORS AFTER TRANSACTION\n\n");
                Yii::warning($model->errors);
                $session->addFlash('error', _generate_error($model->errors));
//                _send_error('OSGOR FORM SAVE VALIDATION TRY',json_encode($model->errors));

            }
        } elseif ($model->load(Yii::$app->request->post())) {
            Yii::warning("\n\nOSGOR ERRORS AFTER POST\n\n");
            Yii::warning($model->errors);
            $session->addFlash('error', _generate_error($model->errors));
//            _send_error('OSGOR FORM SAVE VALIDATION',json_encode($model->errors));

        }
        return $this->render('fill_from', [
            'model' => $model,
            'modelPage' => $modelPage,
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
        ]);
    }


    public function actionGetHandbookDistrict()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = [];

        if ($post = Yii::$app->request->post()) {
            if (($_id = (int)$post['depdrop_all_params']['policyosgo-owner_region']) > 0) {
                if (!empty($out = HandbookFondRegion::_getItemsListByInsParam($_id, false))) {
                    return ['output'=>$out, 'selected'=>''];
                }
            }
        }

        return ['output'=>'', 'selected'=>''];

    }


    /**
     * Finds the PolicyOsgo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PolicyOsgo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PolicyOsgo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('policy','The requested page does not exist.'));
    }

    /**
     * @param $h
     * @return PolicyOsgo|mixed
     * @throws NotFoundHttpException
     */
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
        return null;
    }
}