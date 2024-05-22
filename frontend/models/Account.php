<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Customer Account model.
 *
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $phone
 * @property string $image
 * @property string $district
 * @property string $address
 *
 */
class Account extends Model
{

    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $image;
    public $district;
    public $address;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('model', 'Эл. адрес'),
            'firstName' => Yii::t('model', 'Имя'),
            'lastName' => Yii::t('model', 'Фамилия'),
            'image' => Yii::t('model', 'Изображение'),
            'phone' => Yii::t('model', 'Телефон'),
            'district' => Yii::t('model', 'Район'),
            'address' => Yii::t('model', 'Адрес'),
        ];
    }

}