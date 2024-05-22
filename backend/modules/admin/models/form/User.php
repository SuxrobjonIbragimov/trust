<?php

namespace backend\modules\admin\models\form;

use Yii;
use yii\base\Model;
use common\models\User as UserModel;
use backend\models\location\Locations;

/**
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $image
 * @property string $phone
 * @property integer $location_id
 * @property string $address
 * @property string $info
 * @property integer $status
 * @property integer $isNewRecord
 *
 * User Create Update Form
 */
class User extends Model
{
    public $id;
    public $username;
    public $email;
    public $first_name;
    public $last_name;
    public $image;
    public $phone;
    public $location_id;
    public $address;
    public $info;
    public $password;
    public $status;
    public $isNewRecord;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            [['username', 'email', 'password'], 'required', 'on' => 'create'],
            [['username'], 'unique', 'targetClass' => '\common\models\User', 'on' => 'create'],
//            [['username'], 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u'],
            [['username', 'first_name', 'last_name'], 'string', 'min' => 3, 'max' => 32],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 64],
            [['email'], 'unique', 'targetClass' => '\common\models\User', 'on' => 'create'],
            [['password'], 'string', 'min' => 6],
            [['status'], 'in', 'range' => [UserModel::STATUS_ACTIVE, UserModel::STATUS_INACTIVE]],
            [['image'], 'string', 'max' => 255],
            [['info'], 'string'],
            [['location_id'], 'integer'],
            [['phone'], 'required'],
            [['phone', 'address'], 'string', 'max' => 255],
            [['phone'], 'match', 'pattern' => '/^\+998(\d{2})-(\d{3})-(\d{4})$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('model', 'Username'),
            'email' => Yii::t('model', 'Email'),
            'first_name' => Yii::t('model', 'First name'),
            'last_name' => Yii::t('model', 'Last name'),
            'image' => Yii::t('model', 'Image'),
            'phone' => Yii::t('model', 'Phone'),
            'location_id' => Yii::t('model', 'Location'),
            'address' => Yii::t('model', 'Address'),
            'info' => Yii::t('model', 'Info'),
            'password' => Yii::t('model', 'Password'),
        ];
    }

    /**
     * Create user up.
     *
     * @return UserModel|null the saved model or null if saving fails
     */
    public function create()
    {
        if ($this->validate()) {
            $user = new UserModel();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->image = $this->image;
            $user->phone = $this->phone;
            $user->location_id = $this->location_id;
            $user->address = $this->address;
            $user->info = $this->info;
            $user->status = $this->status;
            $user->setPassword($this->password);
            $user->generateAuthKey();

            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Update user up.
     *
     * @param UserModel $user
     * @return UserModel|null the saved model or null if saving fails
     */
    public function update($user)
    {
        if ($this->validate()) {
            $user->username = $this->username;
            $user->email = $this->email;
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->image = $this->image;
            $user->phone = $this->phone;
            $user->location_id = $this->location_id;
            $user->address = $this->address;
            $user->info = $this->info;
            $user->status = $this->status;
            if ($this->password) {
                $user->setPassword($this->password);
                $user->generateAuthKey();
            }

            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Locations List
     * @return array
     */
    public function getLocationsList()
    {
        return Locations::getLocationsList();
    }
}
