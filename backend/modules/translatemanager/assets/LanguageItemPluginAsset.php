<?php

namespace backend\modules\translatemanager\assets;

use Yii;
use yii\web\AssetBundle;
use backend\modules\translatemanager\Module;

/**
 * LanguageItem Plugin asset bundle
 */
class LanguageItemPluginAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/modules/translatemanager/web';

    /**
     * @inheritdoc
     */
    public $publishOptions = [
        'forceCopy' => true,
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        /* @var $module Module */
        $module = Yii::$app->getModule('translatemanager');
        $this->sourcePath = $module->getLanguageItemsDirPath();
        if (file_exists(Yii::getAlias($this->sourcePath . Yii::$app->language . '.js'))) {
            $this->js = [
                Yii::$app->language . '.js',
            ];
        } else {
            $this->sourcePath = null;
        }

        parent::init();
    }
}
