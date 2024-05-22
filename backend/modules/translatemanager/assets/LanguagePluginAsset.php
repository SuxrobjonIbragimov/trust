<?php

namespace backend\modules\translatemanager\assets;

use yii\web\AssetBundle;

/**
 * Language Plugin asset bundle
 */
class LanguagePluginAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/modules/translatemanager/web';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/helpers.js',
        'js/language.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
