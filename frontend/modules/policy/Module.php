<?php

namespace frontend\modules\policy;

/**
 * policy module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\policy\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->setAliases([
            '@policy-assets' => __DIR__ . '/assets'
        ]);
        // custom initialization code goes here
    }
}
