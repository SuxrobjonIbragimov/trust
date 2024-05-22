<?php

namespace backend\modules\translatemanager\assets;

/**
 * Translation Plugin asset bundle
 * will include all active languages
 */
class FullTranslationPluginAsset extends TranslationPluginAsset
{
    /**
     * @inheritdoc
     */
    public $depends = [
        'backend\modules\translatemanager\assets\AllLanguageItemPluginAsset',
    ];
}
