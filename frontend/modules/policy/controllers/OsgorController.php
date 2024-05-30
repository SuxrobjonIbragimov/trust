<?php

namespace frontend\modules\policy\controllers;

use backend\models\page\Pages;
use backend\modules\handbook\models\HandbookFondRegion;
use backend\modules\policy\models\PolicyOsgo;
use common\models\Settings;
use Exception;
use frontend\models\PaymentForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OsgoController implements the CRUD actions for PolicyOsgo model.
 */
class OsgorController extends Controller
{
    /**
     * OsgoController constructor.
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
        return $this->redirect(['osgor/form'], 301);
    }

    /**
     * Creates a new PolicyOsgo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCalculate($code=null)
    {
        return $this->redirect(['osgor/form'], 301);

    }

    public function actionForm($h=null, $code=null)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();

        $model = null;

        $modelPage = $this->findPage('osgor_anketa');

        if (!empty($h)) {
            $id_model = _model_decrypt($h);
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            $model = $modelClassName::findOne($id_model['id']);
            /* @var $model PolicyOsgo */
            if (!empty($model) && empty($model->ins_anketa_id)) {
                $model->scenario = PolicyOsgo::SCENARIO_SITE_STEP_OSGOR_FORM;
                $model->product_type = PolicyOsgo::PRODUCT_TYPE_OSGOR;
                $model->start_date = date('d.m.Y', strtotime($model->start_date));
                $model->end_date = date('d.m.Y', strtotime($model->end_date));
            } elseif (!empty($model->org_annual_salary)) {
                $model->org_annual_salary = round($model->org_annual_salary);
            }
        }

        if (empty($model)) {
            $model = new PolicyOsgo(['scenario' => PolicyOsgo::SCENARIO_SITE_STEP_OSGOR_FORM, 'product_type' => PolicyOsgo::PRODUCT_TYPE_OSGOR]);
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
            $model->product_type = PolicyOsgo::PRODUCT_TYPE_OSGOR;

        }
        if ($session->has('model_osgor_okonx_list')) {
            $session_model_osgor_okonx_list = $session->get('model_osgor_okonx_list');
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
        if (!empty($model->start_date)) {
            $model->setEndDate();
            $model->start_date = date('d.m.Y', strtotime($model->start_date));
            $model->end_date = date('d.m.Y', strtotime($model->end_date));
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->product_type = PolicyOsgo::PRODUCT_TYPE_OSGOR;
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

        if (!empty($model->app_birthday)) {
            $model->app_birthday = date('d.m.Y', strtotime($model->app_birthday));
        }
        if (!empty($model->start_date)) {
            $model->start_date = date('d.m.Y', strtotime($model->start_date));
        }
        return $this->render('fill_from', [
            'model' => $model,
            'modelPage' => $modelPage,
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
        ]);
    }

    /**
     * Displays a single PolicyOsgo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionApprove($h=null)
    {
        if (empty($h)) {
            return $this->redirect(['osgor/form'], 301);
        }
        $model = $this->findModelHash($h);
        $modelPage = $this->findPage('osgor_approve');
        if (!empty($model->ins_anketa_id)) {

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            $session->remove('model_osgor_calc');
            return $this->redirect(['osgor/form'], 301);
        }
        return $this->render('approve', [
            'model' => $this->findModelHash($h),
            'modelPage' => $modelPage,
        ]);
    }

    public function actionConfirm($h, $p=null)
    {
        $model = $this->findModelHash($h);
        $model->uuid_sys = gen_uuid();
        if ($model->saveInsAnketaOsgor()) {

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            $session->remove('model_travel_program');
            $session->remove('model_osgor_calc');
            $session->remove('model_osgor_owner');
            $session->remove('session_osgor_applicant');

            return $this->redirect(['/payment/payment/index', 'h' => _model_encrypt($model), 'p' => $p], 301);
        } else {
            return $this->redirect(['osgor/form', 'h' => _model_encrypt($model)], 301);
        }
    }


    /**
     * @return array|int[]
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionCalculatePrice()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $model = new PolicyOsgo(['scenario' => PolicyOsgo::SCENARIO_SITE_STEP_OSGOR_FORM, "product_type" => PolicyOsgo::PRODUCT_TYPE_OSGOR]);
        $model->_loadDefaultValues();
        if ($model->load(Yii::$app->request->post())) {
            $model->owner_inn = preg_replace('/\D/', '', $model->owner_inn);
            $model->org_annual_salary = preg_replace('/\D/', '', $model->org_annual_salary);
            $out = $model->calculatePremPriceOsgor();
        }
        return $out;

    }

    /**
     * @return array
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionGetTinData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $out['ERROR'] = 0;
        if (Yii::$app->request->post() && ($post = Yii::$app->request->post())) {
            $items = !empty($post['tin']) ? $post['tin'] : 0;
            $items = preg_replace('/\D/', '', $items);
            $didox_data = PolicyOsgo::_getDidoxTinData($items);

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();
            if (!empty($didox_data['oked'])) {
                $oked_data = PolicyOsgo::_getOkonxData($didox_data['oked']);
                if (!empty($oked_data) && is_array($oked_data) && empty($oked_data['ERROR'])) {
                    foreach ($oked_data as $key => $value) {
                        if (!empty($value['NAME'])) {
                            $out['okonx_list'][$value['ID']] = $value['NAME'];
                        }
                    }
                    $session['model_osgor_okonx_list'] = $out['okonx_list'];
                } else {
                    $out = $oked_data;
                }
            }

            if (!empty($didox_data['shortName']) || !empty($didox_data['shortname'])) {
                $out['ORGNAME'] = !empty($didox_data['shortName']) ? trim(mb_strtoupper($didox_data['shortName'])) : trim(mb_strtoupper($didox_data['shortname'])) ;
            }
            if (!empty($didox_data['address'])) {
                $out['ADDRESS'] = trim(mb_strtoupper($didox_data['address']));
            }
            if (!empty($didox_data['oked'])) {
                $out['OKED'] = trim(mb_strtoupper($didox_data['oked']));
            }
            if (!empty($didox_data['org_okonx'])) {
                $out['ORG_OKONX'] = trim(mb_strtoupper($didox_data['org_okonx']));
            }
            if (!empty($didox_data['org_okonx_coef'])) {
                $out['ORG_OKONX_COEF'] = trim(mb_strtoupper($didox_data['org_okonx_coef']));
            }

            if (!empty($out) && is_array($out) && empty($out['ERROR']) && $session->has('model_osgor_owner')) {
                $model_osgor_owner = [
                    'owner_orgname' => !empty($out['ORGNAME']) ? $out['ORGNAME'] : null,
                    'owner_oked' => !empty($out['OKED']) ? $out['OKED'] : null,
                    'owner_address' => !empty($out['ADDRESS']) ? $out['ADDRESS'] : null,
                    'org_okonx' => !empty($out['ORG_OKONX']) ? $out['ORG_OKONX'] : null,
                    'org_okonx_coef' => !empty($out['ORG_OKONX_COEF']) ? $out['ORG_OKONX_COEF'] : null,
                ];
                $session['model_osgor_owner'] = $model_osgor_owner;
            }
        }
        return $out;

    }

    public function actionGetOkonxData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $out['ERROR'] = 0;
        if (Yii::$app->request->post() && ($post = Yii::$app->request->post())) {
            $items = !empty($post['oked']) ? $post['oked'] : 0;
            $items = preg_replace('/\D/', '', $items);
            $oked_data = PolicyOsgo::_getOkonxData(['oked' => $items]);

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            if (!empty($oked_data) && is_array($oked_data) && empty($oked_data['ERROR'])) {
                foreach ($oked_data as $key => $value) {
                    if (!empty($value['NAME'])) {
                        $out['okonx_list'][$value['ID']] = $value['NAME'];
                    }
                }
                $session['model_osgor_okonx_list'] = $out['okonx_list'];
            } else {
                $out = $oked_data;
            }

            if (!empty($didox_data['shortName']) || !empty($didox_data['shortname'])) {
                $out['ORGNAME'] = !empty($didox_data['shortName']) ? trim(mb_strtoupper($didox_data['shortName'])) : trim(mb_strtoupper($didox_data['shortname'])) ;
            }
            if (!empty($didox_data['address'])) {
                $out['ADDRESS'] = trim(mb_strtoupper($didox_data['address']));
            }
            if (!empty($didox_data['oked'])) {
                $out['OKED'] = trim(mb_strtoupper($didox_data['oked']));
            }
            if (!empty($didox_data['org_okonx'])) {
                $out['ORG_OKONX'] = trim(mb_strtoupper($didox_data['org_okonx']));
            }
            if (!empty($didox_data['org_okonx_coef'])) {
                $out['ORG_OKONX_COEF'] = trim(mb_strtoupper($didox_data['org_okonx_coef']));
            }

            if (!empty($out) && is_array($out) && empty($out['ERROR']) && $session->has('model_osgor_owner')) {
                $model_osgor_owner = [
                    'owner_orgname' => !empty($out['ORGNAME']) ? $out['ORGNAME'] : null,
                    'owner_oked' => !empty($out['OKED']) ? $out['OKED'] : null,
                    'owner_address' => !empty($out['ADDRESS']) ? $out['ADDRESS'] : null,
                    'org_okonx' => !empty($out['ORG_OKONX']) ? $out['ORG_OKONX'] : null,
                    'org_okonx_coef' => !empty($out['ORG_OKONX_COEF']) ? $out['ORG_OKONX_COEF'] : null,
                ];
                $session['model_osgor_owner'] = $model_osgor_owner;
            }
        }
        return $out;

    }

    /**
     * @return array|string[]
     */
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
    protected function findModelHash($h)
    {
        $id_model = _model_decrypt($h);

        if (!empty($id_model['id']) && !empty($id_model['formName'])) {
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            if (($model = $modelClassName::findOne($id_model['id'])) !== null) {
                return $model;
            }
        }

        throw new NotFoundHttpException(Yii::t('policy','The requested page does not exist.'));
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
        return null;
    }

}
