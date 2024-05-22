<?php
namespace frontend\controllers;

use backend\models\comment\Comments;
use backend\models\insurance\InsuranceProduct;
use backend\models\menu\Menus;
use backend\models\page\Pages;
use backend\models\parts\HtmlParts;
use backend\models\post\PostCategories;
use backend\models\post\Posts;
use backend\models\review\Contact;
use backend\models\review\Subscribe;
use backend\models\sliders\Sliders;
use backend\modules\handbook\models\HandbookLegalType;
use backend\modules\news\models\News;
use backend\modules\news\models\NewsCategory;
use backend\modules\news\models\NewsSearch;
use common\components\AuthHandler;
use common\models\Settings;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use frontend\widgets\SubscribeWidget;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($slug=null)
    {

        $modelVote = new Posts();
        $modelContact = new Contact(['scenario' => Contact::SCENARIO_FEEDBACK]);
        $online_voted = false;
        if (Yii::$app->session->get('online_voting')) {
            $online_voted = true;
        }
        if ($modelVote->load(Yii::$app->request->post()) && ($post = Yii::$app->request->post()) != null && !$online_voted) {
            $modelVote = Posts::findOne($post['Posts']['id']);
            $modelVote->views += 1;
            if ($modelVote->save()) {
                Yii::$app->session->set('online_voting', true);
                Yii::$app->session->addFlash('success', Yii::t('frontend','So’rovda qatnashganingiz uchun rahmat!'));
                $online_voted = true;
                return $this->refresh();
            } else {
                $title = 'SiteController index ModelVote save error';
                _send_error($title,json_encode(['error' => $modelVote->errors], JSON_UNESCAPED_UNICODE));
            }
        }

        return $this->render('index', [
            'title' => Settings::getSettingValue(Settings::KEY_SITE_NAME),
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
            'metaKeywords' => $this->getSettingValue('home_page_meta_keywords'),
            'metaDescription' => $this->getSettingValue('home_page_meta_description'),
            'footerText' => $this->getSettingValue('home_page_footer_text'),
            'slider' => Sliders::findOne(['key' => 'home_main_slider', 'status' => Sliders::STATUS_ACTIVE]),
            'legal_type_list' => HandbookLegalType::_getItemsList(),
            'product_list_main' => InsuranceProduct::_getHomeMainItems(),
            'home_about_us_part' => HtmlParts::getItemByKey(HtmlParts::HOME_ABOUT_US_PART),
            'why_choose_us' => PostCategories::getItemByKey(PostCategories::KEY_WHY_CHOOSE_US),
            'our_services' => PostCategories::getItemByKey(PostCategories::OUR_ADVANTAGE),
            'companies_served' => PostCategories::getItemByKey(PostCategories::KEY_COMPANIES_SERVED),
            'partners' => PostCategories::getItemByKey(PostCategories::KEY_CLIENTS),
            'comments' => Comments::_getHomeCommentItems(),
            'latest_news' => PostCategories::getItemByKey(PostCategories::KEY_NEWS),
            'online_voted' => $online_voted,
            'modelVote' => $modelVote,
            'online_voting' => PostCategories::getItemByKey(PostCategories::KEY_ONLINE_VOTING),
            'modelFeedback' => $modelContact,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
                'modelPage' => $this->findPage('contact'),
                'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
            ]);
        }
    }
    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionFeedback()
    {
        $model = new Contact(['scenario' => Contact::SCENARIO_FEEDBACK]);

        $message = null;
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->type = Contact::TYPE_FEEDBACK;
            if ($model->save()) {
                $message_tmp = Yii::t('frontend','Thank you for contacting us. We will respond to you as soon as possible.');
                $message_tmp .= Yii::t('frontend','Your application ID: {id}.', ['id' => $model->id]);
                $message = [
                    'type' => 'success',
                    'message' => $message_tmp,
                ];
                Yii::$app->session->addFlash('success', $message_tmp);
                $model = new Contact(['scenario' => Contact::SCENARIO_FEEDBACK]);

            } else {
                $message_tmp = _generate_error($model->errors).Yii::t('frontend','There was an error sending your message.');
                $message = [
                    'type' => 'error',
                    'message' => $message_tmp,
                ];
                Yii::$app->session->addFlash('error', _generate_error($model->errors));
                Yii::$app->session->addFlash('error', Yii::t('frontend','There was an error sending your message.'));
            }

            return $this->renderAjax('feedback', [
                'model' => $model,
                'message' => $message,
                'ajax' => true,
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->type = Contact::TYPE_FEEDBACK;
            if ($model->save()) {
                $message_tmp = Yii::t('frontend','Thank you for contacting us. We will respond to you as soon as possible.');
                $message_tmp .= Yii::t('frontend','Your application ID: {id}.', ['id' => $model->id]);
                $message = [
                    'type' => 'success',
                    'message' => $message_tmp,
                ];
                Yii::$app->session->addFlash('success', $message_tmp);
                $model = new Contact(['scenario' => Contact::SCENARIO_FEEDBACK]);

            } else {
                Yii::$app->session->addFlash('error', _generate_error($model->errors));
                Yii::$app->session->addFlash('error', 'There was an error sending your message.');
            }

            return $this->render('feedback', [
                'model' => $model,
                'message' => $message,
                'ajax' => null,
            ]);
        }

        return $this->render('feedback', [
            'model' => $model,
            'message' => null,
            'ajax' => null,
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
        else
            throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }
    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionSitemap()
    {
        $menu = Menus::findOne(['key' => 'front_header', 'status' => Menus::STATUS_ACTIVE]);
        $menuItems = $menu->getMenuItemsActive()->all();

        return $this->render('sitemap', [
            'menuItems' => $menuItems,
        ]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->signup()) {
                Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
                return $this->goHome();
            } else {
                Yii::$app->session->addFlash('error', _generate_error($model->errors));
            }
        }

        return $this->render('signup', [
            'model' => $model,
            'offerText' => Settings::getSettingValue('offer_text'),
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

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
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }


    public function actionSubscribe()
    {
        $model = new Subscribe();
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->findByEmail($model->email)) {
                $model->status = 1;
                if ($model->save()) {
                    $session->setFlash('success',Yii::t('app','Thank you for Subscribing!'));
                    return SubscribeWidget::widget([
                        'model' => $model,
                        'type' => 'success',
                        'message' => Yii::t('app','Thank you for Subscribing!'),
                    ]);
                } else {
                    foreach ($model->getErrors() as $error) {
                        if (is_array($error)) {
                            foreach ($error as $err) {
                                $errors .= $err;
                            }
                        } else {
                            $errors .= $error;
                        }
                    }
                    $session->setFlash('error',$errors);
                    return SubscribeWidget::widget([
                        'model' => $model,
                        'type' => 'error',
                        'message' => $errors,
                    ]);
                }
            } else {

                $session->setFlash('info',Yii::t('app','You have already subscribed'));
                return SubscribeWidget::widget([
                    'model' => $model,
                    'type' => 'info',
                    'message' => Yii::t('app','You have already subscribed'),
                ]);
            }

        } else {
            return SubscribeWidget::widget([
                'model' => $model,
            ]);
        }
    }

    /**
     * Find Settings model.
     * @param string $key
     * @return string|null
     */
    protected function getSettingValue($key)
    {
        return Settings::getValueByKey($key);
    }

    /**
     * Find Categories model.
     * @param string $slug
     * @return NewsCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelCategory($slug)
    {
        if (($model = NewsCategory::findOne(['slug' => $slug, 'status' => NewsCategory::STATUS_ACTIVE])) !== null)
            return $model;
        else
            throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }

}
