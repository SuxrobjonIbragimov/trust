<?php

namespace backend\modules\handbook\models;

use common\models\User;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%handbook_okonh}}".
 *
 * @property int $id
 * @property int|null $oked_id
 * @property string|null $ins_id
 * @property string|null $name_uz
 * @property string|null $name_ru
 * @property string|null $name_en
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property HandbookOked $oked
 * @property User $createdBy
 * @property User $updatedBy
 */
class HandbookOkonh extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = -1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%handbook_okonh}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['oked_id', 'weight', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['oked_id', 'weight', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ins_id', 'name_uz', 'name_ru', 'name_en'], 'string', 'max' => 255],
            [['oked_id'], 'exist', 'skipOnError' => true, 'targetClass' => HandbookOked::className(), 'targetAttribute' => ['oked_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('policy', 'ID'),
            'oked_id' => Yii::t('policy', 'Oked ID'),
            'ins_id' => Yii::t('policy', 'Ins ID'),
            'name_uz' => Yii::t('policy', 'Name Uz'),
            'name_ru' => Yii::t('policy', 'Name Ru'),
            'name_en' => Yii::t('policy', 'Name En'),
            'weight' => Yii::t('policy', 'Weight'),
            'status' => Yii::t('policy', 'Status'),
            'created_by' => Yii::t('policy', 'Created By'),
            'updated_by' => Yii::t('policy', 'Updated By'),
            'created_at' => Yii::t('policy', 'Created At'),
            'updated_at' => Yii::t('policy', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Oked]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOked()
    {
        return $this->hasOne(HandbookOked::className(), ['id' => 'oked_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }


    /**
     * @param $id
     * @return HandbookOkonh|null
     */
    public static function _getByInsId($id)
    {
        return self::findOne(['ins_id' => $id]);
    }

    /**
     * @return HandbookOked[]|array|\yii\db\ActiveRecord[]
     */
    public static function _getAllModelsList()
    {
        $null = new Expression('NULL');
        return self::find()->where(['status' => self::STATUS_ACTIVE])->all();
    }

    /**
     * @return array
     */
    public static function _getItemsList()
    {
        $name_field = 'name_'._lang();
        return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE])->asArray()->all(), 'id', $name_field);
    }

    /**
     * @return array
     */
    public static function _getItemsListByInsParam($id=null, $array=true)
    {
        $name_field = 'name_'._lang();
        if (!empty($id) && is_numeric($id) && empty($array)) {
            $data = self::find()->where(['status' => self::STATUS_ACTIVE])->andWhere(['oked_id' => $id])->select(['ins_id as id', "{$name_field} as name"])->asArray()->all();
        }
        elseif (!empty($id) && is_numeric($id)) {
            $data =  ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE])->andWhere(['oked_id' => $id])->select(['ins_id', "{$name_field} as name"])->asArray()->all(), 'ins_id' ,'name');
        } elseif (!empty($id) && $id=='all') {
            $data =  ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE])->select(['ins_id', "{$name_field} as name"])->asArray()->all(), 'ins_id' ,'name');
        } else {
            $data =  ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE])->select(['ins_id', "{$name_field} as name"])->asArray()->all(), 'ins_id' ,'name');
        }
        return $data;
    }


}
