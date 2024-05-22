<?php

namespace frontend\models;

use backend\models\review\Contact;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $phone;
    public $subject;
    public $body;
    public $verifyCode;
    public $type;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'phone', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['type', 'safe'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => Yii::t('model','Verify Code'),
            'name' => Yii::t('models','Имя'),
            'email' => Yii::t('models','Почта'),
            'phone' => Yii::t('models','Номер телефона'),
            'subject' => Yii::t('models','Тема'),
            'body' => Yii::t('models','Сообшение'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }


    /**
     * @return Contact|false
     */
    public function save()
    {
        if ($this->validate()) {
            $contactModel = new Contact(['scenario' => Contact::SCENARIO_CONTACT]);
            $contactModel->full_name = $this->name;
            $contactModel->phone = clear_phone_full($this->phone);
            $contactModel->email = $this->email;
            $contactModel->subject = $this->subject;
            $contactModel->message = $this->body;
            $contactModel->type = $this->type;
            if (!$contactModel->save(false)) {
                Yii::$app->session->addFlash('error', _generate_error($contactModel->errors));
            } else {
                return $contactModel;
            }
        } else {
            Yii::$app->session->addFlash('error', _generate_error($this->errors));
        }

        return false;
    }
}
