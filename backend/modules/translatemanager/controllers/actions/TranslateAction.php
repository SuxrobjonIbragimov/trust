<?php

namespace backend\modules\translatemanager\controllers\actions;

use Yii;
use yii\base\Action;
use backend\modules\translatemanager\assets\TranslateAsset;
use backend\modules\translatemanager\assets\TranslatePluginAsset;
use backend\modules\translatemanager\models\searches\LanguageSourceSearch;

/**
 * This class facilitates the listing of language elements to be translated.
 */
class TranslateAction extends Action
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        TranslateAsset::register(Yii::$app->controller->view);
        TranslatePluginAsset::register(Yii::$app->controller->view);
        parent::init();
    }

    /**
     * List of language elements.
     *
     * @return string
     */
    public function run()
    {
        $searchModel = new LanguageSourceSearch([
            'searchEmptyCommand' => $this->controller->module->searchEmptyCommand,
        ]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->controller->render('translate', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'language_id' => Yii::$app->request->get('language_id', ''),
        ]);
    }
}
