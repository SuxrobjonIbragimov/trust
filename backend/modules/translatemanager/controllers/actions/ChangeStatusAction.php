<?php

namespace backend\modules\translatemanager\controllers\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use backend\modules\translatemanager\models\Language;

/**
 * Class that modifies the state of a language.
 */
class ChangeStatusAction extends Action
{
    /**
     * Modifying the state of language.
     *
     * @return array
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $language = Language::findOne(Yii::$app->request->post('language_id', ''));
        if ($language !== null) {
            $language->status = Yii::$app->request->post('status', Language::STATUS_BETA);
            if ($language->validate()) {
                $language->save();
            }
        }

        return $language->getErrors();
    }
}
