<?php
namespace backend\controllers;

use backend\models\comment\Comments;
use backend\models\comment\CommentsSearch;
use backend\models\review\Contact;
use backend\models\review\ContactSearch;
use backend\models\review\Subscribe;
use backend\models\review\SubscribeSearch;
use common\models\Settings;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->can('accessDashboard')) {
                return $this->goBack();
            } else {
                Yii::$app->user->logout();
                $model->addError('password', 'Incorrect username or password.');
            }
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Ajax Set Session SidebarCollapse.
     */
    public function actionSessionSidebar()
    {
        $session = Yii::$app->session;
        $session['sidebar'] = $session['sidebar'] ? false : true;
    }

    /**
     * Displays FileManges page.
     * @return string
     */
    public function actionFiles()
    {
        return $this->render('files');
    }


    /**
     * Displays Site Setting page.
     * @return mixed
     */
    public function actionSetting()
    {
        if ($post = Yii::$app->request->post()) {
            foreach ($post as $key => $value) {
                if (($setting = Settings::findOne(['key' => $key])) !== null && !empty($value)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            _cache_clear_expired(true);
            Yii::$app->session->setFlash('success', 'Settings saved.');
            return $this->refresh();
        }

        return $this->render('setting', [
            'model' => new Settings(),
            'models' => Settings::find()->all()
        ]);
    }

    /**
     * Action Create Site Setting.
     * @return mixed
     */
    public function actionSettingCreate()
    {
        $model = new Settings();
        ($model->load(Yii::$app->request->post()) && $model->save()) ?
            Yii::$app->session->setFlash('success', $model->label . ' field added.') :
            Yii::$app->session->setFlash('error', 'Field not added.');

        return $this->redirect(['setting']);
    }

    /**
     * Deletes an existing Setting model.
     * @param integer $id
     * @return mixed
     */
    public function actionSettingDelete($id)
    {
        Settings::findOne($id)->delete();
        Yii::$app->session->setFlash('warning', 'Field deleted.');
        _cache_clear_expired(true);

        return $this->redirect(['setting']);
    }

    /**
     * Lists all Comments models.
     * @return mixed
     */
    public function actionComments()
    {
        $searchModel = new CommentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('comments', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Ajax SetCommentStatus.
     */
    public function actionSetCommentStatus()
    {
        if ($post = Yii::$app->request->post()) {
            if ($model = Comments::findOne($post['id'])) {
                $model->status = $post['status'];
                if ($model->save())
                    return true;
            }
        }

        return false;
    }


    /**
     * Lists all Comments models.
     * @return mixed
     */
    public function actionSubscribes()
    {
        $searchModel = new SubscribeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('subscribes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Ajax SetCommentStatus.
     */
    public function actionSetSubscribeStatus()
    {
        if ($post = Yii::$app->request->post()) {
            if ($model = Subscribe::findOne($post['id'])) {
                $model->status = $post['status'];
                if ($model->save())
                    return true;
            }
        }

        return false;
    }

    /**
     * Lists all Contact models.
     * @return mixed
     */
    public function actionContacts()
    {
        $searchModel = new ContactSearch(['type' => Contact::TYPE_CONTACT]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('contact', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Contact models.
     * @return mixed
     */
    public function actionFeedback()
    {
        $searchModel = new ContactSearch(['type' => Contact::TYPE_FEEDBACK]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('feedback', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Contact models.
     * @return mixed
     */
    public function actionClaims()
    {
        $searchModel = new ContactSearch(['type' => Contact::TYPE_CLAIM]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('claim', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Ajax SetCommentStatus.
     */
    public function actionSetContactStatus()
    {
        if ($post = Yii::$app->request->post()) {
            if ($model = Contact::findOne($post['id'])) {
                $model->status = $post['status'];
                if ($model->save(false))
                    return true;
            }
        }

        return false;
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionContactTg($id)
    {

        if ($model = Contact::findOne($id)) {
            $model->sendNewContactMessage();
        }
        return $this->redirect(['/site/feedback'], 301);
    }

}
