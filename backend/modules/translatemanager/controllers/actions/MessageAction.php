<?php

namespace backend\modules\translatemanager\controllers\actions;

use Yii;
use yii\base\Action;
use backend\modules\translatemanager\models\LanguageSource;
use backend\modules\translatemanager\models\LanguageTranslate;

/**
 * Class for returning messages in the given language
 */
class MessageAction extends Action
{
    /**
     * Returning messages in the given language
     *
     * @return string
     */
    public function run()
    {
        $languageTranslate = LanguageTranslate::findOne([
            'id' => Yii::$app->request->get('id', 0),
            'language' => Yii::$app->request->get('language_id', ''),
        ]);

        if ($languageTranslate) {
            $translation = $languageTranslate->translation;
        } else {
            $languageSource = LanguageSource::findOne([
                'id' => Yii::$app->request->get('id', 0),
            ]);

            $translation = $languageSource ? $languageSource->message : '';
        }

        return $translation;
    }
}
