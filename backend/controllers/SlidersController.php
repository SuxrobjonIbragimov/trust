<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use backend\models\sliders\Sliders;
use backend\models\sliders\SliderItems;
use backend\models\sliders\SlidersSearch;
use backend\models\sliders\SliderItemsSearch;

/**
 * SlidersController implements the CRUD actions for Sliders model.
 */
class SlidersController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Sliders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SlidersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sliders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new SliderItemsSearch(['slider_id' => $id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Sliders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sliders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sliders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sliders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sliders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sliders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sliders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /* ********** SliderItems Model ********** */

    /**
     * Creates a new SliderItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $slider_id
     * @return mixed
     */
    public function actionCreateItem($slider_id)
    {
        $this->findModel($slider_id);
        $model = new SliderItems(['slider_id' => $slider_id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->slider_id]);
        } else {
            return $this->render('item/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SliderItems model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateItem($id)
    {
        $model = $this->findModelItem($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->slider_id]);
        } else {
            return $this->render('item/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SliderItems model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteItem($id)
    {
        $model = $this->findModelItem($id);
        $slider_id = $model->slider_id;
        $model->delete();

        return $this->redirect(['view', 'id' => $slider_id]);
    }

    /**
     * Delete or Update an existing SliderItems model.
     * The browser will be redirected to the 'index' page.
     * @param integer $slider_id
     * @return mixed
     */
    public function actionApplyItems($slider_id)
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post['selection'])) {

                $condition = ['id' => array_map(function ($value) {
                    return (int)$value;
                }, $post['selection'])];

                switch ($post['action']) {
                    case 'deactivate':
                        SliderItems::updateAll(['status' => SliderItems::STATUS_INACTIVE], $condition);
                        Yii::$app->session->setFlash('warning', Yii::t('yii', 'Selected items <b>DEACTIVATED</b>.'));
                        break;
                    case 'activate':
                        SliderItems::updateAll(['status' => SliderItems::STATUS_ACTIVE], $condition);
                        Yii::$app->session->setFlash('success', Yii::t('yii', 'Selected items <b>ACTIVATED</b>.'));
                        break;
                    case 'delete':
                        SliderItems::deleteAll($condition);
                        Yii::$app->session->setFlash('error', Yii::t('yii', 'Selected items <b>DELETED</b>.'));
                        break;
                }
            }
        }

        return $this->redirect(['view', 'id' => $slider_id]);
    }

    /**
     * Sortable SliderItems
     * @return boolean
     */
    public function actionSortableItems()
    {
        if ($post = Yii::$app->request->post()) {
            $data = $post['data'];
            foreach ($data as $key => $value) {
                $model = SliderItems::findOne(['id' => $value]);
                $model->weight = $key + 1;
                $model->save();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Finds the SliderItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SliderItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelItem($id)
    {
        if (($model = SliderItems::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
