<?php

namespace backend\modules\translatemanager\assets;

use yii\web\AssetBundle;

/**
 * Translation Plugin asset bundle
 */
class TranslationPluginAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/modules/translatemanager/web';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/md5.js',
        'js/lajax.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'backend\modules\translatemanager\assets\LanguageItemPluginAsset',
    ];
}
