<?php

namespace backend\modules\handbook\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%handbook_fond_region}}".
 *
 * @property int $id
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_uz
 * @property int $parent_id
 * @property int $ins_id
 * @property int $territory_id
 * @property string $car_number_prefixes
 * @property int $weight
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property string $shortName
 *
 * @property HandbookFondRegion $parent
 * @property HandbookFondRegion $handbookFondRegion
 * @property HandbookFondRegion[] $handbookFondRegions
 */
class HandbookFondRegion extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = -1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%handbook_fond_region}}';
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
            [['parent_id', 'ins_id', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name_ru', 'name_en', 'name_uz'], 'string', 'max' => 255],
            [['territory_id', 'car_number_prefixes'], 'safe',],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => HandbookFondRegion::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('handbook', 'ID'),
            'name_ru' => Yii::t('handbook', 'Name Ru'),
            'name_en' => Yii::t('handbook', 'Name En'),
            'name_uz' => Yii::t('handbook', 'Name Uz'),
            'parent_id' => Yii::t('handbook', 'Parent ID'),
            'ins_id' => Yii::t('handbook', 'Ins ID'),
            'weight' => Yii::t('handbook', 'Weight'),
            'status' => Yii::t('handbook', 'Status'),
            'created_at' => Yii::t('handbook', 'Created At'),
            'updated_at' => Yii::t('handbook', 'Updated At'),
        ];
    }

    public function getShortName()
    {
        $name_field = 'name_'._lang();
        return !empty($this->$name_field) ? $this->$name_field : $this->name_ru;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(HandbookFondRegion::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHandbookFondRegion()
    {
        return $this->hasOne(HandbookFondRegion::className(), ['parent_id' => 'id'])->orderBy(['id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHandbookFondRegions()
    {
        return $this->hasMany(HandbookFondRegion::className(), ['parent_id' => 'id']);
    }

    /**
     * @param $id
     * @return HandbookFondRegion|null
     */
    public static function _getByTerritory($id)
    {
        return self::findOne(['territory_id' => $id]);
    }

    /**
     * @param $id
     * @return HandbookFondRegion|null
     */
    public static function _getByInsId($id)
    {
        return self::findOne(['ins_id' => $id]);
    }

    /**
     * @return HandbookFondRegion[]|array|\yii\db\ActiveRecord[]
     */
    public static function _getAllModelsList()
    {
        $null = new Expression('NULL');
        return self::find()->where(['status' => self::STATUS_ACTIVE])->andWhere(['is', 'parent_id', $null])->all();
    }

    /**
     * @return array
     */
    public static function _getItemsList()
    {
        return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE])->asArray()->all(), 'id' ,'name');
    }

    /**
     * @return array
     */
    public static function _getItemsListByInsParam($id=null, $array=true)
    {
        $name_field = 'name_'._lang();
        if (!empty($id) && is_numeric($id) && empty($array)) {
            $parent_model = HandbookFondRegion::findOne(['ins_id' => $id]);
            $paren_id = !empty($parent_model->id) ? $parent_model->id : null;
            $data = self::find()->where(['status' => self::STATUS_ACTIVE])->andWhere(['parent_id' => $paren_id])->select(['ins_id as id', "{$name_field} as name"])->asArray()->all();
        }
        elseif (!empty($id) && is_numeric($id)) {
            $parent_model = HandbookFondRegion::findOne(['ins_id' => $id]);
            $paren_id = !empty($parent_model->id) ? $parent_model->id : null;
            $data =  ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE])->andWhere(['parent_id' => $paren_id])->select(['ins_id', "{$name_field} as name"])->asArray()->all(), 'ins_id' ,'name');
        } elseif (!empty($id) && $id=='all') {
            $null = new Expression('NULL');
            $data =  ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE])->andWhere(['is not', 'parent_id', $null])->select(['ins_id', "{$name_field} as name"])->asArray()->all(), 'ins_id' ,'name');
        } else {
            $null = new Expression('NULL');
            $data =  ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE])->andWhere(['is', 'parent_id', $null])->select(['ins_id', "{$name_field} as name"])->asArray()->all(), 'ins_id' ,'name');
        }
        return $data;
    }

}
