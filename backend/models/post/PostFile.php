<?php

namespace backend\models\post;

use Yii;

/**
 * This is the model class for table "{{%post_file}}".
 *
 * @property int $post_id
 * @property string $generate_name
 * @property string $name
 * @property string $path
 * @property int $position
 *
 * @property Posts $post
 */
class PostFile extends \yii\db\ActiveRecord
{
    const ITEMS_LIMIT = 50;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'generate_name', 'name', 'path'], 'required'],
            [['post_id', 'position'], 'default', 'value' => null],
            [['post_id', 'position'], 'integer'],
            [['generate_name'], 'string', 'max' => 64],
            [['name', 'path'], 'string', 'max' => 255],
            [['generate_name'], 'unique'],
            [['post_id', 'generate_name'], 'unique', 'targetAttribute' => ['post_id', 'generate_name']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'post_id' => Yii::t('model', 'Post ID'),
            'generate_name' => Yii::t('model', 'Generate Name'),
            'name' => Yii::t('model', 'Name'),
            'path' => Yii::t('model', 'Path'),
            'position' => Yii::t('model', 'Position'),
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posts::className(), ['id' => 'post_id']);
    }
}
