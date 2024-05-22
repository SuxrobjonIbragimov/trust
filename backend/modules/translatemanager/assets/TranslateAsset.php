<?php

namespace backend\modules\translatemanager\assets;

use yii\web\AssetBundle;

/**
 * Translation asset bundle
 */
class TranslateAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/modules/translatemanager/web';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/helpers.css',
        'css/translate.css',
    ];
}
