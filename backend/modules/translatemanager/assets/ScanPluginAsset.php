<?php

namespace backend\modules\translatemanager\assets;

use yii\web\AssetBundle;

/**
 * Scan Plugin asset bundle
 */
class ScanPluginAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/modules/translatemanager/web';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/scan.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'backend\modules\translatemanager\assets\TranslationPluginAsset',
    ];
}
