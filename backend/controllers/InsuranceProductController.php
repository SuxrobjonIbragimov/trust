<?php

namespace backend\controllers;

use backend\models\insurance\InsuranceProductItem;
use backend\models\insurance\InsuranceProductItemSearch;
use Yii;
use backend\models\insurance\InsuranceProduct;
use backend\models\insurance\InsuranceProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InsuranceProductController implements the CRUD actions for InsuranceProduct model.
 */
class InsuranceProductController extends Controller
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
     * Lists all InsuranceProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InsuranceProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all InsuranceProduct models.
     * @return mixed
     */
    public function actionLegalType($type = 1)
    {
        $searchModel = new InsuranceProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InsuranceProduct model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new InsuranceProductItemSearch(['insurance_product_id' => $id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new InsuranceProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InsuranceProduct();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing InsuranceProduct model.
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
     * Deletes an existing InsuranceProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->setStatusDeleted();

        return $this->redirect(['index']);
    }

    /**
     * Finds the InsuranceProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InsuranceProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InsuranceProduct::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('model', 'The requested page does not exist.'));
    }


    /* ********** Items Model ********** */

    /**
     * Displays a single Posts model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewItem($id)
    {
        $model = $this->findModelItem($id);

        return $this->render('item/view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Posts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $insurance_product_id
     * @return mixed
     */
    public function actionCreateItem($insurance_product_id)
    {
        $this->findModel($insurance_product_id);
        $model = new InsuranceProductItem(['insurance_product_id' => $insurance_product_id]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view-item', 'id' => $model->id]);
            } else {
                Yii::$app->session->addFlash('error', _generate_error($model->errors));
            }
        }
        return $this->render('item/create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Posts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateItem($id)
    {
        $model = $this->findModelItem($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view-item', 'id' => $model->id]);
            } else {
                Yii::$app->session->addFlash('error', _generate_error($model->errors));
            }
        }
        return $this->render('item/update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Posts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteItem($id)
    {
        $model = $this->findModelItem($id);
        $categoryId = $model->insurance_product_id;
        $model->delete();

        return $this->redirect(['view', 'id' => $categoryId]);

    }

    /**
     * Delete or Update an existing Posts model.
     * The browser will be redirected to the 'index' page.
     * @param integer $insurance_product_id
     * @return mixed
     */
    public function actionApplyItems($insurance_product_id)
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post['selection'])) {

                $condition = ['id' => array_map(function ($value) {
                    return (int)$value;
                }, $post['selection'])];

                switch ($post['action']) {
                    case 'deactivate':
                        InsuranceProductItem::updateAll(['status' => InsuranceProductItem::STATUS_INACTIVE], $condition);
                        Yii::$app->session->setFlash('warning', Yii::t('yii', 'Selected items <b>DEACTIVATED</b>.'));
                        break;
                    case 'activate':
                        InsuranceProductItem::updateAll(['status' => InsuranceProductItem::STATUS_ACTIVE], $condition);
                        Yii::$app->session->setFlash('success', Yii::t('yii', 'Selected items <b>ACTIVATED</b>.'));
                        break;
                    case 'delete':
                        InsuranceProductItem::deleteAll($condition);
                        Yii::$app->session->setFlash('error', Yii::t('yii', 'Selected items <b>DELETED</b>.'));
                        break;
                }
            }
        }

        return $this->redirect(['view', 'id' => $insurance_product_id]);
    }

    /**
     * Finds the Posts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InsuranceProductItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelItem($id)
    {
        if (($model = InsuranceProductItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
