<?php
/**
 * Created by PhpStorm.
 * User: Shohrux Haqberdiyev
 * Date: 04-Apr-20
 * Time: 16:43
 */

namespace common\components\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;

class TranslateBehavior extends Behavior
{
    public $translatableFields = [
        'name'
    ];

    public $translateModel;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'saveTranslate',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    public function saveTranslate($event)
    {
        $translatableFields = $this->translatableFields;
        foreach ($translatableFields as $field) {
            if (($model = $this->translateModel::findOne([
                    'param' => $field,
                    'model' => $this->owner->formName(),
                    'revision_id' => $this->owner->id,
                    'lang' => _lang()
                ])) === null) {
                $model = new $this->translateModel;
                $model->param = $field;
                $model->model = $this->owner->formName();
                $model->revision_id = $this->owner->id;
                $model->lang = _lang();
            }
            $model->content = $this->owner->$field;
            $model->save();
        }
    }

    public function afterInsert($event)
    {
        $translatableFields = $this->translatableFields;
        foreach ($translatableFields as $field) {
            if (($model = $this->translateModel::findOne([
                    'param' => $field,
                    'model' => $this->owner->formName(),
                    'revision_id' => $this->owner->id,
                    'lang' => _lang()
                ])) === null) {
                $model = new $this->translateModel;
                $model->param = $field;
                $model->model = $this->owner->formName();
                $model->revision_id = $this->owner->id;
                $model->lang = _lang();
            }
            $model->content = $this->owner->$field;
            $model->save();
        }
    }

    public function afterFind()
    {
        $translatableFields = $this->translatableFields;
        foreach ($translatableFields as $field) {
            if ($model = $this->getTranslate($field)) {
                $this->owner->$field = $model->content;
            }
        }

    }


    public function getTranslate($field='name')
    {
        $cache_db_translate = Yii::$app->params['cache']['db.translate'] ?? 0;
        if ($cache_db_translate) {

            $dependency = new \yii\caching\ExpressionDependency(['expression'=>_lang()]);
            return $translate = $this->translateModel::getDb()->cache(function ($db) use ($field) {
                return $this->translateModel::findOne([
                    'param' => $field,
                    'model' => $this->owner->formName(),
                    'revision_id' => $this->owner->id,
                    'lang' => _lang()
                ]) ? :
                    $this->translateModel::findOne([
                        'param' => $field,
                        'model' => $this->owner->formName(),
                        'revision_id' => $this->owner->id,
                    ]);
            }, $cache_db_translate, $dependency);

        }
        return $this->translateModel::findOne([
            'param' => $field,
            'model' => $this->owner->formName(),
            'revision_id' => $this->owner->id,
            'lang' => _lang()
        ]) ? :
        $this->translateModel::findOne([
            'param' => $field,
            'model' => $this->owner->formName(),
            'revision_id' => $this->owner->id,
        ]);
    }


}