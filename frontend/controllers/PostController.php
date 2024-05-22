<?php

namespace frontend\controllers;

use backend\models\post\PostsSearch;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\models\post\Posts;
use backend\models\post\PostCategories;

class PostController extends Controller
{
    /**
     * Post Category View
     * @param $slug
     * @return mixed
     */
    public function actionCategory($slug)
    {
        $model = $this->findPostCategory($slug);
        $filterData = Posts::find()
            ->select("EXTRACT(YEAR FROM published_date) as p_year")
            ->where([
                'category_id' => $model->id,
                'status' => Posts::STATUS_ACTIVE,
            ])
            ->distinct()
            ->orderBy(['p_year' => SORT_DESC])
            ->asArray()->indexBy('p_year')
            ->all();
        $y=null;
        if (!empty(Yii::$app->request->get('y'))) {
            $y=intval(Yii::$app->request->get('y'));
        }
        return $this->render('category', [
            'model' => $model,
            'filterData' => $filterData,
            'y' => $y,
            'dataProvider' => $model->getPostsDataProvider(),
        ]);
    }

    /**
     * Post View
     * @param $slug
     * @return mixed
     */
    public function actionView($slug)
    {
        return $this->render('view', [
            'model' => $this->findPost($slug)
        ]);
    }

    /**
     * Displays Search Page.
     * @return mixed
     */
    public function actionSearch()
    {
        $searchModel = new PostsSearch();
        $text = '';

        if ($get = Yii::$app->request->get()) {
            if (strlen($text = strip_tags(trim($get['text']))) >= 2)
                $searchModel->title = $text;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['title' => SORT_ASC];
        return $this->render('search', [
            'dataProvider' => $dataProvider,
            'text' => $text,
            'pagination' => $dataProvider->pagination,
        ]);
    }

    /**
     * Ajax Product List.
     * @param string $q
     */
    public function actionPostList($q = null)
    {
        $out = [];

        $models = Posts::find()
            ->where(['status' => [Posts::STATUS_ACTIVE,]])
            ->andWhere(['ILIKE', 'title', $q])
            ->orderBy('title')
            ->limit(1000)
            ->all();

        /** @var $model Posts */
        foreach ($models as $model) {
            $out[] = [
                'key' => $model->slug,
                'value' => $model->title
            ];
        }

        return Json::encode($out);
    }

    /**
     * Find PostCategories model.
     * @param string $slug
     * @return PostCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPostCategory($slug)
    {
        if (($model = PostCategories::findOne(['slug' => $slug, 'status' => PostCategories::STATUS_ACTIVE])) !== null)
            return $model;
        else
            throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
    }

    /**
     * Find Posts model.
     * @param string $slug
     * @return Posts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPost($slug)
    {
        if (($model = Posts::findOne(['slug' => $slug, 'status' => Posts::STATUS_ACTIVE])) !== null) {
            $model->views += 1;
            $model->save();

            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
        }
    }

}
