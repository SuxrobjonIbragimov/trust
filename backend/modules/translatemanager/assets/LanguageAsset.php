<?php

namespace backend\modules\translatemanager\assets;

use yii\web\AssetBundle;

/**
 * Language asset bundle
 */
class LanguageAsset extends AssetBundle
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
        'css/language.css',
    ];
}
