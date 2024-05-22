<?php

namespace backend\modules\telegram\controllers;

use backend\modules\telegram\models\BotUserMessage;
use Yii;
use backend\modules\telegram\models\BotUser;
use backend\modules\telegram\models\BotUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BotUserController implements the CRUD actions for BotUser model.
 */
class BotUserController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all BotUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BotUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BotUser model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BotUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BotUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BotUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BotUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BotUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BotUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BotUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('telegram', 'The requested page does not exist.'));
    }

    /**
     * Creates a new BotUserMessage model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionMessage($id=null,$param=null)
    {
        $model = new BotUser();
        if (!Yii::$app->user->can('accessAdministrator')) {
            $model = $this->findModel($id);
        }
        if (empty($param)) {
            $models = !empty($id) ? [$this->findModel($id)] : BotUser::find()->orderBy(['id' => SORT_ASC])->all();
        } else {
            $models = !empty($id) ? [$this->findModel($id)] : BotUser::find()->where(['current_step_type' => $param])->orderBy(['id' => SORT_ASC])->all();
        }
        $modelMessage = new BotUserMessage();

        if ($modelMessage->load(Yii::$app->request->post()) && !empty($models)) {
            $count = 0;
            $message = $modelMessage->message;
            foreach ($models as $modelBot) {
                $modelMessage = new BotUserMessage();
                $modelMessage->message = $message;
                $modelMessage->type = BotUserMessage::TYPE_OUT;
                $modelMessage->bot_user_id = $modelBot->id;
                if ($modelMessage->save()) {
                    $count++;
                } else {
                    Yii::$app->session->addFlash('error', _generate_error($modelMessage->errors));
                }
            }
            if ($count) {
                Yii::$app->session->addFlash('success', Yii::t('telegram','{count} - messages successfully send!',[
                    'count' => $count,
                ]));
                return $this->redirect(['index',]);
            }
        }

        return $this->render('message', [
            'model' => $model,
            'modelMessage' => $modelMessage,
        ]);
    }


}
