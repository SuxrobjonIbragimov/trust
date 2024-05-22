<?php

namespace backend\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * Description of AnimateAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 2.5
 */
class AnimateAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/modules/admin/web';
    /**
     * @inheritdoc
     */
    public $css = [
        'css/animate.css',
    ];

}
