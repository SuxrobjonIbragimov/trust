<?php

namespace frontend\controllers;

use backend\models\review\Comments;
use backend\modules\news\models\News;
use backend\modules\news\models\NewsCategory;
use backend\modules\news\models\NewsRibbon;
use backend\modules\news\models\NewsSearch;
use backend\modules\news\models\NewsToRibbon;
use common\models\LoginForm;
use frontend\models\CommentForm;
use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use common\models\Settings;
use backend\models\page\Pages;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    /**
     * Product Category Page.
     * @param string $slug
     * @return mixed
     */
    public function actionCategory($slug, $tag=null)
    {
        $category = ($slug == 'all') ? null : $this->findModelCategory($slug);

        $searchModel = new NewsSearch();

        $conditionPopular = [
            'status' => News::STATUS_ACTIVE,
        ];
        $productIds = [];
        if (($ribbon = NewsRibbon::findOne(['html_key' => NewsRibbon::RIBBON_POPULAR, 'status' => NewsRibbon::STATUS_ACTIVE])) !== null) {
            empty($productIds_rib = ArrayHelper::getColumn($ribbon->newsToRibbons, 'news_id')) ?
                $productIds_rib = [] :
                $conditionPopular['id'] = array_merge($productIds,$productIds_rib);
        }
        if ($category != null) {
            $searchModel->category_id = $category->id;
            if (($category = NewsCategory::findOne($category->id)) !== null) {
                empty($productIds_cat = ArrayHelper::getColumn($category->newsToCategories, 'news_id')) ?
                    $productIds_cat = [] :
                    $conditionPopular['id'] = array_merge($productIds,$productIds_cat);
            }
        }
        if ($tag !== null) {
            $searchModel->tags = $tag;
        }

        $popularModels = News::find()
            ->where($conditionPopular)
            ->limit(4)->all();

        $queryParams = Yii::$app->request->queryParams;

        $dataProvider = $searchModel->search($queryParams);
        $models = $searchModel->searchAll();

        $categories = NewsCategory::find()
            ->where([
                'status' => NewsCategory::STATUS_ACTIVE,
                'parent_id' => ($category == null) ? null : $category->parent_id
            ])
            ->orderBy(['weight' => SORT_ASC])
            ->all();

        $ribbons = ArrayHelper::map(NewsRibbon::findAll([
            'status' => NewsRibbon::STATUS_ACTIVE,
            'id' => array_unique(ArrayHelper::getColumn(NewsToRibbon::findAll([
                'news_id' => array_unique(ArrayHelper::getColumn($models, 'id'))
            ]), 'ribbon_id'))
        ]), 'slug', 'name');

        $filters = [];

        return $this->render('category', [
            'category' => $category,
            'dataProvider' => $dataProvider,
            'popularModels' => $popularModels,
            'categories' => $categories,
            'pagination' => $dataProvider->pagination,
            'ribbons' => $ribbons,
            'quantityText' => $this->renderQuantityText($dataProvider),
        ]);
    }

    /**
     * Displays Page model.
     * @param string $slug
     * @return mixed
     */
    public function actionView($slug)
    {
        $model = $this->findPage($slug);
        $authorModels = News::find()
            ->where(['created_by' => $model->created_by])
            ->where(['not', ['id' => $model->id]])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)->all();

        $conditionPopular = [
            'status' => News::STATUS_ACTIVE,
        ];
        $productIds = [];
        if (($ribbon = NewsRibbon::findOne(['html_key' => NewsRibbon::RIBBON_POPULAR, 'status' => NewsRibbon::STATUS_ACTIVE])) !== null) {
            empty($productIds_rib = ArrayHelper::getColumn($ribbon->newsToRibbons, 'news_id')) ?
                $productIds_rib = [] :
                $conditionPopular['id'] = array_merge($productIds,$productIds_rib);
        }
        if (!empty($model->categories[0])) {
            if (($category = NewsCategory::findOne($model->categories[0]->id)) !== null) {
                empty($productIds_cat = ArrayHelper::getColumn($category->newsToCategories, 'news_id')) ?
                    $productIds_cat = [] :
                    $conditionPopular['id'] = array_merge($productIds,$productIds_cat);
            }
        }

        $popularModels = News::find()
            ->where($conditionPopular)
            ->limit(4)->all();

        $loginForm = new LoginForm();
        $commentForm = new CommentForm([
            'model' => $model::tableName(),
            'model_id' => $model->id,
        ]);

        if (Yii::$app->user->isGuest) {
            if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
                return $this->refresh('#add-comment');
            }
        } else {
            if ($commentForm->load(Yii::$app->request->post()) && $commentForm->commentSave()) {
                return $this->refresh('#pjax_comments_list');
            } else {
//                dd($commentForm->errors);
            }
        }

        return $this->render('view', [
            'model' => $model,
            'popularModels' => $popularModels,
            'authorModels' => $authorModels,
            'loginForm' => $loginForm,
            'commentForm' => $commentForm,
            'logo' => Yii::$app->request->hostInfo . Settings::getLogoValue(),
            'dataProvider' => $model->getCommentsDataProvider(),
        ]);
    }

    /**
     * Find Pages model.
     * @param string $url
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPage($url)
    {
        if (($model = News::findOne(['slug' => $url, 'status' => News::STATUS_ACTIVE])) !== null) {
            $model->views_counter += 1;
            $model->save();
            return $model;

        }
        else
            throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
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

    /**
     * Find Products model.
     * @param string $slug
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelNews($slug)
    {
        if (($model = News::findOne(['slug' => $slug, 'status' => [News::STATUS_ACTIVE,]])) !== null) {
            $model->views += 1;
            $model->save();

            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('frontend', 'The requested page does not exist.'));
        }
    }


    /**
     * Render Quantity Text.
     * @param ActiveDataProvider $dataProvider
     * @return string
     */
    protected
    function renderQuantityText($dataProvider)
    {
        if ((($count = $dataProvider->getCount()) > 1) && ($pagination = $dataProvider->getPagination()) !== false) {
            $totalCount = $dataProvider->getTotalCount();
            $begin = $pagination->getPage() * $pagination->pageSize + 1;
            $end = $begin + $count - 1;
            if ($begin > $end) $begin = $end;
            $page = $pagination->getPage() + 1;
            $pageCount = $pagination->pageCount;

            return Yii::t('frontend', 'Товары <b>{begin, number}—{end, number}</b> из <b>{totalCount, number}</b>', [
                'begin' => $begin,
                'end' => $end,
                'count' => $count,
                'totalCount' => $totalCount,
                'page' => $page,
                'pageCount' => $pageCount,
            ]);
        }

        return '';
    }

    /**
     * Displays Search Page.
     * @return mixed
     */
    public function actionSearch()
    {
        $searchModel = new NewsSearch();
        $text = '';

        if ($get = Yii::$app->request->get()) {
            if (strlen($text = strip_tags(trim($get['text']))) >= 2)
                $searchModel->name = $text;
//            else
//                $searchModel->group_id = 'brand';
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['name' => SORT_ASC];
        $ribbonPopular = NewsRibbon::findOne(['html_key' => NewsRibbon::RIBBON_POPULAR, 'status' => NewsRibbon::STATUS_ACTIVE]);
        return $this->render('search', [
            'dataProvider' => $dataProvider,
            'text' => $text,
            'pagination' => $dataProvider->pagination,
            'popularModels' => $ribbonPopular->getNews()->limit(4)->all(),
        ]);
    }

    /**
     * Ajax Product List.
     * @param string $q
     */
    public function actionNewsList($q = null)
    {
        $out = [];

        $models = News::find()
            ->where(['status' => [News::STATUS_ACTIVE,]])
            ->andWhere(['LIKE', 'name', $q])
            ->orderBy('name')
            ->limit(1000)
            ->all();

        /** @var $model News */
        foreach ($models as $model) {
            $out[] = [
                'key' => $model->slug,
                'value' => $model->name
            ];
        }

        return Json::encode($out);
    }

    /**
     * @return bool
     */
    public function actionCommentLike()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            if ($model = Comments::findOne((int)$post['item'])) {
                $session = Yii::$app->session;
                if (!$session->isActive) $session->open();
                $sessionId = Yii::$app->user->isGuest ? $session->id : Yii::$app->user->id;
                $sessionArray = empty($model->sessions) ? [] : explode(' ', $model->sessions);
                array_push($sessionArray, $sessionId);
                $model->sessions = implode(' ', $sessionArray);
                (int)$post['value'] ? $model->likes += 1 : $model->unlikes += 1;

                if ($model->save())
                    return true;
            }
        }
        return false;
    }

}
