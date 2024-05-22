<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use backend\models\menu\Menus;
use backend\models\menu\MenusSearch;
use backend\models\menu\MenuItems;
use backend\models\menu\MenuItemsSearch;

/**
 * MenuController implements the CRUD actions for Menus model.
 */
class MenuController extends Controller
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
     * Lists all Menus models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menus model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new MenuItemsSearch(['menu_id' => $id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataTree' => $this->getMenuItemsTree($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Menus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menus();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Menus model.
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
     * Deletes an existing Menus model.
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
     * Delete or Update an existing Menus model.
     * The browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionApply()
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post['selection'])) {

                $condition = ['id' => array_map(function ($value) {
                    return (int)$value;
                }, $post['selection'])];

                switch ($post['action']) {
                    case 'deactivate':
                        Menus::updateAll(['status' => Menus::STATUS_INACTIVE], $condition);
                        Yii::$app->session->setFlash('warning', Yii::t('yii', 'Selected items <b>DEACTIVATED</b>.'));
                        break;
                    case 'activate':
                        Menus::updateAll(['status' => Menus::STATUS_ACTIVE], $condition);
                        Yii::$app->session->setFlash('success', Yii::t('yii', 'Selected items <b>ACTIVATED</b>.'));
                        break;
                    case 'delete':
                        Menus::deleteAll($condition);
                        Yii::$app->session->setFlash('error', Yii::t('yii', 'Selected items <b>DELETED</b>.'));
                        break;
                }
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /* ********** MenuItems Model ********** */

    /**
     * Displays a single MenuItems model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewItem($id)
    {
        return $this->render('item/view', [
            'model' => $this->findModelItem($id),
        ]);
    }

    /**
     * Creates a new MenuItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $menu_id
     * @return mixed
     */
    public function actionCreateItem($menu_id)
    {
        $this->findModel($menu_id);
        $model = new MenuItems();
        $model->menu_id = $menu_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-item', 'id' => $model->id]);
        } else {
            return $this->render('item/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MenuItems model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateItem($id)
    {
        $model = $this->findModelItem($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-item', 'id' => $model->id]);
        } else {
            return $this->render('item/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MenuItems model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteItem($id)
    {
        $model = $this->findModelItem($id);
        $menu_id = $model->menu_id;
        $model->delete();

        return $this->redirect(['view', 'id' => $menu_id]);
    }

    /**
     * Delete or Update an existing MenuItems model.
     * The browser will be redirected to the 'index' page.
     * * @param integer $menu_id
     * @return mixed
     */
    public function actionApplyItems($menu_id)
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post['selection'])) {

                $condition = ['id' => array_map(function ($value) {
                    return (int)$value;
                }, $post['selection'])];

                switch ($post['action']) {
                    case 'deactivate':
                        MenuItems::updateAll(['status' => MenuItems::STATUS_INACTIVE], $condition);
                        Yii::$app->session->setFlash('warning', Yii::t('yii', 'Selected items <b>DEACTIVATED</b>.'));
                        break;
                    case 'activate':
                        MenuItems::updateAll(['status' => MenuItems::STATUS_ACTIVE], $condition);
                        Yii::$app->session->setFlash('success', Yii::t('yii', 'Selected items <b>ACTIVATED</b>.'));
                        break;
                    case 'delete':
                        MenuItems::deleteAll($condition);
                        Yii::$app->session->setFlash('error', Yii::t('yii', 'Selected items <b>DELETED</b>.'));
                        break;
                }
            }
        }

        return $this->redirect(['view', 'id' => $menu_id]);
    }

    /**
     * Ajax action DragDrop.
     * @return bool
     */
    public function actionDragDrop()
    {
        if ($post = Yii::$app->request->post()) {

            $hitMode = (string)$post['hit_mode'];
            $id = (int)$post['id'];
            $otherId = (int)$post['other_id'];

            $model = $this->findModelItem($id);
            $otherModel = $this->findModelItem($otherId);
            $weight = $otherModel->weight;
            $parentId = $otherModel->parent_id;

            if ($hitMode != 'over') {
                MenuItems::updateAllCounters(['weight' => 1], ['and',
                    ['menu_id' => $model->menu_id],
                    ['parent_id' => $parentId],
                    ['>', 'weight', $weight],
                    ['!=', 'id', $id],
                ]);
            }

            if ($hitMode == 'over') {
                $model->parent_id = $otherId;

            } elseif ($hitMode == 'after') {
                $model->parent_id = $parentId;

                if ($id < $otherId) {
                    $model->weight = $weight + 1;
                } else {
                    $model->weight = $weight;
                }

                MenuItems::updateAllCounters(['weight' => 1], ['and',
                    ['menu_id' => $model->menu_id],
                    ['parent_id' => $parentId],
                    ['=', 'weight', $weight],
                    ['!=', 'id', $id],
                    ['>', 'id', $otherId],
                ]);

            } elseif ($hitMode == 'before') {
                $model->parent_id = $parentId;
                $model->weight = $weight;

                MenuItems::updateAllCounters(['weight' => 1], ['and',
                    ['menu_id' => $model->menu_id],
                    ['parent_id' => $parentId],
                    ['=', 'weight', $weight],
                    ['!=', 'id', $id],
                    ['>=', 'id', $otherId],
                ]);

            }

            $model->save();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Finds the MenuItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelItem($id)
    {
        if (($model = MenuItems::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * @param integer $menu_id
     * @return array
     */
    protected function getMenuItemsTree($menu_id)
    {
        $data = [];

        if (!empty($models = MenuItems::find()->where(['menu_id' => $menu_id, 'parent_id' => NULL])->orderBy(['weight' => SORT_ASC])->all())) {
            /** @var MenuItems $model */
            foreach ($models as $model) {
                if (!empty($childs = $this->getMenuItemTreeChilds($model->id))) {
                    array_push($data, [
                        'title' => $model->label,
                        'key' => $model->id,
                        'folder' => true,
                        'children' => $childs
                    ]);
                } else {
                    array_push($data, [
                        'title' => $model->label,
                        'key' => $model->id,
                    ]);
                }
            }
        }

        return $data;
    }

    /**
     * @param integer $id
     * @return array
     */
    protected function getMenuItemTreeChilds($id)
    {
        $data = [];
        if (!empty($models = MenuItems::find()->where(['parent_id' => $id])->orderBy(['weight' => SORT_ASC])->all())) {
            /** @var MenuItems $model */
            foreach ($models as $model) {
                if (!empty($childs = $this->getMenuItemTreeChilds($model->id))) {
                    array_push($data, [
                        'title' => $model->label,
                        'key' => $model->id,
                        'folder' => true,
                        'children' => $childs
                    ]);
                } else {
                    array_push($data, [
                        'title' => $model->label,
                        'key' => $model->id,
                    ]);
                }
            }
        }

        return $data;
    }
}
