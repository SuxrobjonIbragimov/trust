<?php

namespace frontend\modules\policy\controllers;

use backend\models\page\Pages;
use backend\models\page\SourceCounter;
use backend\modules\policy\models\PolicyTravel;
use backend\modules\policy\models\PolicyTravelParentTraveller;
use backend\modules\policy\models\PolicyTravelProgram;
use backend\modules\policy\models\PolicyTravelToCountry;
use backend\modules\policy\models\PolicyTravelTraveller;
use backend\modules\telegram\models\BotUser;
use common\base\Model;
use common\models\Settings;
use Exception;
use Yii;
use yii\base\BaseObject;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TravelController implements the CRUD actions for PolicyTravel model.
 */
class TravelController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(Url::to(['/policy/travel/calculate']));
    }
    /**
     * Creates a new PolicyTravel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCalculate($h = null)
    {
        $model = new PolicyTravel(['scenario' => PolicyTravel::SCENARIO_SITE_STEP_CALC]);
        $model->_loadDefaultValues();
        $modelTravellers = [new PolicyTravelTraveller(['scenario' => PolicyTravelTraveller::SCENARIO_SITE_STEP_CALC])];
        $modelParentTravellers = [new PolicyTravelParentTraveller(['scenario' => PolicyTravelParentTraveller::SCENARIO_SITE_STEP_CALC])];

        if (!empty(Yii::$app->session->get('source_id'))) {
            $model->source = Yii::$app->session->get('source_id');
        }

        if (!empty(Yii::$app->session->get('b_u_id'))) {
            $bot_user_id = Yii::$app->session->get('b_u_id');
            $botModel = BotUser::findOne($bot_user_id);
            if (!empty($botModel->ins_agent_id)) {
                $model->ins_agent_id = $botModel->ins_agent_id;
            }
            $model->bot_user_id = Yii::$app->session->get('b_u_id');
        }

        $modelPage = $this->findPage('travel_calc');

        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();

        if (!empty($h)) {
            $id_model = _model_decrypt($h);
            /* @var $modelClassName PolicyTravel */
            $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
            $model = $modelClassName::findOne($id_model['id']);
            /* @var $model PolicyTravel */
            if (!empty($model) && empty($model->policyOrder->paymentTransaction)) {
                $model->scenario = PolicyTravel::SCENARIO_SITE_STEP_CALC;
                $model->start_date = date('d.m.Y', strtotime($model->start_date));
                $model->end_date = date('d.m.Y', strtotime($model->end_date));
                $model->country_ids = json_decode($model->country_ids);
                $modelTravellers_ = $model->policyTravelTravellers;
                if (!empty($modelTravellers_)) {
                    $modelTravellers = null;
                    foreach ($modelTravellers_ as $traveller) {
                        $traveller->scenario = PolicyTravelTraveller::SCENARIO_SITE_STEP_CALC;
                        $traveller->birthday = date('d.m.Y', strtotime($traveller->birthday));
                        $modelTravellers[] = $traveller;
                    }
                }
            }
        }
        $model->_travelCountriesList = $model->getCountriesList();
        $model->_travelPurposesList = $model->getPurposesList();
        if (!empty($model->_travelCountries)) {
            $model->_travelProgramsList = $model->getProgramsList();
        } else {
            $model->program_id = null;
            $model->_travelProgramsList = null;
        }


        if (!empty($model->_travelCountries) && !empty($model->program_id) && !empty($model->days)) {
            if (!empty($model->_traveller_birthday)) {
                $model->calculateFullPrice();
            }
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $covid = false;
            if (isset(Yii::$app->request->post()['PolicyTravel']['covid_has'])) {
                $covid = Yii::$app->request->post()['PolicyTravel']['covid_has'];
            }
            if (!empty($model->_travelCountries)) {
                $model->_travelProgramsList = $model->getProgramsList(null,$covid);
            } else {
                $model->program_id = null;
                $model->_travelProgramsList = null;
            }

            if (!empty($model->program_id)) {
                $programModel = PolicyTravelProgram::findOne($model->program_id);
                if (!empty($programModel)) {
                    $model->_ins_amount = $programModel->totalRiskAmount;
                }
            }

            $model->setDays();

            if ($model->days > 365) {
                $model->days = 365;
            }
            if (!empty($model->days) && is_numeric($model->days)) {
                $model->setEndDate();
            }

            $modelParentTravellers = Model::createMultiple(PolicyTravelParentTraveller::classname());
            $modelTravellers = Model::createMultiple(PolicyTravelTraveller::classname());
            Model::loadMultiple($modelParentTravellers, Yii::$app->request->post());
            Model::loadMultiple($modelTravellers, Yii::$app->request->post());

            $tmp_array =[];
            if (!empty($modelParentTravellers)) {
                foreach ($modelParentTravellers as $modelTraveller) {
                    $age = floor((time() - strtotime($modelTraveller->birthday)) / 31556926);
                    if (!empty($modelTraveller->birthday) && ($age < 100 || $age > 0)) {
                        $tmp_array[] = $modelTraveller->birthday;
                    }
                }
            }
            if (!empty($modelTravellers)) {
                foreach ($modelTravellers as $modelTraveller) {
                    $age = floor((time() - strtotime($modelTraveller->birthday)) / 31556926);
                    if (!empty($modelTraveller->birthday) && ($age < 100 || $age > 0)) {
                        $tmp_array[] = $modelTraveller->birthday;
                    }
                }
            }
//            d($tmp_array);
            $model->_traveller_birthday = $tmp_array;
            Yii::warning("\n\n_traveller_birthday");
            Yii::warning($model->_traveller_birthday);

            if (!empty($model->_traveller_birthday)) {
                $model->calculateFullPrice();
            }
            $session_calc_countries = null;
            if (!empty($model->start_date) && !empty($model->end_date) && !empty($model->days)) {
                if ($model->validate() && !empty(Yii::$app->request->post()['submit'])) {
                    $model->country_ids = $model->_travelCountries;
                    $model->amount_uzs = $model->_policy_price_uzs;
                    $model->amount_usd = $model->_policy_price_usd;
                    $session_calc_countries = $model->_travelCountries;
                    $session['model_travel_calc_countries'] = $model->_travelCountries;
                    $session['model_travel_calc_travellers'] = json_encode($model->_traveller_birthday);
                    $session['model_travel_calc'] = json_encode($model->attributes);
                    $session['model_travel_program'] = [
                        'amount' => $model->_ins_amount,
                        'name' => $model->_travelProgramsList[$model->program_id],
                    ];

                    if (!$model->isNewRecord) {
                        $oldIDs = ArrayHelper::map($modelTravellers, 'id', 'id');
                        $modelTravellers = Model::createMultiple(PolicyTravelTraveller::classname(), $modelTravellers);
                        Model::loadMultiple($modelTravellers, Yii::$app->request->post());
                        $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelTravellers, 'id', 'id')));
                    } else {
                        $oldIDs = null;
                        $modelTravellers = Model::createMultiple(PolicyTravelTraveller::classname());
                        Model::loadMultiple($modelTravellers, Yii::$app->request->post());
                        $deletedIDs = null;
                    }

                    // validate all models
                    $valid = $model->validate();
                    $valid = Model::validateMultiple($modelTravellers) && $valid;

                    if ($valid) {
                        $exception_catch = Yii::t('frontend','Travel model exception');
                        $session_error_flash = Yii::t('frontend','Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');

                        $transaction = \Yii::$app->db->beginTransaction();
                        try {

                            $model->setEndDate();
                            if (empty($model->app_surname) && $model->is_family) {
                                $model->app_name = !empty($modelTravellers[0]->first_name) ? $modelTravellers[0]->first_name : $model->app_name;
                                $model->app_surname = !empty($modelTravellers[0]->surname) ? $modelTravellers[0]->surname : $model->app_surname;
                                $model->app_pass_sery = !empty($modelTravellers[0]->pass_sery) ? $modelTravellers[0]->pass_sery : $model->app_pass_sery;
                                $model->app_pass_num = !empty($modelTravellers[0]->pass_num) ? $modelTravellers[0]->pass_num : $model->app_pass_num;
                                $model->app_pinfl = !empty($modelTravellers[0]->pinfl) ? $modelTravellers[0]->pinfl : $model->app_pinfl;
                                $model->app_birthday = !empty($modelTravellers[0]->birthday) ? date('Y-m-d', strtotime($modelTravellers[0]->birthday)) : $model->app_birthday;
                            }
                            $model->app_name = mb_strtoupper($model->app_name);
                            $model->app_pass_sery = mb_strtoupper($model->app_pass_sery);
                            $model->app_phone = clear_phone_full($model->app_phone);
                            $model->start_date = date('Y-m-d', strtotime($model->start_date));
                            $model->end_date = date('Y-m-d', strtotime($model->end_date));
                            $model->app_birthday = date('Y-m-d', strtotime($model->app_birthday));
                            $model->country_ids = json_encode($model->country_ids);
                            $model->app_address = mb_strtoupper($model->app_address);
                            if ($flag = $model->save(false)) {
                                if (!empty($deletedIDs)) {
                                    PolicyTravelTraveller::deleteAll(['id' => $deletedIDs]);
                                }
                                $count_traveller = 0;
                                if ($flag) {
                                    foreach ($modelTravellers as $index => $modelItem) {
                                        if ($count_traveller==6) {
                                            break;
                                        }
                                        $modelItem->policy_travel_id = $model->id;
                                        $modelItem->pass_sery = mb_strtoupper($modelItem->pass_sery);
                                        $modelItem->birthday = date('Y-m-d', strtotime($modelItem->birthday));
                                        if (!($flag = $modelItem->save(false))) {
                                            $transaction->rollBack();
                                            break;
                                        } else {
                                            $session->addFlash('error', $modelItem->errors);
                                        }
                                        $count_traveller++;
                                    }
                                }
                                if ($flag && !empty($session_calc_countries)) {
                                    if(is_array($session_calc_countries)) {
                                        foreach ($session_calc_countries as $key => $country_id) {
                                            $attributes = [
                                                'policy_travel_id' => $model->id,
                                                'country_id' => $country_id,
                                                'weight' => $key,
                                            ];
                                            $modelCountry = new PolicyTravelToCountry($attributes);

                                            if (!($flag = $modelCountry->save(false))) {
                                                $transaction->rollBack();
                                                break;
                                            } else {
                                                $session->addFlash('error', $modelCountry->errors);
                                            }
                                        }
                                    } else {

                                        $attributes = [
                                            'policy_travel_id' => $model->id,
                                            'country_id' => intval($session_calc_countries),
                                            'weight' => 0,
                                        ];
                                        $modelCountry = new PolicyTravelToCountry($attributes);

                                        if (!($flag = $modelCountry->save(false))) {
                                            $transaction->rollBack();
                                        } else {
                                            $session->addFlash('error', $modelCountry->errors);
                                        }
                                    }
                                }
                            }
                            if ($flag) {
                                $transaction->commit();
                                unset($session['model_travel_calc'],$session['model_travel_calc_travellers'],$session['model_travel_ins_amount']);
                                return $this->redirect(['travel/approve', 'h' => _model_encrypt($model)]);
                            } else {
                                $session->addFlash('error', $model->errors);
                                $title = Yii::t('policy','Travel model exception');
                                _send_error($title,json_encode($model->errors, JSON_UNESCAPED_UNICODE));
                                dd($model->errors);
                            }
                        } catch (Exception $e) {
                            $title = $exception_catch;
                            _send_error($title,$e->getMessage());
                            $session->addFlash('error', $e->getMessage());
                            $session->addFlash('error', $session_error_flash);
                            $transaction->rollBack();
                        }
                    } else {
                        $session->addFlash('error', $model->errors);
                    }
                }
            }
            if (is_null($model->program_id)) {
                $model->_ins_amount = 0;
            }
            return $this->renderAjax('calculate', [
                'model' => $model,
                'modelPage' => $modelPage,
                'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
                'modelParentTravellers' => (empty($modelParentTravellers)) ? [new PolicyTravelParentTraveller(['isFamily' => $model->is_family])] : $modelParentTravellers,
                'modelTravellers' => (empty($modelTravellers)) ? [new PolicyTravelTraveller(['isFamily' => $model->is_family])] : $modelTravellers,
            ]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->setDays();
            if (!empty($model->_traveller_birthday)) {
                $model->calculateFullPrice();
            }

            $model->amount_uzs = $model->_policy_price_uzs;
            $model->amount_usd = $model->_policy_price_usd;

            $modelParentTravellers = Model::createMultiple(PolicyTravelParentTraveller::classname());
            $modelTravellers = Model::createMultiple(PolicyTravelTraveller::classname());
            Model::loadMultiple($modelParentTravellers, Yii::$app->request->post());
            Model::loadMultiple($modelTravellers, Yii::$app->request->post());

            $tmp_array =[];
            if (!empty($modelParentTravellers)) {
                foreach ($modelParentTravellers as $modelTraveller) {
                    $age = floor((time() - strtotime($modelTraveller->birthday)) / 31556926);
                    if (!empty($modelTraveller->birthday) && ($age < 100 || $age > 0)) {
                        $tmp_array[] = $modelTraveller->birthday;
                    }
                }
            }
            if (!empty($modelTravellers)) {
                foreach ($modelTravellers as $modelTraveller) {
                    $age = floor((time() - strtotime($modelTraveller->birthday)) / 31556926);
                    if (!empty($modelTraveller->birthday) && ($age < 100 || $age > 0)) {
                        $tmp_array[] = $modelTraveller->birthday;
                    }
                }
            }
            $session['model_travel_calc_countries'] = $model->_travelCountries;
            $session['model_travel_calc_travellers'] = json_encode($tmp_array);

            $session['model_travel_calc'] = json_encode($model->attributes);
            $session['model_travel_program'] = [
                    'amount' => $model->_ins_amount,
                    'name' => $model->_travelProgramsList[$model->program_id],
                ];

        } else {
            $session->addFlash('error', $model->errors);
        }

        if (is_null($model->program_id)) {
            $model->_ins_amount = 0;
        }

        if (!empty($model->app_birthday)) {
            $model->app_birthday = date('d.m.Y', strtotime($model->app_birthday));
        }
        if (!empty($modelTravellers)) {
            foreach ($modelTravellers as $key => $modelItem) {
                if (!empty($modelItem->birthday)) {
                    $modelItem->birthday = date('d.m.Y', strtotime($modelItem->birthday));
                }
                $modelTravellers[$key] = $modelItem;
            }
        }

        return $this->render('calculate', [
            'model' => $model,
            'modelPage' => $modelPage,
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
            'modelParentTravellers' => (empty($modelParentTravellers)) ? [new PolicyTravelParentTraveller(['isFamily' => $model->is_family])] : $modelParentTravellers,
            'modelTravellers' => (empty($modelTravellers)) ? [new PolicyTravelTraveller(['isFamily' => $model->isFamily])] : $modelTravellers
        ]);
    }

    public function actionForm($h=null): Response
    {
        return $this->redirect(Url::to(['travel/calculate','h'=>$h]), 301);
    }

    /**
     * Displays a single PolicyTravel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionApprove($h)
    {
        $model = $this->findModelHash($h);
        $modelPage = $this->findPage('travel_approve');
        if (!empty($model->ins_anketa_id)) {

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            $session->remove('model_travel_calc');
            $session->remove('model_travel_program');
            $session->remove('model_travel_calc_countries');
            $session->remove('model_travel_calc_travellers');
            return $this->redirect(['/payment/payment/index', 'h' => _model_encrypt($model)], 301);
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

            $session->remove('model_travel_calc');
            $session->remove('model_travel_program');
            $session->remove('model_travel_calc_countries');
            $session->remove('model_travel_calc_travellers');

            return $this->redirect(['/payment/payment/index', 'h' => _model_encrypt($model)], 301);
        } else {
            return $this->redirect(['travel/approve', 'h' => _model_encrypt($model)], 301);
        }
    }

    public function actionGetPassBirthdayData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = [];
        if (Yii::$app->request->post() && ($post = Yii::$app->request->post())) {
            $items = [
                'birthday' => $post['birthday'],
                'pass_series' => mb_strtoupper($post['pass_series']),
                'pass_number' => mb_strtoupper($post['pass_number']),
                'driver_id' => (!is_null($post['driver_id']) && $post['driver_id'] != '') ? $post['driver_id'] : null,
            ];
            $out = PolicyTravel::_getPassBirthdayData($items);

            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            if (!empty($out) && is_array($out) && empty($out['ERROR']) && !is_null($items['driver_id'])) {

                $session_travel_drivers = $session->has('model_travel_drivers') ? $session->get('model_travel_drivers') : [];
                $session_travel_drivers[$items['driver_id']] = [
                    'first_name' => !empty($out['FIRST_NAME']) ? $out['FIRST_NAME'] : null,
                    'last_name' => !empty($out['LAST_NAME']) ? $out['LAST_NAME'] : null,
                    'middle_name' => !empty($out['MIDDLE_NAME']) ? $out['MIDDLE_NAME'] : null,
                    'birthday' => !empty($items['birthday']) ? date('Y-m-d', strtotime($items['birthday'])) : null,
                    'pass_sery' => !empty($items['pass_series']) ? $items['pass_series'] : null,
                    'pass_num' => !empty($items['pass_number']) ? $items['pass_number'] : null,
                ];
                if (!empty($out['PINFL'])) {
                    $session_travel_drivers[$items['driver_id']]['pinfl'] = $out['PINFL'];
                }
                $session['model_travel_drivers'] = $session_travel_drivers;
            } elseif ($session->has('model_travel_vehicle')) {
                $session_travel_applicant = [
                    'first_name' => !empty($out['FIRST_NAME']) ? $out['FIRST_NAME'] : null,
                    'last_name' => !empty($out['LAST_NAME']) ? $out['LAST_NAME'] : null,
                    'middle_name' => !empty($out['MIDDLE_NAME']) ? $out['MIDDLE_NAME'] : null,
                    'birthday' => !empty($items['birthday']) ? date('Y-m-d', strtotime($items['birthday'])) : null,
                    'pass_sery' => !empty($items['pass_series']) ? $items['pass_series'] : null,
                    'pass_num' => !empty($items['pass_number']) ? $items['pass_number'] : null,
                ];
                if (!empty($out['PINFL'])) {
                    $session_travel_applicant['pinfl'] = $out['PINFL'];
                }
                $session['session_travel_applicant'] = $session_travel_applicant;
            }
        }
        return $out;

    }

    /**
     * Displays a single PolicyTravel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the PolicyTravel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PolicyTravel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PolicyTravel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('policy','The requested page does not exist.'));
    }

    /**
     * @param $h
     * @return PolicyTravel|null
     * @throws NotFoundHttpException
     */
    protected function findModelHash($h)
    {
        $id_model = _model_decrypt($h);
        /* @var $modelClassName PolicyTravel */
        $modelClassName = 'backend\modules\policy\models\\'.$id_model['formName'];
        if (($model = $modelClassName::findOne($id_model['id'])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('policy','The requested page does not exist.'));
    }

    public function actionMyMethod()
    {
        Yii::warning("\nGET\n");
        Yii::warning(Yii::$app->request->get());
        Yii::warning("\nPOST\n");
        Yii::warning(Yii::$app->request->post());
        Yii::warning("\nRAWBODY\n");
        Yii::warning(Yii::$app->request->getRawBody());
        Yii::warning("\nBodyParams\n");
        Yii::warning(Yii::$app->request->bodyParams);

        return $this->render('view', [
            'model' => $this->findModel(29),
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
        return null;
    }

}
