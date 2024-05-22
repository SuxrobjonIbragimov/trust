<?php

namespace backend\modules\translatemanager\assets;

use yii\web\AssetBundle;

/**
 * Translation Plugin asset bundle
 */
class TranslatePluginAsset extends AssetBundle
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
        'js/translate.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
