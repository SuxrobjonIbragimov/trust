<?php
namespace console\controllers;

use backend\modules\handbook\models\HandbookFondRegion;
use backend\modules\policy\models\HandbookCountry;
use backend\modules\policy\models\HandBookIns;
use backend\modules\policy\models\PolicyOsgo;
use backend\modules\policy\models\PolicyTravel;
use backend\modules\policy\models\PolicyTravelAbroadType;
use backend\modules\policy\models\PolicyTravelMultiDays;
use backend\modules\policy\models\PolicyTravelProgram;
use backend\modules\policy\models\PolicyTravelProgramToCountry;
use backend\modules\policy\models\PolicyTravelProgramToRisk;
use backend\modules\policy\models\PolicyTravelPurpose;
use backend\modules\policy\models\PolicyTravelRisk;
use common\library\payment\models\PaymentTransaction;
use PHPUnit\Util\Log\JSON;
use Yii;
use yii\console\Controller;
use yii\db\Expression;
use yii\httpclient\Client;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CheckInsController extends Controller
{
    public $lang = 'ru';
    public $param;
    public $type = 'additional';

    public function options($actionID)
    {
        return ['lang','param', 'type'];
    }

    public function optionAliases()
    {
        return ['l' => 'lang', 'p' => 'param', 't' => 'type'];
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $this->lang . "\n";
    }

    public function actionCheckAnketaStatus()
    {
        $null = new Expression('NULL');
        $a_day_ago = ($this->type=='force') ? 0 :  (time()-24*60*60);
        $models = PolicyOsgo::find()
            ->where(['is not', 'ins_anketa_id', $null])
            ->andWhere(['is', 'policy_series', $null])
            ->andWhere(['>', 'created_at', $a_day_ago])
            ->all();

        $modelsTravel = PolicyTravel::find()
            ->where(['is not', 'ins_anketa_id', $null])
            ->andWhere(['or',
                ['is', 'policy_series', $null],
                ['status' => PolicyOsgo::STATUS_NEW]
            ])
            ->andWhere(['>', 'created_at', $a_day_ago])
            ->all();

        $counter = 0;
        if (!empty($models)) {
            foreach ($models as $model) {
                if (!empty($model->policyOrder)) {
                    if (1) {
                        $handBookService = new HandBookIns();
                        $handBookService->setBaseUrl(EBASE_URL_INS);
                        $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
                        $handBookService->setMethod(HandBookIns::METHOD_OSGO_POST_CHECK_ANKETA_STATUS);

                        $handBookService->setParams([
                            'anketa_id' => !empty($model->ins_anketa_id) ? $model->ins_anketa_id : null,
                        ]);

                        $data = $handBookService->sendRequestIns();
                        if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                            if (!empty($data['POLICY_SERY']) && !empty($data['POLICY_NUMBER'])) {
                                $model->policy_series = trim($data['POLICY_SERY']);
                                $model->policy_number = trim($data['POLICY_NUMBER']);
                            } elseif(!empty($data['STATUS_PAYMENT']) && $data['STATUS_PAYMENT'] == PaymentTransaction::STATUS_PAYMENT_PAID && empty($data['POLICY_SERY'])) {
                                $title = "CheckInsController actionCheckAnketaStatus policy number is EMPTY";
                                Yii::warning("\n\n\n{$title}");
                                Yii::warning($data);
                                _send_error($title,json_encode($data, JSON_UNESCAPED_UNICODE));
                            }
                            $model->ins_log = json_encode($data);
                            if (!$model->save(false)) {
                                $title = "CheckInsController actionCheckAnketaStatus modelPolicy policy number not saved";
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
                                    $title = "CheckInsController actionCheckAnketaStatus order status not saved";
                                    Yii::warning("\n\n\n{$title}");
                                    Yii::warning($model->policyOrder->errors);
                                    _send_error($title,json_encode($model->policyOrder->errors, JSON_UNESCAPED_UNICODE));
                                }
                                $counter++;
                            }
                            if ($model->policyOrder->payment_status == PaymentTransaction::STATUS_PAYMENT_PAID) {
                                $response['status'] = true;
                                $response['message'] = Yii::t('frontend','Transaction success');
                            }
                        } elseif (!empty($data['ERROR'])) {
                            $response['ERROR'] = $data['ERROR'];
                            $response['ERROR_MESSAGE'] = !empty($data['ERROR_MESSAGE']) ? $data['ERROR_MESSAGE'] : Yii::t('policy','Data not found by pinfl');
                        }

                    }
                }
            }
        }

        if (!empty($modelsTravel)) {
            foreach ($modelsTravel as $model) {
                if (!empty($model->policyOrder)) {
                    if (1) {
                        $handBookService = new HandBookIns();
                        $handBookService->setBaseUrl(EBASE_URL_INS);
                        $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
                        $handBookService->setMethod(HandBookIns::METHOD_OSGO_POST_CHECK_ANKETA_STATUS);

                        $handBookService->setParams([
                            'anketa_id' => !empty($model->ins_anketa_id) ? $model->ins_anketa_id : null,
                        ]);

                        $data = $handBookService->sendRequestIns();
                        if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                            if (!empty($data['POLICY_SERY']) && !empty($data['POLICY_NUMBER'])) {
                                $model->ins_policy_id = !empty($data['POLICY_ID']) ? trim($data['POLICY_ID']) : $model->ins_policy_id;
                                $model->policy_series = trim($data['POLICY_SERY']);
                                $model->policy_number = trim($data['POLICY_NUMBER']);
                            } elseif(!empty($data['STATUS_PAYMENT']) && $data['STATUS_PAYMENT'] == PaymentTransaction::STATUS_PAYMENT_PAID  && $data['STATUS_POLICY'] == PolicyOsgo::STATUS_GIVEN && empty($data['POLICY_SERY'])) {
                                $title = "CheckInsController actionCheckAnketaStatus policy number is EMPTY";
                                Yii::warning("\n\n\n{$title}");
                                Yii::warning($data);
                                _send_error($title,json_encode($data, JSON_UNESCAPED_UNICODE));
                            }
                            $model->ins_log = json_encode($data);
                            if (!empty($data['STATUS_POLICY'])) {
                                $model->status = $data['STATUS_POLICY'];
                            }
                            if (!empty($data['PAYMENT_TYPE'])) {
                                $model->policyOrder->payment_type = PaymentTransaction::getPaymentTypeFromInsType($data['PAYMENT_TYPE']);
                                if (!$model->policyOrder->save()) {
                                    $title = "CheckInsController actionCheckAnketaStatus order payment_type not saved";
                                    Yii::warning("\n\n\n{$title}");
                                    Yii::warning($model->policyOrder->errors);
                                    _send_error($title,json_encode($model->policyOrder->errors, JSON_UNESCAPED_UNICODE));
                                }
                            }
                            if (!$model->save(false)) {
                                $title = "CheckInsController actionCheckAnketaStatus modelPolicy policy number not saved";
                                Yii::warning("\n\n\n{$title}");
                                Yii::warning($model->errors);
                                _send_error($title,json_encode($model->errors, JSON_UNESCAPED_UNICODE));
                            }
                            if (!empty($data['STATUS_PAYMENT']) && ($data['STATUS_PAYMENT'] != $model->policyOrder->payment_status)  && !empty($data['POLICY_ID'])) {
                                $model->policyOrder->payment_status = $data['STATUS_PAYMENT'];
                                if (!$model->policyOrder->save()) {
                                    $title = "CheckInsController actionCheckAnketaStatus order status not saved";
                                    Yii::warning("\n\n\n{$title}");
                                    Yii::warning($model->policyOrder->errors);
                                    _send_error($title,json_encode($model->policyOrder->errors, JSON_UNESCAPED_UNICODE));
                                }
                                $counter++;
                            }
                        }

                    }
                }
            }
        }

        $message = "CRON job worked\n{$counter} items updated\n";

        if ($counter) {
            sendTelegramData('sendMessage', [
                'chat_id' => CHAT_ID_ME,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]);
        }

        echo $message;
        return 0;
    }

    public function actionHandbookUpdater()
    {
        $date=date('d.m.Y H:i:s');
        $message_full = "CRON job worked at <b>{$date}</b>\n";
        $message = null;

        $handBookService = new HandBookIns();
        $handBookService->setBaseUrl(EBASE_URL_INS_TR);
        $handBookService->setLogin(TR_LOGIN);
        $handBookService->setPassword(TR_PASSWORD);

        // GET MULTI DAYS
        $handBookService->setMethod(HandBookIns::METHOD_GET_TRAVEL_MULTI_DAYS);
        $handBookService->setLanguage($this->lang);
        $data = $handBookService->sendRequestIns();
        if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
            $name_field = 'name';
            $name_field .= '_'.$this->lang;
            $counter = 0;
            foreach ($data as $key_r => $dataItem) {
                if (is_array($dataItem)){
                    $dataItem = array_change_key_case($dataItem,CASE_UPPER);
                }
                if (isset($dataItem['ID']) && !is_null($dataItem['ID'])) {
                    $ins_id = $dataItem['ID'];
                    $ins_name = trim($dataItem['NAME']);
                    $condition = ['ins_id' => $ins_id];
                    $model = PolicyTravelMultiDays::findOne($condition) ?:
                        new PolicyTravelMultiDays([
                            'ins_id' => $ins_id,
                            $name_field => $ins_name,
                            'days' => ($dataItem['DAYS']),
                            'weight' => $key_r,
                            'status' => PolicyTravelMultiDays::STATUS_ACTIVE,
                        ]);

                    $model->days = intval($dataItem['DAYS']);
                    $model->$name_field = $ins_name;
                    if ($model->isNewRecord || $model->isAttributeChanged($name_field) || $model->isAttributeChanged('days')) {
                        if ($model->save()) {
                            $counter++;
                        } else {
                            $title = "CheckInsController actionHandbookTravelIns MultiDays model saved";
                            _send_error($title,json_encode($model->errors, JSON_UNESCAPED_UNICODE));
                            echo json_encode($model->errors);
                        }
                    }
                }

            }
            if ($counter) {
                $message .="<b>{$counter}</b> travel MULTI DAYS items updated\n";
            }
        }

        // GET ABROAD TYPES
        $handBookService->setMethod(HandBookIns::METHOD_GET_TRAVEL_ABROAD_TYPE);
        $handBookService->setLanguage($this->lang);
        $data = $handBookService->sendRequestIns();
        if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
            $name_field = 'name';
            $name_field .= '_'.$this->lang;
            $counter = 0;
            foreach ($data as $key_r => $dataItem) {
                if (is_array($dataItem)){
                    $dataItem = array_change_key_case($dataItem,CASE_UPPER);
                }
                if (isset($dataItem['ID']) && !is_null($dataItem['ID'])) {
                    $ins_id = $dataItem['ID'];
                    $ins_name = trim($dataItem['NAME']);
                    $condition = ['ins_id' => $ins_id];
                    $model = PolicyTravelAbroadType::findOne($condition) ?:
                        new PolicyTravelAbroadType([
                            'ins_id' => $ins_id,
                            $name_field => $ins_name,
                            'weight' => $key_r,
                            'status' => PolicyTravelAbroadType::STATUS_ACTIVE,
                        ]);

                    $model->$name_field = $ins_name;
                    if ($model->isNewRecord || $model->isAttributeChanged($name_field)) {
                        if ($model->save()) {
                            $counter++;
                        } else {
                            $title = "CheckInsController actionHandbookTravelIns ABROAD TYPES model saved";
                            _send_error($title,json_encode($model->errors, JSON_UNESCAPED_UNICODE));
                            echo json_encode($model->errors);
                        }
                    }
                }

            }
            if ($counter) {
                $message .="<b>{$counter}</b> travel ABROAD TYPES items updated\n";
            }
        }

        // GET ABROAD_ACTIVITY
        $handBookService->setMethod(HandBookIns::METHOD_GET_TRAVEL_ABROAD_ACTIVITY);
        $handBookService->setLanguage($this->lang);
        $data = $handBookService->sendRequestIns();
        if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
            $name_field = 'name';
            $name_field .= '_'.$this->lang;
            $counter = 0;
            foreach ($data as $key_r => $dataItem) {
                if (is_array($dataItem)){
                    $dataItem = array_change_key_case($dataItem,CASE_UPPER);
                }
                if (isset($dataItem['ID']) && !is_null($dataItem['ID'])) {
                    $ins_id = $dataItem['ID'];
                    $ins_name = trim($dataItem['NAME']);
                    $condition = ['ins_id' => $ins_id];
                    $model = PolicyTravelPurpose::findOne($condition) ?:
                        new PolicyTravelPurpose([
                            'ins_id' => $ins_id,
                            $name_field => $ins_name,
                            'rate' => ($dataItem['RATE']),
                            'weight' => $key_r,
                            'status' => PolicyTravelPurpose::STATUS_ACTIVE,
                        ]);

                    $model->rate = floatval($dataItem['RATE']);
                    $model->$name_field = $ins_name;
                    if ($model->isNewRecord || $model->isAttributeChanged($name_field) || $model->isAttributeChanged('rate')) {
                        if ($model->save()) {
                            $counter++;
                        } else {
                            $title = "CheckInsController actionHandbookTravelIns ABROAD_ACTIVITY model saved";
                            _send_error($title,json_encode($model->errors, JSON_UNESCAPED_UNICODE));
                            echo json_encode($model->errors);
                        }
                    }
                }

            }
            if ($counter) {
                $message .="<b>{$counter}</b> travel ABROAD_ACTIVITY items updated\n";
            }
        }

        // POST ABROAD_PROGRAMS
        $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
        $handBookService->setMethod(HandBookIns::METHOD_POST_TRAVEL_PROGRAMS);
        $handBookService->setLanguage($this->lang);
        $handBookService->setParams([
            'countries' => [260],
        ]);

        $data = $handBookService->sendRequestIns();
        if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
            $name_field = 'name';
            $name_field .= '_'.$this->lang;
            $counter = 0;
            foreach ($data as $key_r => $dataItem) {
                if (is_array($dataItem)){
                    $dataItem = array_change_key_case($dataItem,CASE_UPPER);
                }
                if (isset($dataItem['ID']) && !is_null($dataItem['ID'])) {
                    $ins_id = $dataItem['ID'];
                    $ins_name = trim($dataItem['NAME']);
                    $condition = ['ins_id' => $ins_id];
                    $program_risks = [];
                    if (!empty($dataItem['MEDEX'])) {
                        $program_risks['MEDEX'] = intval($dataItem['MEDEX']);
                    }
                    if (!empty($dataItem['ACCIDENT'])) {
                        $program_risks['ACCIDENT'] = intval($dataItem['ACCIDENT']);
                    }
                    if (!empty($dataItem['OTV'])) {
                        $program_risks['TICKET'] = intval($dataItem['OTV']) - ($program_risks['MEDEX'] + $program_risks['ACCIDENT']);
                    }
                    $model = PolicyTravelProgram::findOne($condition) ?:
                        new PolicyTravelProgram([
                            'ins_id' => $ins_id,
                            $name_field => $ins_name,
                            'weight' => $key_r,
                            'status' => PolicyTravelProgram::STATUS_ACTIVE,
                        ]);

                    $model->{$name_field} = $ins_name;
                    if ($model->isNewRecord || $model->isAttributeChanged($name_field) || 1) {
                        if ($model->save()) {
                            $changed = false;
                            foreach ($program_risks as $index_p => $risk_value) {
                                $modelTravelRisk = PolicyTravelRisk::findOne(['key' => $index_p]);
                                if (!empty($modelTravelRisk->id)) {
                                    $condition = ['policy_travel_program_id' => $model->id, 'policy_travel_risk_id' => $modelTravelRisk->id];
                                    $modelPR = PolicyTravelProgramToRisk::findOne($condition) ?:
                                        new PolicyTravelProgramToRisk([
                                            'policy_travel_risk_id' => $modelTravelRisk->id,
                                            'policy_travel_program_id' => $model->id,
                                            'value' => $risk_value,
                                        ]);

                                    $modelPR->value = $risk_value;
                                    if ($modelPR->isNewRecord || $modelPR->isAttributeChanged('value')) {
                                        if (!$modelPR->save()) {
                                            $title = "CheckInsController actionHandbookTravelIns PolicyTravelProgramToRisk model saved";
                                            _send_error($title,json_encode($modelPR->errors, JSON_UNESCAPED_UNICODE));
                                            echo json_encode($modelPR->errors);
                                        }
                                        $changed =true;
                                    }
                                }
                            }

                            if ($changed) {
                                $counter++;
                            }
                        } else {
                            $title = "CheckInsController actionHandbookTravelIns ABROAD_PROGRAMS model saved";
                            _send_error($title,json_encode($model->errors, JSON_UNESCAPED_UNICODE));
                            echo json_encode($model->errors);
                        }
                    }
                }

            }
            if ($counter) {
                $message .="<b>{$counter}</b> travel ABROAD_PROGRAMS items updated\n";
            }
        }


        // GET TRAVEL_COUNTRIES
        $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_GET);
        $handBookService->setMethod(HandBookIns::METHOD_GET_TRAVEL_COUNTRIES);
        $handBookService->setLanguage($this->lang);

        $data = $handBookService->sendRequestIns();
        if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
            $name_field = 'name';
            $name_field .= '_'.$this->lang;
            $counter = 0;
            foreach ($data as $key_r => $dataItem) {
                if (is_array($dataItem)){
                    $dataItem = array_change_key_case($dataItem,CASE_UPPER);
                }
                if (isset($dataItem['ID']) && !is_null($dataItem['ID'])) {
                    $suffix = ', SCHENGEN';
                    switch ($this->lang) {
                        case 'uz' : $suffix = ', SHENGEN';
                            break;
                        case 'ru' : $suffix = ', ШЕНГЕН';
                            break;
                    }
                    $ins_id = $dataItem['ID'];
                    $ins_name = trim($dataItem['NAME']);
                    $country_programs = !empty($dataItem['PROGRAMS']) ? $dataItem['PROGRAMS'] : [];
                    $condition = ['ins_id' => $ins_id];
                    $is_shengen = false;
                    if (!empty($dataItem['SCHENGEN'])) {
                        $is_shengen = true;
                        $ins_name .= $suffix;
                    }
                    $model = HandbookCountry::findOne($condition) ?:
                        new HandbookCountry([
                            'ins_id' => $ins_id,
                            $name_field => $ins_name,
                            'is_shengen' => $is_shengen,
                            'status' => HandbookCountry::STATUS_ACTIVE,
                        ]);

                    if (!empty($model)) {
                        $model->is_shengen = $is_shengen;
                        $model->$name_field = $ins_name;
                        if ($model->isNewRecord || $model->isAttributeChanged($name_field) || $model->isAttributeChanged('is_shengen')) {
                            if ($model->save()) {
                                if (!empty($country_programs)) {
                                    foreach ($country_programs as $index_p => $program_id) {
                                        $modelTravelProgram = PolicyTravelProgram::findOne(['ins_id' => $program_id]);
                                        if (!empty($modelTravelProgram->id)) {
                                            $condition = ['policy_travel_program_id' => $modelTravelProgram->id, 'country_id' => $model->id];
                                            $modelPC = PolicyTravelProgramToCountry::findOne($condition);
                                            if (empty($modelPC)) {
                                                $modelPC = new PolicyTravelProgramToCountry([
                                                    'country_id' => $model->id,
                                                    'policy_travel_program_id' => $modelTravelProgram->id,
                                                ]);
                                                if (!$modelPC->save()) {
                                                    $title = "CheckInsController actionHandbookTravelIns PolicyTravelProgramToCountry model saved";
                                                    _send_error($title,json_encode($modelPC->errors, JSON_UNESCAPED_UNICODE));
                                                    echo json_encode($modelPC->errors);
                                                }
                                            }
                                        }
                                    }
                                }
                                $counter++;
                            } else {
                                $title = "CheckInsController actionHandbookTravelIns TRAVEL_COUNTRIES model saved";
                                _send_error($title,json_encode($model->errors, JSON_UNESCAPED_UNICODE));
                                echo json_encode($model->errors);
                            }
                        }
                    }

                }

            }
            if ($counter) {
                $message .="<b>{$counter}</b> TRAVEL_COUNTRIES items updated\n";
            }
        }


        if ($message) {
            $message_full .= $message."\n".Yii::$app->name;
            sendTelegramData('sendMessage', [
                'chat_id' => CHAT_ID_ME,
                'text' => $message_full,
                'parse_mode' => 'HTML'
            ]);
        }
        echo $message_full;
        return 0;
    }

    public function actionHandbookRegionUpdater()
    {
        $regionUpdaterCount = 0;
        $districtUpdateCount = 0;
        $handbook = new HandBookIns();
        $handbook->setBaseUrl(EBASE_URL_INS_TR);
        $handbook->setLogin(TR_LOGIN);
        $handbook->setPassword(TR_PASSWORD);
        $handbook->setMethodRequest(HandBookIns::METHOD_REQUEST_GET);
        $handbook->setMethod(HandBookIns::METHOD_REFERENCE_REGIONS);
        $handbook->setLanguage($this->lang);
        $data = $handbook->sendRequestIns();
        if (!empty($data) && is_array($data) && empty($data['error'])){
            foreach ($data as $key_r => $item)
            {
                $nameField = 'name_'.$this->lang;
                $insID = $item['id'];
                $insName = $item['name'];
                $condition = ['ins_id' => $insID];
                $regionModel = HandbookFondRegion::findOne($condition) ?:
                    new HandbookFondRegion([
                         $nameField => $insName,
                         'ins_id' => $insID,
                         'parent_id' => null
                    ]);
                if ($regionModel->isNewRecord){
                    if (!$regionModel->save())
                    {
                        $title = "HandbookFondRegion has not region saved";
                        _send_error($title,json_encode($regionModel->errors,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                        echo json_encode($regionModel->errors,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                        exit();
                    }else{
                        $regionUpdaterCount++;
                    }
                }
                $regions = HandbookFondRegion::find()->andWhere(['parent_id' => null])->all();
                foreach ($regions as $region)
                {
                    /** @var HandbookFondRegion $region */
                    $queryParam = '?id='.$region->ins_id;
                    $handbook->setMethod(HandBookIns::METHOD_REFERENCE_REGIONS);
                    $districtsData = $handbook->sendRequestIns($queryParam);
                    if (!empty($districtsData) && is_array($districtsData) && empty($districtsData['error']))
                    {
                        foreach ($districtsData as $key_d => $districtItem)
                        {
                            $insID = $districtItem['id'];
                            $insName = $districtItem['name'];
                            $condition = ['ins_id' => $insID,'parent_id' => $region->id];
                            $districtModel = HandbookFondRegion::findOne($condition) ?:
                                new HandbookFondRegion([
                                    $nameField => $insName,
                                    'ins_id' => $insID,
                                    'parent_id' => $region->id
                                ]);
                            if ($districtModel->isNewRecord){
                                if (!$districtModel->save()){
                                    $title = "HandbookFondRegion has not district saved";
                                    _send_error($title,json_encode($regionModel->errors,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                                    echo json_encode($regionModel->errors,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                                    exit();
                                }else{
                                    $districtUpdateCount++;
                                }
                            }
                        }
                    }else{
                        $message = json_encode($data,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                        echo "District Not Found in ".$region->id."\n";
                        echo $message;
                        _send_error('District not found',$message);
                    }
                }
            }
        }else{
            $message = json_encode($data,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            echo "Regions Not Found \n";
            echo $message;
            _send_error('Regions not found',$message);
        }
        if ($regionUpdaterCount > 0)
        {
            $message = "HandBookRegion updater ".$regionUpdaterCount." region updated";
            echo $message;
        }
        if ($districtUpdateCount > 0){
            $message = "HandBookRegion updater ".$districtUpdateCount." district updated";
            echo $message;
        }
    }
}
