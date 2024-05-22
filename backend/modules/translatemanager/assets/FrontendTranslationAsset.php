<?php

namespace backend\modules\translatemanager\assets;

use yii\web\AssetBundle;

/**
 * FrontendTranslation asset bundle
 */
class FrontendTranslationAsset extends AssetBundle
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
        'css/frontend-translation.css',
    ];
}
