<?php

namespace backend\modules\translatemanager\assets;

use Yii;
use backend\modules\translatemanager\Module;
use backend\modules\translatemanager\models\Language;

/**
 * AllLanguageItem Plugin asset bundle
 * will include all active languages
 */
class AllLanguageItemPluginAsset extends LanguageItemPluginAsset
{
    public function init()
    {
        parent::init();
        $this->js = [];
        /* @var $module Module */
        $module = Yii::$app->getModule('translatemanager');
        $this->sourcePath = $module->getLanguageItemsDirPath();

        $langs = Language::findAll(['status' => Language::STATUS_ACTIVE]);

        foreach ($langs as $key => $lang) {
            if (file_exists(Yii::getAlias($this->sourcePath . $lang->language_id . '.js'))) {
                $this->js[] = $lang->language_id . '.js';
            }
        }
    }
}
