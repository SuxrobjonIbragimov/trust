<?php

namespace backend\modules\translatemanager\assets;

use yii\web\AssetBundle;

/**
 * FrontendTranslation Plugin asset bundle
 */
class FrontendTranslationPluginAsset extends AssetBundle
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
        'js/frontend-translation.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'backend\modules\translatemanager\assets\TranslationPluginAsset',
    ];
}
