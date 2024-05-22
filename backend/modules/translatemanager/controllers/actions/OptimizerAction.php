<?php

namespace backend\modules\translatemanager\controllers\actions;

use yii\base\Action;
use backend\modules\translatemanager\services\Optimizer;

/**
 * Class for optimizing language database.
 */
class OptimizerAction extends Action
{
    /**
     * Removing unused language elements.
     *
     * @return string
     */
    public function run()
    {
        $optimizer = new Optimizer();
        $optimizer->run();

        $removedLanguageElements = $optimizer->getRemovedLanguageElements();

        return $this->controller->render('optimizer', [
            'newDataProvider' => $this->controller->createLanguageSourceDataProvider($removedLanguageElements),
        ]);
    }
}
