<?php

namespace backend\modules\translatemanager\controllers\actions;

use Yii;
use yii\base\Action;
use backend\modules\translatemanager\assets\LanguageAsset;
use backend\modules\translatemanager\assets\LanguagePluginAsset;
use backend\modules\translatemanager\models\searches\LanguageSearch;

/**
 * Class that creates a list of languages.
 */
class ListAction extends Action
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        LanguageAsset::register($this->controller->view);
        LanguagePluginAsset::register($this->controller->view);
        parent::init();
    }

    /**
     * List of languages
     *
     * @return string
     */
    public function run()
    {
        $searchModel = new LanguageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->sort = ['defaultOrder' => ['status' => SORT_DESC]];

        return $this->controller->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
