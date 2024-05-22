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
 * @property string $password_repeat
 * @property boolean $offer
 *
 * Signup form
 */
class SignupForm extends Model
{
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
    public $password_repeat;
    public $verify_code;
    public $offer;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'trim'],
            [['username'], 'unique', 'targetClass' => '\common\models\User'],
//            [['username'], 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u'],
            [['username', 'first_name', 'last_name'], 'required'],
            [['username', 'first_name', 'last_name'], 'string', 'min' => 2, 'max' => 32],

            [['email'], 'trim'],
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 64],
            [['email'], 'unique', 'targetClass' => '\common\models\User'],

            [['password', 'password_repeat'], 'required'],
            [['password'], 'string', 'min' => 6],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false],

            [['image'], 'string', 'max' => 255],
            [['info'], 'string'],

            [['location_id'], 'integer'],
            [['phone'], 'required'],
            [['phone', 'address'], 'string', 'max' => 255],
            [['phone'], 'match', 'pattern' => '/^\+998(\d{2})-(\d{3})-(\d{4})$/'],

            ['verify_code', 'captcha'],
            ['offer', 'boolean'],
            ['offer', 'required', 'requiredValue' => true, 'message' => Yii::t('model', 'Это поле обязательно для заполнения.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('model', 'Имя пользователя'),
            'email' => Yii::t('model', 'Эл. адрес'),
            'first_name' => Yii::t('model', 'Имя'),
            'last_name' => Yii::t('model', 'Фамилия'),
            'image' => Yii::t('model', 'Изображение'),
            'phone' => Yii::t('model', 'Телефон'),
            'location_id' => Yii::t('model', 'Район'),
            'address' => Yii::t('model', 'Адрес'),
            'info' => Yii::t('model', 'Информация'),
            'password' => Yii::t('model', 'Пароль'),
            'password_repeat' => Yii::t('model', 'Повтор пароля'),
            'verify_code' => Yii::t('model', 'Код подтверждения'),
            'offer' => Yii::t('model', 'Я прочитал и принимаю'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->username = $this->email;

            if ($file = UploadedFile::getInstance($this, 'image')) {
                $ext = pathinfo($file->name, PATHINFO_EXTENSION);
                $generateName = $this->username . '_' . time() . ".{$ext}";
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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->image = $this->image;
        $user->phone = $this->phone;
        $user->address = $this->address;
        $user->info = $this->info;
        $user->status = User::STATUS_INACTIVE;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($user->save()) {
            $this->sendEmail($user);
            return $user;
        } else {
            Yii::$app->session->addFlash('error', _generate_error($user->errors));
            return null;
        }
    }

    /**
     * Sends an email with a link, for activate user.
     * @param User $user
     * @return bool whether the email was send
     */
    public function sendEmail($user)
    {
        try {
            return Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'confirmEmail-html', 'text' => 'confirmEmail-text'],
                    ['user' => $user]
                )
                ->setFrom([Yii::$app->params['noreplyEmail'] => Yii::$app->name])
                ->setTo($user->email)
                ->setSubject('Confirm email for ' . Yii::$app->name)
                ->send();
        } catch (\Swift_SwiftException $e) {

            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);
            Yii::$app->session->addFlash('error', $title);
//            Yii::$app->session->addFlash('error', $message);
            $message = [
                $title,
                Yii::t('app', 'Пожалуйста попробуйте позже'),
            ];
//            Yii::$app->session->addFlash('error', $message);
            $user->delete();
            return false;
        }
    }

    /**
     * Locations List
     * @return array
     */
    public function getLocationsList()
    {
        return [];
    }
}
