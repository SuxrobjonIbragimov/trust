<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\imagine\Image;
use common\models\User;
use yii\web\UploadedFile;
use backend\models\location\Locations;

/**
 *
 * @property string $password
 * @property string $password_new
 * @property string $password_repeat
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $image
 * @property string $phone
 * @property integer $location_id
 * @property string $address
 *
 * AccountUpdateForm form
 */
class AccountUpdateForm extends Model
{
    public $email;
    public $first_name;
    public $last_name;
    public $image;
    public $phone;
    public $location_id;
    public $address;
    public $password;
    public $password_new;
    public $password_repeat;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'string', 'max' => 64],
            [['first_name', 'last_name', 'password', 'phone'], 'required'],
            [['first_name', 'last_name'], 'string', 'min' => 2, 'max' => 32],
            [['password', 'password_new'], 'string', 'min' => 6],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password_new', 'skipOnEmpty' => false],
            [['image'], 'string', 'max' => 255],
            [['location_id'], 'integer'],
            [['phone', 'address'], 'string', 'max' => 255],
            [['phone'], 'match', 'pattern' => '/^\+998(\d{2})-(\d{3})-(\d{4})$/'],
            [['password'], 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный пароль.');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'first_name' => Yii::t('model', 'Имя'),
            'last_name' => Yii::t('model', 'Фамилия'),
            'image' => Yii::t('model', 'Изображение'),
            'phone' => Yii::t('model', 'Телефон'),
            'location_id' => Yii::t('model', 'Район'),
            'address' => Yii::t('model', 'Адрес'),
            'password' => Yii::t('model', 'Пароль'),
            'password_new' => Yii::t('model', 'Новый пароль'),
            'password_repeat' => Yii::t('model', 'Повторите новый пароль'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {

            if ($file = UploadedFile::getInstance($this, 'image')) {
                $ext = pathinfo($file->name, PATHINFO_EXTENSION);
                $generateName = $this->email . '_' . time() . ".{$ext}";
                $path = Yii::getAlias('@uploadsPath') . '/users/' . $generateName;
                if ($file->saveAs($path)) {
                    Image::thumbnail($path, 100, 100)
                        ->save(Yii::getAlias(Yii::getAlias('@uploadsPath') . '/users/thumb/' . $generateName), ['quality' => 50]);
                    $this->image = Yii::getAlias('@uploadsUrl') . '/users/thumb/' . $generateName;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Update user up.
     * @param integer $userId
     * @return bool
     */
    public function update($userId)
    {
        if (!$this->validate())
            return false;

        $user = User::findOne($userId);
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->phone = $this->phone;
        $user->location_id = $this->location_id;
        $user->address = $this->address;
        if ($this->image)
            $user->image = $this->image;
        if ($this->password_new)
            $user->setPassword($this->password_new);

        return $user->save() ? true : false;
    }

    /**
     * Locations List
     * @return array
     */
    public function getLocationsList()
    {
        return Locations::getLocationsList();
    }

    /**
     * Finds user bt email
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null)
            $this->_user = User::findByUsername($this->email);

        return $this->_user;
    }
}
