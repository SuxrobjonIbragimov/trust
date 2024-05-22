<?php

namespace backend\modules\translatemanager\controllers\actions;

use yii\base\Action;

/**
 * Deletes an existing Language model.
 */
class DeleteAction extends Action
{
    /**
     * Deletes an existing Language model.
     * If deletion is successful, the browser will be redirected to the 'list' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function run($id)
    {
        $this->controller->findModel($id)->delete();

        return $this->controller->redirect(['list']);
    }
}
