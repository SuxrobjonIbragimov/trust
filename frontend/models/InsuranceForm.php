<?php

namespace frontend\models;

use yii\base\Model;

class InsuranceForm extends Model
{
    // Customer Information
    public $customer_name;
    public $customer_inn;
    public $customer_oked;
    public $customer_mfo;
    public $customer_bank_name;
    public $customer_bank_rs;
    public $customer_address;
    public $customer_phone;
    public $customer_dir_name;

    // Stroy Information
    public $lot_id;
    public $dog_num;
    public $dog_date;
    public $stroy_name;
    public $stroy_price;
    public $current_year_price;

    // Insurance Information
    public $claim_id;
    public $ins_sum_otv;
    public $current_year_sum_otv;
    public $s_date;
    public $e_date;

    // Agent Information
    public $agent_inn;
    public $agent_name;


    public static function tableName()
    {

    }
    public function rules()
    {
        return [
            [['customer_name', 'customer_inn', 'customer_oked', 'customer_mfo', 'customer_bank_name', 'customer_bank_rs', 'customer_address', 'customer_phone', 'customer_dir_name',
                'lot_id', 'dog_num', 'dog_date', 'stroy_name', 'stroy_price', 'current_year_price',
                'claim_id', 'ins_sum_otv', 'current_year_sum_otv', 's_date', 'e_date',
                'agent_inn', 'agent_name'], 'required'],
            [['dog_date', 's_date', 'e_date'], 'safe'],
            [['stroy_price', 'current_year_price', 'ins_sum_otv', 'current_year_sum_otv'], 'number'],
            [['customer_inn', 'customer_oked', 'customer_mfo', 'claim_id', 'lot_id', 'agent_inn'], 'integer'],
            [['customer_phone'], 'string', 'max' => 15],
            [['customer_name', 'customer_bank_name', 'customer_bank_rs', 'customer_address', 'customer_dir_name', 'stroy_name', 'agent_name'], 'string', 'max' => 255],
        ];
    }
}