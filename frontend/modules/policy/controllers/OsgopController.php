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
class OsgopController extends Controller
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
        return $this->redirect(['osgop/form'], 301);
    }

    /**
     * Creates a new PolicyOsgo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCalculate($code=null)
    {
        return $this->redirect(['osgop/form'], 301);

    }

    public function actionForm($h=null, $code=null)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();

        if (empty($code) && !empty(Yii::$app->request->get('gclid'))) {
            $code = 'g-ad';
        }

        $model = null;

        $modelPage = $this->findPage('osgop_anketa');

        if (!empty($h)) {
            $id_model = _model_decrypt($h);
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            $model = $modelClassName::findOne($id_model['id']);
            /* @var $model PolicyOsgo */
            if (!empty($model) && empty($model->ins_anketa_id)) {
                $model->scenario = PolicyOsgo::SCENARIO_SITE_STEP_OSGOP_FORM;
                $model->product_type = PolicyOsgo::PRODUCT_TYPE_OSGOP;
                $model->start_date = date('d.m.Y', strtotime($model->start_date));
                $model->end_date = date('d.m.Y', strtotime($model->end_date));
            }
        }

        if (empty($model)) {
            $model = new PolicyOsgo(['scenario' => PolicyOsgo::SCENARIO_SITE_STEP_OSGOP_FORM, 'product_type' => PolicyOsgo::PRODUCT_TYPE_OSGOP]);
            if ($session->has('model_osgop_calc')) {
                $session_model = $session->get('model_osgop_calc');
                if (!empty($session_model)) {
                    $attrs = json_decode($session_model);
                    $model->attributes = (array)$attrs;

                    $cur_date = date('d.m.Y');
                    if (strtotime($model->start_date) < strtotime($cur_date)) {
                        $session->remove('model_osgop_calc');
                    }
                    $model->_ins_amount = PolicyOsgo::INSURANCE_SUM;
                    $model->setAppPhone();

                }
            } else {
                $model->_loadDefaultValues();
            }
            $model->product_type = PolicyOsgo::PRODUCT_TYPE_OSGOP;

        }

        if (!empty(Yii::$app->session->get('source_id'))) {
            $model->source = Yii::$app->session->get('source_id');
        }

        $model->calculatePremPriceOsgop();

        if (!empty($model->app_birthday)) {
            $model->app_birthday = date('d.m.Y', strtotime($model->app_birthday));
        }
        if (!empty($model->start_date)) {
            $model->setEndDate();
            $model->start_date = date('d.m.Y', strtotime($model->start_date));
            $model->end_date = date('d.m.Y', strtotime($model->end_date));
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->product_type = PolicyOsgo::PRODUCT_TYPE_OSGOP;
            $model->calculatePremPriceOsgop();

            if ($session->has('model_osgop_vehicle') && !empty($session['model_osgop_vehicle'])) {
                $model->attributes = $session['model_osgop_vehicle'];
            }
            if ($session->has('session_osgop_applicant') && !empty($session['session_osgop_applicant'])) {
                $model->attributes = $session['session_osgop_applicant'];
            }
            // validate all models
            $valid = $model->validate();

            $error_message = Yii::t('policy','Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
            if ($valid) {
                $model->setRegion();
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $model->setEndDate();
                    $model->owner_birthday = !empty($model->owner_birthday) ? date('Y-m-d', strtotime($model->owner_birthday)) : null;
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
                        $model->app_pass_issue_date = !empty($model->owner_pass_issue_date) ? $model->owner_pass_issue_date : $model->app_pass_issue_date;
                        $model->app_pass_expiration_date = !empty($model->owner_pass_expiration_date) ? $model->owner_pass_expiration_date : $model->app_pass_expiration_date;
                        $model->app_birthday = date('Y-m-d', strtotime($model->owner_birthday));
                        $model->app_region = ($model->owner_region);
                        $model->app_district = ($model->owner_district);
                        $model->app_address = ($model->owner_address);
                    } else {
                        $model->app_first_name = mb_strtoupper($model->app_first_name);
                        $model->app_last_name = mb_strtoupper($model->app_last_name);
                        $model->app_middle_name = mb_strtoupper($model->app_middle_name);
                        $model->app_pass_sery = mb_strtoupper($model->app_pass_sery);
                        $model->app_birthday = date('Y-m-d', strtotime($model->app_birthday));
                        $model->app_region = !empty($model->app_region) ? $model->app_region : ($model->owner_region);
                        $model->app_district = !empty($model->app_district) ? $model->app_district : $model->owner_district;
                        $model->app_pass_issued_by = !empty($model->app_pass_issued_by) ? $model->app_pass_issued_by : $model->owner_pass_issued_by;
                        $model->app_pass_issue_date = !empty($model->app_pass_issue_date) ? $model->app_pass_issue_date : $model->owner_pass_issue_date;
                        $model->app_pass_expiration_date = !empty($model->app_pass_expiration_date) ? $model->app_pass_expiration_date : $model->owner_pass_expiration_date;
                    }
                    if ($flag = $model->save(false)) {
                        $transaction->commit();
                        unset($session['model_osgop_calc'],$session['model_travel_ins_amount']);
                        return $this->redirect(['osgop/approve', 'h' => _model_encrypt($model)], 301);
                    } else {
                        $session->addFlash('error', $model->errors);
                        dd($model->errors);
//                        return $this->redirect(['/payment/payment/index', 'h' => _model_encrypt($model)], 301);
                    }
                } catch (Exception $e) {
                    $title = 'Osgop model exception';
                    _send_error($title,$e->getMessage());
                    Yii::warning("\n\nOSGOP ERRORS Exception\n\n");
                    Yii::warning($e);
                    $session->addFlash('error', $error_message);
                    $transaction->rollBack();
                }
            } else {
                Yii::warning("\n\nOSGOP ERRORS AFTER TRANSACTION\n\n");
                Yii::warning($model->errors);
                $session->addFlash('error', _generate_error($model->errors));
//                _send_error('OSGOP FORM SAVE VALIDATION TRY',json_encode($model->errors));

            }
        } elseif ($model->load(Yii::$app->request->post())) {
            Yii::warning("\n\nOSGOP ERRORS AFTER POST\n\n");
            Yii::warning($model->errors);
            $session->addFlash('error', _generate_error($model->errors));
//            _send_error('OSGOP FORM SAVE VALIDATION',json_encode($model->errors));

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
            return $this->redirect(['osgop/form'], 301);
        }
        $model = $this->findModelHash($h);
        $modelPage = $this->findPage('osgop_approve');
        if (!empty($model->ins_anketa_id)) {

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            $session->remove('model_osgop_calc');
            return $this->redirect(['osgop/form'], 301);
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
        if ($model->saveInsAnketaOsgop()) {

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            $session->remove('model_travel_program');
            $session->remove('model_osgop_calc');
            $session->remove('model_osgop_vehicle');
            $session->remove('session_osgop_applicant');

            return $this->redirect(['/payment/payment/index', 'h' => _model_encrypt($model), 'p' => $p], 301);
        } else {
            return $this->redirect(['osgop/approve', 'h' => _model_encrypt($model)], 301);
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
        $model = new PolicyOsgo(["product_type" => PolicyOsgo::PRODUCT_TYPE_OSGOP]);
        $model->_loadDefaultValues();
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post()['PolicyOsgo'];
            $model->region_id = !empty($post['region_id']) ? $post['region_id'] : $model->region_id;
            $out = $model->calculatePremPriceOsgop();
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
            if ($session->has('model_osgop_calc')) {
                $session_model = $session->get('model_osgop_calc');
                if (!empty($session_model)) {
                    $attrs = json_decode($session_model);
                    $model->attributes = (array)$attrs;
                }
            }

            $out = PolicyOsgo::_getTechPassDataOsgop($items, $model);
            Yii::warning("\n\n_getTechPassData\n\n");
            Yii::warning($out);
            if (!empty($out) && is_array($out) && empty($out['ERROR'])) {

                $session = Yii::$app->session;
                if (!$session->isActive) $session->open();

                $session_osgop_vehicle = [
                    'owner_orgname' => !empty($out['ORGNAME']) ? $out['ORGNAME'] : null,
                    'owner_first_name' => !empty($out['FIRST_NAME']) ? $out['FIRST_NAME'] : null,
                    'owner_last_name' => !empty($out['LAST_NAME']) ? $out['LAST_NAME'] : null,
                    'owner_middle_name' => !empty($out['MIDDLE_NAME']) ? $out['MIDDLE_NAME'] : null,
                    'owner_pass_issued_by' => !empty($out['PASSPORT_ISSUED_BY']) ? $out['PASSPORT_ISSUED_BY'] : null,
                    'owner_pass_issue_date' => !empty($out['PASSPORT_ISSUE_DATE']) ? date('Y-m-d',strtotime($out['PASSPORT_ISSUE_DATE'])) : null,
                    'owner_pinfl' => !empty($out['PINFL']) ? $out['PINFL'] : null,
                    'owner_inn' => !empty($out['INN']) ? $out['INN'] : null,
                    'owner_oked' => !empty($out['OKED']) ? $out['OKED'] : null,
                    'owner_fy' => !is_null($out['FY']) ? $out['FY'] : null,
                    'owner_is_pensioner' => !empty($out['ISPENSIONER']) ? $out['ISPENSIONER'] : PolicyOsgo::DEFAULT_OWNER_IS_PENSIONER,
                    'tech_pass_issue_date' => !empty($out['TECH_PASSPORT_ISSUE_DATE']) ? date('Y-m-d',strtotime($out['TECH_PASSPORT_ISSUE_DATE'])) : null,
                    'vehicle_gov_number' => !empty($items['vehicle_gov_number']) ? $items['vehicle_gov_number'] : null,
                    'tech_pass_series' => !empty($items['tech_pass_series']) ? $items['tech_pass_series'] : null,
                    'tech_pass_number' => !empty($items['tech_pass_number']) ? $items['tech_pass_number'] : null,
                    'vehicle_model_name' => !empty($out['MODEL_NAME']) ? $out['MODEL_NAME'] : null,
                    'vehicle_marka_id' => !empty($out['MARKA_ID']) ? $out['MARKA_ID'] : null,
                    'vehicle_model_id' => !empty($out['MODEL_ID']) ? sanitizeVehicleModelID($out['MODEL_ID']) : null,
                    'vehicle_type_id' => !empty($out['VEHICLE_TYPE_ID']) ? $out['VEHICLE_TYPE_ID'] : null,
                    'vehicle_issue_year' => !empty($out['ISSUE_YEAR']) ? $out['ISSUE_YEAR'] : null,
                    'vehicle_body_number' => !empty($out['BODY_NUMBER']) ? $out['BODY_NUMBER'] : null,
                    'vehicle_engine_number' => !empty($out['ENGINE_NUMBER']) ? $out['ENGINE_NUMBER'] : null,
                ];
//                if (!empty($out['SEATS'])) {
//                    $session_osgop_vehicle['vehicle_seats_count'] = $out['SEATS'];
//                }
                if (!empty($out['BODY_NUMBER'])) {
                    $session_osgop_vehicle['vehicle_body_number'] = $out['BODY_NUMBER'];
                }
                if (!empty($out['ENGINE_NUMBER'])) {
                    $session_osgop_vehicle['vehicle_engine_number'] = $out['ENGINE_NUMBER'];
                }
                if (!empty($out['BIRTHDAY'])) {
                    $session_osgop_vehicle['owner_birthday'] = $out['BIRTHDAY'];
                } elseif(!empty($out['PINFL'])) {
                    $session_osgop_vehicle['owner_birthday'] = _getBirthdayFromPinfl($out['PINFL']);
                }
                if (!empty($out['PASSPORT_SERIES'])) {
                    $session_osgop_vehicle['owner_pass_sery'] = $out['PASSPORT_SERIES'];
                }
                if (!empty($out['PASSPORT_NUMBER'])) {
                    $session_osgop_vehicle['owner_pass_num'] = $out['PASSPORT_NUMBER'];
                }
                if (!empty($out['REGION_ID'])) {
                    $session_osgop_vehicle['owner_region'] = $out['REGION_ID'];
                }
                if (!empty($out['DISTRICT_ID'])) {
                    $session_osgop_vehicle['owner_district'] = $out['DISTRICT_ID'];
                }
                if (!empty($out['ADDRESS'])) {
                    $session_osgop_vehicle['owner_address'] = $out['ADDRESS'];
                }
                Yii::warning("\n\nsession_osgop_vehicle\n\n");
                Yii::warning($session_osgop_vehicle);
                $session['model_osgop_vehicle'] = $session_osgop_vehicle;
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
                'driver_id' => null,
            ];
            $out = PolicyOsgo::_getPassBirthdayData($items);

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            if (!empty($out) && is_array($out) && empty($out['ERROR']) && $session->has('model_osgop_vehicle')) {
                $session_osgop_applicant = [
                    'app_first_name' => !empty($out['FIRST_NAME']) ? $out['FIRST_NAME'] : null,
                    'app_last_name' => !empty($out['LAST_NAME']) ? $out['LAST_NAME'] : null,
                    'app_middle_name' => !empty($out['MIDDLE_NAME']) ? $out['MIDDLE_NAME'] : null,
                    'app_birthday' => !empty($items['birthday']) ? date('Y-m-d', strtotime($items['birthday'])) : null,
                    'app_pass_sery' => !empty($items['pass_series']) ? $items['pass_series'] : null,
                    'app_pass_num' => !empty($items['pass_number']) ? $items['pass_number'] : null,
                ];
                if (!empty($out['FIRST_NAME'])) {
                    $session_osgop_applicant['first_name'] = !empty($out['FIRST_NAME']) ? $out['FIRST_NAME'] : null;
                    $session_osgop_applicant['last_name'] = !empty($out['LAST_NAME']) ? $out['LAST_NAME'] : null;
                    $session_osgop_applicant['middle_name'] = !empty($out['MIDDLE_NAME']) ? $out['MIDDLE_NAME'] : null;
                }
                if (!empty($out['PASSPORT_ISSUED_BY'])) {
                    $session_osgop_applicant['app_pass_issued_by'] = $out['PASSPORT_ISSUED_BY'];
                } else {
                    $session_osgop_applicant['app_pass_issued_by'] = 'TEST ISSUED BY';
                }
                if (!empty($out['PASSPORT_ISSUE_DATE'])) {
                    $session_osgop_applicant['app_pass_issue_date'] = $out['PASSPORT_ISSUE_DATE'];
                } else {
                    $session_osgop_applicant['app_pass_issue_date'] = "2023-01-01T00:00:00";
                }
                if (!empty($out['PASSPORT_EXPIRATION_DATE'])) {
                    $session_osgop_applicant['app_pass_expiration_date'] = $out['PASSPORT_EXPIRATION_DATE'];
                } else {
                    $session_osgop_applicant['app_pass_expiration_date'] = "2033-01-01T00:00:00";
                }
                if (!empty($out['PINFL'])) {
                    $session_osgop_applicant['pinfl'] = $out['PINFL'];
                }
                if (!empty($out['REGION_ID'])) {
                    $session_osgop_applicant['app_region'] = $out['REGION_ID'];
                }
                if (!empty($out['DISTRICT_ID'])) {
                    $session_osgop_applicant['app_district'] = $out['DISTRICT_ID'];
                }
                if (!empty($out['ADDRESS'])) {
                    $session_osgop_applicant['app_address'] = $out['ADDRESS'];
                }
                $session['session_osgop_applicant'] = $session_osgop_applicant;
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
                'driver_id' => (!is_null($post['driver_id']) && $post['driver_id'] != '') ? $post['driver_id'] : null,
                'senderPinfl' => (!is_null($post['sender_pinfl']) && $post['sender_pinfl'] != '') ? $post['sender_pinfl'] : $post['pinfl'],
            ];
            $out = PolicyOsgo::_getPassPersonalIDData($items);

            Yii::warning("\n\n_getPassPersonalIDData\n\n");
            Yii::warning($out);

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            if (!empty($out) && is_array($out) && empty($out['ERROR']) && !is_null($items['driver_id'])) {

                Yii::warning("\n\n\nSESSION VEHICLE PINFL DRIVER DATA\n");
                Yii::warning($session->get('model_osgop_vehicle'));

                $session_osgo_drivers = $session->has('model_osgop_drivers') ? $session->get('model_osgop_drivers') : [];
                $session_osgo_drivers[$items['driver_id']] = [
                    'first_name' => !empty($out['FIRST_NAME']) ? $out['FIRST_NAME'] : null,
                    'last_name' => !empty($out['LAST_NAME']) ? $out['LAST_NAME'] : null,
                    'middle_name' => !empty($out['MIDDLE_NAME']) ? $out['MIDDLE_NAME'] : null,
                    'pass_issued_by' => !empty($out['PASSPORT_ISSUED_BY']) ? $out['PASSPORT_ISSUED_BY'] : null,
                    'pass_issue_date' => !empty($out['PASSPORT_ISSUE_DATE']) ? $out['PASSPORT_ISSUE_DATE'] : null,
                    'pass_expiration_date' => !empty($out['PASSPORT_EXPIRATION_DATE']) ? $out['PASSPORT_EXPIRATION_DATE'] : null,
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

                $session['model_osgop_drivers'] = $session_osgo_drivers;

            } elseif ($session->has('model_osgop_vehicle')) {
                Yii::warning("\n\n\nSESSION VEHICLE DATA BEFORE\n");
                Yii::warning($session->get('model_osgop_vehicle'));
                $session_osgo_vehicle = $session->has('model_osgop_vehicle') ? $session->get('model_osgop_vehicle') : [];
                if (!empty($out['BIRTHDAY'])) {
                    $session_osgo_vehicle['owner_birthday'] = $out['BIRTHDAY'];
                } elseif(!empty($out['PINFL'])) {
                    $session_osgo_vehicle['owner_birthday'] = _getBirthdayFromPinfl($out['PINFL']);
                }
                if (!empty($out['FIRST_NAME'])) {
                    $session_osgo_vehicle['owner_first_name'] = $out['FIRST_NAME'];
                }
                if (!empty($out['LAST_NAME'])) {
                    $session_osgo_vehicle['owner_last_name'] = $out['LAST_NAME'];
                }
                if (!empty($out['MIDDLE_NAME'])) {
                    $session_osgo_vehicle['owner_middle_name'] = $out['MIDDLE_NAME'];
                }
                if (!empty($out['PASSPORT_SERIES'])) {
                    $session_osgo_vehicle['owner_pass_sery'] = $out['PASSPORT_SERIES'];
                }
                if (!empty($out['PASSPORT_NUMBER'])) {
                    $session_osgo_vehicle['owner_pass_num'] = $out['PASSPORT_NUMBER'];
                }
                if (!empty($out['PASSPORT_ISSUED_BY'])) {
                    $session_osgo_vehicle['owner_pass_issued_by'] = $out['PASSPORT_ISSUED_BY'];
                } else {
                    $session_osgo_vehicle['owner_pass_issued_by'] = 'TEST ISSUED BY';
                }
                if (!empty($out['PASSPORT_ISSUE_DATE'])) {
                    $session_osgo_vehicle['owner_pass_issue_date'] = $out['PASSPORT_ISSUE_DATE'];
                } else {
                    $session_osgo_vehicle['owner_pass_issue_date'] = "2023-01-01T00:00:00";
                }
                if (!empty($out['PASSPORT_EXPIRATION_DATE'])) {
                    $session_osgo_vehicle['owner_pass_expiration_date'] = $out['PASSPORT_EXPIRATION_DATE'];
                } else {
                    $session_osgo_vehicle['owner_pass_expiration_date'] = "2033-01-01T00:00:00";
                }
                if (!empty($out['REGION_ID'])) {
                    $session_osgo_vehicle['owner_region'] = $out['REGION_ID'];
                }
                if (!empty($out['DISTRICT_ID'])) {
                    $session_osgo_vehicle['owner_district'] = $out['DISTRICT_ID'];
                }
                if (!empty($out['ADDRESS'])) {
                    $session_osgo_vehicle['owner_address'] = $out['ADDRESS'];
                }
                $session['model_osgop_vehicle'] = $session_osgo_vehicle;

                Yii::warning("\n\n\nSESSION VEHICLE DATA NEW DATA\n");
                Yii::warning($session['model_osgop_vehicle']);
                Yii::warning("\n\n\nSESSION VEHICLE DATA AFTER\n");
                Yii::warning($session->get('model_osgop_vehicle'));
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
