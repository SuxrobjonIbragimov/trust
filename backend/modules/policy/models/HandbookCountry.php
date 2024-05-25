<?php

namespace backend\modules\policy\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%handbook_country}}".
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property int|null $parent_id
 * @property int|null $ins_id
 * @property string|null $code
 * @property string|null $flag
 * @property bool|null $is_shengen
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property string|null $localeName
 *
 * @property HandbookCountry $parent
 * @property HandbookCountry[] $handbookCountries
 * @property PolicyTravelProgramToCountry[] $policyTravelProgramToCountries
 * @property PolicyTravelToCountry[] $policyTravelToCountries
 */
class HandbookCountry extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const IS_SCHENGEN = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%handbook_country}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'ins_id', 'weight', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['parent_id', 'ins_id', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['is_shengen'], 'boolean'],
            [['name_ru', 'name_uz', 'name_en', 'code', 'flag'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => HandbookCountry::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }
    public function getLocaleName()
    {
        $lang = _lang();
        $field = 'name_'.$lang;
        return !empty($this->$field) ? $this->$field : 'name_ru';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('handbook', 'ID'),
            'name_ru' => Yii::t('handbook', 'Name Ru'),
            'name_uz' => Yii::t('handbook', 'Name Uz'),
            'name_en' => Yii::t('handbook', 'Name En'),
            'parent_id' => Yii::t('handbook', 'Parent ID'),
            'ins_id' => Yii::t('handbook', 'Ins ID'),
            'code' => Yii::t('handbook', 'Code'),
            'flag' => Yii::t('handbook', 'Flag'),
            'is_shengen' => Yii::t('handbook', 'Is Shengen'),
            'weight' => Yii::t('handbook', 'Weight'),
            'status' => Yii::t('handbook', 'Status'),
            'created_at' => Yii::t('handbook', 'Created At'),
            'updated_at' => Yii::t('handbook', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(HandbookCountry::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[HandbookCountries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHandbookCountries()
    {
        return $this->hasMany(HandbookCountry::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[PolicyTravelProgramToCountries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelProgramToCountries()
    {
        return $this->hasMany(PolicyTravelProgramToCountry::className(), ['country_id' => 'id']);
    }

    /**
     * Gets query for [[PolicyTravelToCountries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelToCountries()
    {
        return $this->hasMany(PolicyTravelToCountry::className(), ['country_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function _getItemsList()
    {
        $name_field = 'name_'._lang();
        return ArrayHelper::map(
            self::find()
                ->where(['status' =>self::STATUS_ACTIVE])
//                ->andWhere(['IS NOT', 'parent_id', null])
                ->orderBy([$name_field => SORT_ASC])
                ->all(), 'id' ,"{$name_field}"
        );
    }
}
