<?php

namespace backend\modules\translatemanager\controllers\actions;

use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use backend\modules\translatemanager\models\Language;
use backend\modules\translatemanager\models\ImportForm;
use backend\modules\translatemanager\services\Generator;
use backend\modules\translatemanager\assets\LanguageAsset;
use backend\modules\translatemanager\assets\LanguagePluginAsset;

/**
 * Class for exporting translations.
 */
class ImportAction extends Action
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        LanguageAsset::register($this->controller->view);
        LanguagePluginAsset::register($this->controller->view);
        parent::init();
    }

    /**
     * Show import form and import the uploaded file if posted
     *
     * @return string
     *
     * @throws \Exception
     */
    public function run()
    {
        $model = new ImportForm();

        if (Yii::$app->request->isPost) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');

            if ($model->validate()) {
                try {
                    $result = $model->import();

                    $message = Yii::t('language', 'Successfully imported {fileName}', ['fileName' => $model->importFile->name]);
                    $message .= "<br/>\n";
                    foreach ($result as $type => $typeResult) {
                        $message .= "<br/>\n" . Yii::t('language', '{type}: {new} new, {updated} updated', [
                            'type' => $type,
                            'new' => $typeResult['new'],
                            'updated' => $typeResult['updated'],
                        ]);
                    }

                    $languageIds = Language::find()
                        ->select('language_id')
                        ->where(['status' => Language::STATUS_ACTIVE])
                        ->column();

                    foreach ($languageIds as $languageId) {
                        $generator = new Generator($this->controller->module, $languageId);
                        $generator->run();
                    }

                    Yii::$app->getSession()->setFlash('success', $message);
                } catch (\Exception $e) {
                    if (YII_DEBUG) {
                        throw $e;
                    } else {
                        Yii::$app->getSession()->setFlash('error', str_replace("\n", "<br/>\n", $e->getMessage()));
                    }
                }
            }
        }

        return $this->controller->render('import', [
            'model' => $model,
        ]);
    }
}
