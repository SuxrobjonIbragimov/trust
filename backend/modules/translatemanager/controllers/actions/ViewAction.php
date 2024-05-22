<?php

namespace backend\modules\translatemanager\controllers\actions;

use yii\base\Action;

/**
 * Displays a single Language model.
 */
class ViewAction extends Action
{
    /**
     * Displays a single Language model.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function run($id)
    {
        return $this->controller->render('view', [
            'model' => $this->controller->findModel($id),
        ]);
    }
}
