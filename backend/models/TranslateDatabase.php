<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "translate_database".
 *
 * @property string $key
 * @property string $language
 * @property string $translation
 */
class TranslateDatabase extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%translate_database}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'language'], 'required'],
            [['translation'], 'string'],
            [['key'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => 'Key',
            'language' => 'Language',
            'translation' => 'Translation',
        ];
    }

    /**
     * Save TranslateDatabase
     * @param string $key
     * @param string $message
     */
    public static function saveTranslate($key, $message)
    {
        $model = static::findOne(['key' => $key, 'language' => Yii::$app->language]) ?:
            $model = new static(['key' => $key, 'language' => Yii::$app->language]);

        $model->translation = $message;
        empty($message) ? $model->delete() : $model->save();
    }

    /**
     * Find TranslateDatabase
     * @param string $key
     * @param string $message
     * @return string
     */
    public static function findTranslate($key, $message)
    {
        return ($model = static::findOne(['key' => $key, 'language' => Yii::$app->language])) ? $model->translation : $message;
    }
}
