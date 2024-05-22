<?php

namespace backend\modules\translatemanager\assets;

use yii\web\AssetBundle;

/**
 * TranslateManager asset bundle
 */
class TranslateManagerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/modules/translatemanager/web';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/translate-manager.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
