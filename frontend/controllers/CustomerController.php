<?php

namespace frontend\controllers;

use common\models\Settings;
use frontend\models\Account;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use common\models\User;
use common\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\ResetPasswordForm;
use frontend\models\AccountUpdateForm;
use frontend\models\PasswordResetRequestForm;

class CustomerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'account', 'account-update', 'orders', 'order-view'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'account', 'account-update', 'orders', 'order-view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Logs in a user.
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
            return $this->goHome();

        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();
        $sessionId = $session->id;
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack('account');
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Signs user up.
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                $session = Yii::$app->session;
                if (!$session->isActive) $session->open();

                if ($model->sendEmail($user))
                    Yii::$app->session->setFlash('error', 'Check your email for further instructions.');
                else
                    Yii::$app->session->setFlash('error', 'Sorry, we are unable to confirm email for the provided email address.');

                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $model,
            'offerText' => Settings::getSettingValue('offer_text'),
        ]);
    }

    /**
     * Signup with Email Confirmation.
     * @param integer $id
     * @param string $key
     * @return mixed
     */
    public function actionConfirmEmail($id, $key)
    {
        if (($user = User::findOne(['id' => $id, 'auth_key' => $key, 'status' => User::STATUS_INACTIVE])) !== null) {
            $user->status = User::STATUS_ACTIVE;
            $user->generateAuthKey();

            if ($user->save()) {
                Yii::$app->getUser()->login($user);
                Yii::$app->getSession()->setFlash('error', 'Congratulations! You have successfully activated your account.');
            }
        } else {
            Yii::$app->getSession()->setFlash('error', 'Sorry, we cannot activate your account.');
        }

        return $this->goHome();
    }

    /**
     * Requests password reset.
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('error', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('error', 'New password saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Customer Account
     * @return mixed
     */
    public function actionAccount()
    {
        $user = User::findOne(Yii::$app->user->id);
        $model = new Account([
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'image' => $user->image,
            'address' => $user->address,
        ]);

        return $this->render('account', [
            'model' => $model,
        ]);
    }

    /**
     * Customer Account Update
     * @return mixed
     */
    public function actionAccountUpdate()
    {
        $user = User::findOne(Yii::$app->user->id);
        $model = new AccountUpdateForm([
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'image' => $user->image,
            'phone' => $user->phone,
            'location_id' => $user->location_id,
            'address' => $user->address,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->update($user->id)) {
            return $this->redirect('account');
        }

        return $this->render('accountUpdate', [
            'model' => $model,
        ]);
    }


}
