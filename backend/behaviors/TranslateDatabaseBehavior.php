<?php

namespace backend\behaviors;

use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;
use backend\models\TranslateDatabase;

/**
 * Translate Database translate behavior.
 *
 * Installation:
 *
 * ~~~
 * [
 *      'class' => backend\modules\translatemanager\behaviors\TranslateBehavior::className(),
 *      'translateAttributes' => ['names of multi language fields'],
 *      'tableName' => static::tableName(),
 * ],
 * ~~~
 *
 */
class TranslateDatabaseBehavior extends AttributeBehavior
{
    /**
     * @var array|string
     */
    public $translateAttributes;

    /**
     * @var string tableName.
     */
    public $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = str_replace(['{', '%', '}'], '', $this->tableName);
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_FIND => 'translateAttributes',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'deleteAttributes',
            BaseActiveRecord::EVENT_AFTER_INSERT=> 'saveAttributes',
            BaseActiveRecord::EVENT_AFTER_UPDATE=> 'saveAttributes',
        ];
    }

    /**
     * Translate
     * @param \yii\base\Event $event
     */
    public function translateAttributes($event)
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        foreach ($this->translateAttributes as $attribute) {
            $owner->{$attribute} = TranslateDatabase::findTranslate($this->tableName . '_' . $owner['id'] . '_' . $attribute, $owner->attributes[$attribute]);
        }
    }

    /**
     * Save
     * @param \yii\base\Event $event
     */
    public function saveAttributes($event)
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        foreach ($this->translateAttributes as $attribute) {
            TranslateDatabase::saveTranslate($this->tableName . '_' . $owner['id'] . '_' . $attribute, $owner->attributes[$attribute]);
        }
    }

    /**
     * Delete
     * @param \yii\base\Event $event
     */
    public function deleteAttributes($event)
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        foreach ($this->translateAttributes as $attribute) {
            TranslateDatabase::deleteAll(['key' => $this->tableName . '_' . $owner['id'] . '_' . $attribute]);
        }
    }
}
