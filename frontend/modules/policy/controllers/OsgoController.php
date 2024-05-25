<?php

namespace frontend\modules\policy\controllers;

use backend\models\page\Pages;
use backend\modules\handbook\models\HandbookFondRegion;
use backend\modules\policy\models\HandBookIns;
use backend\modules\policy\models\PolicyOsgo;
use backend\modules\policy\models\PolicyOsgoDriver;
use common\base\Model;
use common\models\Settings;
use Exception;
use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OsgoController implements the CRUD actions for PolicyOsgo model.
 */
class OsgoController extends Controller
{
    /**
     * TravelController constructor.
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        if (is_mobile_app()) {
            $this->layout = '@app/views/layouts/webview';
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
        return $this->redirect(['osgo/calculate'], 301);
    }

    /**
     * Creates a new PolicyOsgo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCalculate()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();

        $session->remove('model_osgo_calc');
        $session->remove('model_osgo_drivers');
        return $this->redirect(['osgo/form'], 301);
    }

    public function actionForm($h=null)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();

        $model = null;
        $modelDrivers = null;

        $modelPage = $this->findPage('osgo_anketa');

        if (!empty($h)) {
            $id_model = _model_decrypt($h);
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            $model = $modelClassName::findOne($id_model['id']);
            /* @var $model PolicyOsgo */
            if (!empty($model) && empty($model->ins_anketa_id)) {
                $model->scenario = PolicyOsgo::SCENARIO_SITE_STEP_FORM;
                $model->start_date = date('d.m.Y', strtotime($model->start_date));
                $model->end_date = date('d.m.Y', strtotime($model->end_date));

                /* @var $modelDrivers_ PolicyOsgoDriver */
                /* @var $driver PolicyOsgoDriver */
                $modelDrivers_ = $model->policyOsgoDrivers;
                if (!empty($modelDrivers_)) {
                    foreach ($modelDrivers_ as $driver) {
                        $driver->scenario = PolicyOsgoDriver::SCENARIO_SITE_STEP_FORM;
                        $driver->driver_limit = $model->driver_limit_id;
                        $driver->_full_name = $driver->fullName;
                        $driver->license_issue_date = date('d.m.Y', strtotime($driver->license_issue_date));
                        $modelDrivers[] = $driver;
                    }
                }
            }
        }

        if (empty($model)) {
            $model = new PolicyOsgo(['scenario' => PolicyOsgo::SCENARIO_SITE_STEP_FORM]);
            if ($session->has('model_osgo_calc')) {
                $session_model = $session->get('model_osgo_calc');
                if (!empty($session_model)) {
                    $attrs = json_decode($session_model);
                    $model->attributes = (array)$attrs;

                    $cur_date = date('d.m.Y');
                    if (strtotime($model->start_date) < strtotime($cur_date)) {
                        $session->remove('model_osgo_calc');
//                        return $this->redirect(['osgo/calculate'], 301);
                    }
                    $model->_ins_amount = PolicyOsgo::INSURANCE_SUM;
                    $model->setAppPhone();
                    $model->owner_is_driver = ($model->driver_limit_id == PolicyOsgo::DRIVER_LIMITED);

                    $modelDrivers[] = new PolicyOsgoDriver(['scenario' => PolicyOsgoDriver::SCENARIO_SITE_STEP_FORM,'driver_limit' => $model->driver_limit_id]);
                }
            } else {
                $model->_loadDefaultValues();
                $modelDrivers[] = new PolicyOsgoDriver(['scenario' => PolicyOsgoDriver::SCENARIO_SITE_STEP_FORM,'driver_limit' => $model->driver_limit_id]);
            }
        }
//        if (is_null($model->vehicle_type_id) || is_null($model->start_date) || is_null($model->end_date) || is_null($model->period_id) || is_null($model->region_id)) {
//            return $this->redirect(['osgo/calculate'], 301);
//        }

        $model->calculatePremPrice();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (($model->owner_is_pensioner != PolicyOsgo::DEFAULT_OWNER_IS_PENSIONER) && (!$model->owner_is_applicant || $model->owner_fy != PolicyOsgo::LEGAL_TYPE_FIZ)) {
                $model->owner_is_pensioner = PolicyOsgo::DEFAULT_OWNER_IS_PENSIONER;
            }
            $model->calculatePremPrice();

            if (!$model->isNewRecord) {
                if (empty($modelDrivers)) {
                    $modelDrivers = Model::createMultiple(PolicyOsgoDriver::classname());
                }
                $oldIDs = !empty($modelDrivers) ? ArrayHelper::map($modelDrivers, 'id', 'id') : [];
                $modelDrivers = Model::createMultiple(PolicyOsgoDriver::classname(), $modelDrivers);
                Model::loadMultiple($modelDrivers, Yii::$app->request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDrivers, 'id', 'id')));
            } else {
                $oldIDs = null;
                $modelDrivers = Model::createMultiple(PolicyOsgoDriver::classname());
                Model::loadMultiple($modelDrivers, Yii::$app->request->post());
                $deletedIDs = null;
            }

            if ($session->has('model_osgo_vehicle') && !empty($session['model_osgo_vehicle'])) {
                $attributes = array_filter($session['model_osgo_vehicle'],function ($data){
                    return $data !== null;
                });
                $model->attributes = $attributes;
            }
            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelDrivers) && $valid;

            if ($model->_isEnabledOsgoLimited()) {
                Yii::warning("_isEnabledOsgoLimited");
                Yii::warning($model->_isEnabledOsgoLimited());
                $session->addFlash('error', Yii::t('policy', HandBookIns::getFondError(HandBookIns::FOND_ERROR_503)));
            } elseif ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $model->setEndDate();
                    $model->owner_birthday = date('Y-m-d', strtotime($model->owner_birthday));
                    $model->start_date = date('Y-m-d', strtotime($model->start_date));
                    $model->app_phone = clear_phone_full($model->app_phone);
                    if ($model->owner_is_applicant && $model->owner_fy == PolicyOsgo::LEGAL_TYPE_FIZ) {
                        $model->app_first_name = mb_strtoupper($model->owner_first_name);
                        $model->app_last_name = mb_strtoupper($model->owner_last_name);
                        $model->app_middle_name = mb_strtoupper($model->owner_middle_name);
                        $model->app_pass_sery = mb_strtoupper($model->owner_pass_sery);
                        $model->app_pass_num = ($model->owner_pass_num);
                        $model->app_pinfl = ($model->owner_pinfl);
                        $model->app_pass_issued_by = ($model->owner_pass_issued_by);
                        $model->app_pass_issue_date = ($model->owner_pass_issue_date);
                        $model->app_birthday = date('Y-m-d', strtotime($model->owner_birthday));
                        $model->app_region = ($model->owner_region);
                        $model->app_district = ($model->owner_district);
                    } else {
                        $model->app_first_name = mb_strtoupper($model->app_first_name);
                        $model->app_last_name = mb_strtoupper($model->app_last_name);
                        $model->app_middle_name = mb_strtoupper($model->app_middle_name);
                        $model->app_pass_sery = mb_strtoupper($model->app_pass_sery);
                        $model->app_birthday = date('Y-m-d', strtotime($model->app_birthday));
                        $model->app_region = !empty($model->app_region) ? $model->app_region : ($model->owner_region);
                        $model->app_district = !empty($model->app_district) ? $model->app_district : $model->owner_district;
                    }
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            PolicyOsgoDriver::deleteAll(['id' => $deletedIDs]);
                        }
                        $count_child_model = 0;
                        /* @var $modelItem PolicyOsgoDriver */
                        if ($flag) {

                            $session_osgo_drivers = $session->has('model_osgo_drivers') ? $session->get('model_osgo_drivers') : [];

                            foreach ($modelDrivers as $index => $modelItem) {
                                if ($count_child_model==PolicyOsgoDriver::MAX_DRIVERS_LIMIT) {
                                    break;
                                }
                                $modelItem->policy_osgo_id = $model->id;
                                $modelItem->first_name = mb_strtoupper($modelItem->first_name);
                                $modelItem->last_name = mb_strtoupper($modelItem->last_name);
                                $modelItem->pass_sery = mb_strtoupper($modelItem->pass_sery);
                                $modelItem->license_series = mb_strtoupper($modelItem->license_series);
                                $modelItem->birthday = date('Y-m-d', strtotime($modelItem->birthday));
                                $modelItem->license_issue_date = date('Y-m-d', strtotime($modelItem->license_issue_date));
                                if (!empty($session_osgo_drivers[$index])) {
                                    $modelItem->attributes = $session_osgo_drivers[$index];
                                }
                                if (!($flag = $modelItem->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                } else {
                                    $session->addFlash('error', $modelItem->errors);
                                }
                                $count_child_model++;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        unset($session['model_osgo_calc'],$session['model_osgo_calc_drivers'],$session['model_travel_ins_amount']);
                        return $this->redirect(['osgo/approve', 'h' => _model_encrypt($model)], 301);
                    } else {
                        $session->addFlash('error', $model->errors);
                        dd($model->errors);
//                        return $this->redirect(['/payment/payment/index', 'h' => _model_encrypt($model)], 301);
                    }
                } catch (Exception $e) {
                    $title = Yii::t('policy','Osgo model exception');
                    _send_error($title,$e->getMessage());
                    $session->addFlash('error', Yii::t('policy','Хатолик юз берди биз оздан сўнг қайта уриниб кўринг'));
                    $transaction->rollBack();
                }
            } else {
                $session->addFlash('error', $model->errors);
            }
        } elseif ($model->load(Yii::$app->request->post())) {
            $session->addFlash('error', getFirstErrorMessage($model->errors));
        }

        if (!empty($model->app_birthday)) {
            $model->app_birthday = date('d.m.Y', strtotime($model->app_birthday));
        }
        if (!empty($modelDrivers)) {
            foreach ($modelDrivers as $key => $modelItem) {
                if (!empty($modelItem->birthday)) {
                    $modelItem->birthday = date('d.m.Y', strtotime($modelItem->birthday));
                    $modelItem->license_issue_date = date('d.m.Y', strtotime($modelItem->license_issue_date));
                }
                $modelDrivers[$key] = $modelItem;
            }
        }
        return $this->render('fill_from', [
            'model' => $model,
            'modelPage' => $modelPage,
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
            'modelDrivers' => (empty($modelDrivers)) ? [new PolicyOsgoDriver(['scenario' => PolicyOsgoDriver::SCENARIO_SITE_STEP_FORM,'driver_limit' => $model->driver_limit_id])] : $modelDrivers
        ]);
    }

    /**
     * Displays a single PolicyOsgo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionApprove($h)
    {
        $model = $this->findModelHash($h);
        $modelPage = $this->findPage('osgo_approve');
        if (!empty($model->ins_anketa_id)) {

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            $session->remove('model_osgo_calc');
            return $this->redirect(['osgo/calculate'], 301);
        }
        return $this->render('approve', [
            'model' => $this->findModelHash($h),
            'modelPage' => $modelPage,
        ]);
    }

    public function actionConfirm($h)
    {
        $model = $this->findModelHash($h);
        if ($model->saveInsAnketa()) {

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            $session->remove('model_osgo_calc');
            $session->remove('model_osgo_drivers');

            return $this->redirect(['/payment/payment/index', 'h' => _model_encrypt($model)], 301);
        } else {
            return $this->redirect(['osgo/form', 'h' => _model_encrypt($model)], 301);
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
        $model = new PolicyOsgo();
        $model->_loadDefaultValues();
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post()['PolicyOsgo'];
            $model->region_id = !empty($post['region_id']) ? $post['region_id'] : $model->region_id;
            $out = $model->calculatePremPrice();
        }
        return $out;

    }

    public function actionGetTechPassData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (Yii::$app->request->post() && ($post = Yii::$app->request->post())) {
            $items = [
                'tech_pass_series' => mb_strtoupper($post['tech_pass_series']),
                'tech_pass_number' => mb_strtoupper($post['tech_pass_number']),
                'vehicle_gov_number' => mb_strtoupper($post['vehicle_gov_number']),
            ];

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            $model = new PolicyOsgo();
            if ($session->has('model_osgo_calc')) {
                $session_model = $session->get('model_osgo_calc');
                if (!empty($session_model)) {
                    $attrs = json_decode($session_model);
                    $model->attributes = (array)$attrs;
                }
            }

            $out = PolicyOsgo::_getTechPassData($items, $model);

            if (!empty($out) && is_array($out) && empty($out['ERROR'])) {

                $session = Yii::$app->session;
                if (!$session->isActive) $session->open();

                $session_osgo_vehicle = [
                    'owner_orgname' => !empty($out['ORGNAME']) ? $out['ORGNAME'] : null,
                    'owner_first_name' => !empty($out['FIRST_NAME']) ? $out['FIRST_NAME'] : null,
                    'owner_last_name' => !empty($out['LAST_NAME']) ? $out['LAST_NAME'] : null,
                    'owner_middle_name' => !empty($out['MIDDLE_NAME']) ? $out['MIDDLE_NAME'] : null,
                    'owner_birthday' => !empty($out['BIRTHDAY']) ? date('Y-m-d',strtotime($out['BIRTHDAY'])) : null,
                    'owner_pass_sery' => !empty($out['PASSPORT_SERIES']) ? $out['PASSPORT_SERIES'] : null,
                    'owner_pass_num' => !empty($out['PASSPORT_NUMBER']) ? $out['PASSPORT_NUMBER'] : null,
                    'owner_pass_issued_by' => !empty($out['PASSPORT_ISSUED_BY']) ? $out['PASSPORT_ISSUED_BY'] : null,
                    'owner_pass_issue_date' => !empty($out['PASSPORT_ISSUE_DATE']) ? date('Y-m-d',strtotime($out['PASSPORT_ISSUE_DATE'])) : null,
                    'owner_pinfl' => !empty($out['PINFL']) ? $out['PINFL'] : null,
                    'owner_inn' => !empty($out['INN']) ? $out['INN'] : null,
                    'owner_fy' => !is_null($out['FY']) ? $out['FY'] : null,
                    'owner_is_pensioner' => !empty($out['ISPENSIONER']) ? $out['ISPENSIONER'] : PolicyOsgo::DEFAULT_OWNER_IS_PENSIONER,
                    'tech_pass_issue_date' => !empty($out['TECH_PASSPORT_ISSUE_DATE']) ? date('Y-m-d',strtotime($out['TECH_PASSPORT_ISSUE_DATE'])) : null,
                    'vehicle_gov_number' => !empty($items['vehicle_gov_number']) ? $items['vehicle_gov_number'] : null,
                    'tech_pass_series' => !empty($items['tech_pass_series']) ? $items['tech_pass_series'] : null,
                    'tech_pass_number' => !empty($items['tech_pass_number']) ? $items['tech_pass_number'] : null,
                    'vehicle_model_name' => !empty($out['MODEL_NAME']) ? $out['MODEL_NAME'] : null,
                    'vehicle_marka_id' => !empty($out['MARKA_ID']) ? $out['MARKA_ID'] : null,
                    'vehicle_model_id' => !empty($out['MODEL_ID']) ? $out['MODEL_ID'] : null,
                    'vehicle_type_id' => !empty($out['VEHICLE_TYPE_ID']) ? $out['VEHICLE_TYPE_ID'] : null,
                    'vehicle_issue_year' => !empty($out['ISSUE_YEAR']) ? $out['ISSUE_YEAR'] : null,
                    'vehicle_body_number' => !empty($out['BODY_NUMBER']) ? $out['BODY_NUMBER'] : null,
                    'vehicle_engine_number' => !empty($out['ENGINE_NUMBER']) ? $out['ENGINE_NUMBER'] : null,
                ];
                if (!empty($out['REGION_ID'])) {
                    $session_osgo_vehicle['owner_region'] = $out['REGION_ID'];
                }
                if (!empty($out['DISTRICT_ID'])) {
                    $session_osgo_vehicle['owner_district'] = $out['DISTRICT_ID'];
                }
                $session['model_osgo_vehicle'] = $session_osgo_vehicle;
            }
        }
        return $out;

    }

    public function actionGetPassBirthdayData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (Yii::$app->request->post() && ($post = Yii::$app->request->post())) {
            $items = [
                'birthday' => $post['birthday'],
                'pass_series' => mb_strtoupper($post['pass_series']),
                'pass_number' => mb_strtoupper($post['pass_number']),
                'driver_id' => (!is_null($post['driver_id']) && $post['driver_id'] != '') ? $post['driver_id'] : null,
            ];
            $out = PolicyOsgo::_getPassBirthdayData($items);

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            if (!empty($out) && is_array($out) && empty($out['ERROR']) && !is_null($items['driver_id'])) {

                $session_osgo_drivers = $session->has('model_osgo_drivers') ? $session->get('model_osgo_drivers') : [];
                $session_osgo_drivers[$items['driver_id']] = [
                    'first_name' => !empty($out['FIRST_NAME']) ? $out['FIRST_NAME'] : null,
                    'last_name' => !empty($out['LAST_NAME']) ? $out['LAST_NAME'] : null,
                    'middle_name' => !empty($out['MIDDLE_NAME']) ? $out['MIDDLE_NAME'] : null,
                    'birthday' => !empty($items['birthday']) ? date('Y-m-d', strtotime($items['birthday'])) : null,
                    'pass_sery' => !empty($items['pass_series']) ? $items['pass_series'] : null,
                    'pass_num' => !empty($items['pass_number']) ? $items['pass_number'] : null,
                ];
                if (!empty($out['PINFL'])) {
                    $session_osgo_drivers[$items['driver_id']]['pinfl'] = $out['PINFL'];
                }
                if (!empty($out['LICENSE_SERIA'])) {
                    $session_osgo_drivers[$items['driver_id']]['license_series'] = $out['LICENSE_SERIA'];
                }
                if (!empty($out['LICENSE_NUMBER'])) {
                    $session_osgo_drivers[$items['driver_id']]['license_number'] = $out['LICENSE_NUMBER'];
                }
                if (!empty($out['ISSUE_DATE'])) {
                    $session_osgo_drivers[$items['driver_id']]['license_issue_date'] = date('Y-m-d', strtotime($out['ISSUE_DATE']));
                }
                $session['model_osgo_drivers'] = $session_osgo_drivers;
            } elseif ($session->has('model_osgo_vehicle')) {
                $session_osgo_applicant = [
                    'first_name' => !empty($out['FIRST_NAME']) ? $out['FIRST_NAME'] : null,
                    'last_name' => !empty($out['LAST_NAME']) ? $out['LAST_NAME'] : null,
                    'middle_name' => !empty($out['MIDDLE_NAME']) ? $out['MIDDLE_NAME'] : null,
                    'birthday' => !empty($items['birthday']) ? date('Y-m-d', strtotime($items['birthday'])) : null,
                    'pass_sery' => !empty($items['pass_series']) ? $items['pass_series'] : null,
                    'pass_num' => !empty($items['pass_number']) ? $items['pass_number'] : null,
                ];
                if (!empty($out['PINFL'])) {
                    $session_osgo_applicant['pinfl'] = $out['PINFL'];
                }
//                if (!empty($out['REGION_ID'])) {
//                    $session_osgo_applicant
//                    ['owner_region'] = $out['REGION_ID'];
//                }
//                if (!empty($out['DISTRICT_ID'])) {
//                    $session_osgo_applicant
//                    ['owner_district'] = $out['DISTRICT_ID'];
//                }
                $session['session_osgo_applicant'] = $session_osgo_applicant;
            }
        }
        return $out;

    }

    public function actionGetPassPinflData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (Yii::$app->request->post() && ($post = Yii::$app->request->post())) {
            $items = [
                'pinfl' => $post['pinfl'],
                'pass_series' => mb_strtoupper($post['pass_series']),
                'pass_number' => mb_strtoupper($post['pass_number']),
            ];
            $out = PolicyOsgo::_getPassPersonalIDData($items);
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
        else
            throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }

}
