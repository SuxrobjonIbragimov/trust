<?php

namespace backend\controllers;

use backend\models\product\Products;
use Yii;
use yii\data\ActiveDataProvider;
use yii\imagine\Image;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use backend\models\post\Posts;
use backend\models\post\PostsSearch;
use backend\models\post\ProductToPost;
use backend\models\post\PostCategories;
use backend\models\post\PostCategoriesSearch;
use backend\models\product\search\ProductsSearch;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * PostController implements the CRUD actions for PostCategories model.
 */
class PostController extends Controller
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
     * Lists all PostCategories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostCategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PostCategories model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new PostsSearch(['category_id' => $id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new PostCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PostCategories();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->addFlash('error', _generate_error($model->errors));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PostCategories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->addFlash('error', _generate_error($model->errors));
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PostCategories model.
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
     * Finds the PostCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PostCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostCategories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /* ********** Posts Model ********** */

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
     * @param integer $category_id
     * @return mixed
     */
    public function actionCreateItem($category_id)
    {
        $modelCategory = $this->findModel($category_id);
        $key_type = PostCategories::getKeyTypeArray($modelCategory->key);
        $model = new Posts(['category_id' => $category_id, 'category_key' => $key_type]);

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->published_date)) {
                $model->published_date = date('Y-m-d',strtotime($model->published_date));
            }
            if ($model->save()) {
                return $this->redirect(['view-item', 'id' => $model->id]);
            } else {
                Yii::$app->session->addFlash('error', _generate_error($model->errors));
            }
        }
        return $this->render('item/create', [
            'model' => $model,
            'modelCategory' => $modelCategory,
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
        $modelCategory = $this->findModel($model->category_id);
        $key_type = PostCategories::getKeyTypeArray($modelCategory->key);
        $model->category_key = $key_type;

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->published_date)) {
                $model->published_date = date('Y-m-d',strtotime($model->published_date));
            }
            if ($model->save()) {
                return $this->redirect(['view-item', 'id' => $model->id]);
            } else {
                Yii::$app->session->addFlash('error', _generate_error($model->errors));
            }
        }
        return $this->render('item/update', [
            'model' => $model,
            'modelCategory' => $modelCategory,
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
        $categoryId = $model->category_id;
        $model->delete();

        return $this->redirect(['view', 'id' => $categoryId]);

    }

    /**
     * Delete or Update an existing Posts model.
     * The browser will be redirected to the 'index' page.
     * @param integer $category_id
     * @return mixed
     */
    public function actionApplyItems($category_id)
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post['selection'])) {

                $condition = ['id' => array_map(function ($value) {
                    return (int)$value;
                }, $post['selection'])];

                switch ($post['action']) {
                    case 'deactivate':
                        Posts::updateAll(['status' => Posts::STATUS_INACTIVE], $condition);
                        Yii::$app->session->setFlash('warning', Yii::t('yii', 'Selected items <b>DEACTIVATED</b>.'));
                        break;
                    case 'activate':
                        Posts::updateAll(['status' => Posts::STATUS_ACTIVE], $condition);
                        Yii::$app->session->setFlash('success', Yii::t('yii', 'Selected items <b>ACTIVATED</b>.'));
                        break;
                    case 'delete':
                        Posts::deleteAll($condition);
                        Yii::$app->session->setFlash('error', Yii::t('yii', 'Selected items <b>DELETED</b>.'));
                        break;
                }
            }
        }

        return $this->redirect(['view', 'id' => $category_id]);
    }

    /**
     * Finds the Posts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Posts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelItem($id)
    {
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * ProductToPost
     * @param $id
     * @return \yii\web\Response
     */
    public function actionProductToPost($id)
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post['selection'])) {
                $productIds = array_map(function ($value) {
                    return (int)$value;
                }, $post['selection']);

                if (!empty($productIds)) {
                    ProductToPost::deleteAll(['post_id' => $id, 'product_id' => $productIds]);
                    foreach ($productIds as $productId) {
                        $relation = new ProductToPost([
                            'product_id' => $productId,
                            'post_id' => $id
                        ]);
                        $relation->save();
                    }
                }
            }
        }

        return $this->redirect(['view-item', 'id' => $id]);
    }


    /**
     * Upload Post Image.
     * @return object
     */
    public function actionUploadImage()
    {
        $data = [];

        if ($file_image = UploadedFile::getInstancesByName('_image')) {
            foreach ($file_image as $file) {
                $folder = '/post/images/';
                $directory = Yii::getAlias('@uploadsPath'.$folder);
                if (!is_dir($directory)) {
                    \yii\helpers\FileHelper::createDirectory($directory);
                }
                $ext = pathinfo($file->name, PATHINFO_EXTENSION);
                $name = pathinfo($file->name, PATHINFO_FILENAME);
                $generateName = Yii::$app->security->generateRandomString() . ".{$ext}";
                $path = Yii::getAlias('@uploadsPath') . $folder . $generateName;

                if ($file->saveAs($path)) {
                    $path = Yii::getAlias('@uploadsUrl') . $folder . $generateName;
                    $data = [
                        'generate_name' => $generateName,
                        'name' => $name,
                        'path' => $path,
                    ];
                }
            }
        }

        return json_encode($data);
    }

    /**
     * Delete Post Image.
     * @return object
     */
    public function actionDeleteImage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ($post = Yii::$app->request->post()) ? $post['key'] : null;
    }


}
