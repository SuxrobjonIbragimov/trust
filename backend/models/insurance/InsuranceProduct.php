<?php

namespace backend\models\insurance;

use backend\behaviors\MetaTitleBehavior;
use backend\behaviors\SlugBehavior;
use backend\behaviors\TranslateDatabaseBehavior;
use backend\modules\handbook\models\HandbookLegalType;
use himiklab\sitemap\behaviors\SitemapBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%insurance_product}}".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $slug
 * @property string|null $summary
 * @property string|null $description
 * @property int|null $parent_id
 * @property string|null $image
 * @property string|null $icon
 * @property int|null $is_main
 * @property string|null $meta_title
 * @property string|null $meta_keywords
 * @property string|null $meta_description
 * @property int|null $is_popular
 * @property int|null $views
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property int|null $legal_type_ids
 * @property int|null $legal_type_id
 *
 * @property InsuranceProduct $parent
 * @property InsuranceProduct[] $insuranceProducts
 * @property InsuranceProductItem[] $insuranceProductItems
 * @property InsuranceProductToLegalType[] $insuranceProductToLegalTypes
 * @property HandbookLegalType[] $legalTypes
 */
class InsuranceProduct extends \yii\db\ActiveRecord
{
    const IS_MAIN = 1;
    const IS_POPULAR = 1;

    public $legal_type_ids;
    public $legal_type_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%insurance_product}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SlugBehavior::className(),
                'attribute' => 'title'
            ],
            [
                'class' => MetaTitleBehavior::className(),
                'attribute' => 'title'
            ],
            [
                'class' => TranslateDatabaseBehavior::class,
                'translateAttributes' => ['title', 'description', 'summary','meta_title', 'meta_keywords', 'meta_description'],
                'tableName' => static::tableName(),
            ],
            'sitemap' => [
                'class' => SitemapBehavior::class,
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['slug', 'status', 'updated_at']);
                    $model->andWhere(['status' => self::STATUS_ACTIVE]);
                },
                'dataClosure' => function ($model) {
                    /** @var self $model */
                    return [
                        'loc' => Url::to(['/product/view', 'slug' => $model->slug], true),
                        'lastmod' => $model->updated_at,
                        'changefreq' => SitemapBehavior::CHANGEFREQ_WEEKLY,
                        'priority' => 0.9
                    ];
                }
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'summary','description', ], 'required'],
            [['subtitle', 'summary', 'description', 'slug', 'meta_description'], 'string'],
            [['parent_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['parent_id', 'views', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['is_main', ], 'safe'],
            [['legal_type_ids', ], 'required'],
            [['legal_type_ids'], 'each', 'rule' => ['integer']],
            [['legal_type_id', ], 'integer'],
            [['weight', ], 'default', 'value' => 0],
            [['status', ], 'default', 'value' => self::STATUS_INACTIVE],
            [['title', 'image', 'icon', 'meta_title', 'meta_keywords'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceProduct::class, 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'title' => Yii::t('model', 'Title'),
            'subtitle' => Yii::t('model', 'Subtitle'),
            'slug' => Yii::t('model', 'Slug'),
            'summary' => Yii::t('model', 'Summary'),
            'description' => Yii::t('model', 'Description'),
            'parent_id' => Yii::t('model', 'Parent ID'),
            'image' => Yii::t('model', 'Image'),
            'icon' => Yii::t('model', 'Icon'),
            'is_main' => Yii::t('model', 'Is main'),
            'is_popular' => Yii::t('model', 'Is popular'),
            'legal_type_ids' => Yii::t('model', 'Legal Types'),
            'legal_type_id' => Yii::t('model', 'Legal Type'),
            'meta_title' => Yii::t('model', 'Meta Title'),
            'meta_keywords' => Yii::t('model', 'Meta Keywords'),
            'meta_description' => Yii::t('model', 'Meta Description'),
            'views' => Yii::t('model', 'Views'),
            'weight' => Yii::t('model', 'Weight'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(InsuranceProduct::class, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[InsuranceProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsuranceProducts()
    {
        return $this->hasMany(InsuranceProduct::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[InsuranceProductItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsuranceProductItems($type=null)
    {
        $null = new Expression('NULL');
        if ($type == InsuranceProductItem::TYPE_WHAT_INCLUDED) {
            return $this->hasMany(InsuranceProductItem::className(), ['insurance_product_id' => 'id'])
                ->onCondition(['type' => InsuranceProductItem::TYPE_WHAT_INCLUDED])
                ->andOnCondition(['is', 'parent_id', $null])->orderBy(['weight' => SORT_ASC]);
        } elseif ($type == InsuranceProductItem::TYPE_WHAT_TO_DO) {
            return $this->hasMany(InsuranceProductItem::className(), ['insurance_product_id' => 'id'])
                ->onCondition(['type' => InsuranceProductItem::TYPE_WHAT_TO_DO])
                ->andOnCondition(['is', 'parent_id', $null])->orderBy(['weight' => SORT_ASC]);
        }
        return $this->hasMany(InsuranceProductItem::className(), ['insurance_product_id' => 'id'])
            ->orderBy(['weight' => SORT_ASC]);
    }

    /**
     * Gets query for [[InsuranceProductToLegalTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsuranceProductToLegalTypes()
    {
        return $this->hasMany(InsuranceProductToLegalType::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[LegalTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLegalTypes()
    {
        return $this->hasMany(HandbookLegalType::className(), ['id' => 'legal_type_id'])->viaTable('{{%insurance_product_to_legal_type}}', ['product_id' => 'id']);
    }

    /**
     * LegalTypes model to array
     * @return array
     */
    public static function getLegalTypeList()
    {
        return ArrayHelper::map(HandbookLegalType::_getItemsList(), 'id', 'name_ru');
    }

    /**
     * Status
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = -1;

    /**
     * @return bool
     */
    public function setStatusDeleted()
    {
        $this->status = self::STATUS_DELETED;
        return $this->save(false);
    }

    /**
     * Status Array
     * @param integer|null $status
     * @return array|string
     */
    public static function getStatusArray($status = null)
    {
        $array = [
            self::STATUS_INACTIVE => Yii::t('model', 'Inactive'),
            self::STATUS_ACTIVE => Yii::t('model', 'Active'),
        ];

        return $status === null ? $array : $array[$status];
    }

    /**
     * Status Name
     * @return string
     */
    public function getStatusName()
    {
        $array = [
            self::STATUS_ACTIVE => '<span class="text-bold text-green">' . self::getStatusArray(self::STATUS_ACTIVE) . '</span>',
            self::STATUS_INACTIVE => '<span class="text-bold text-red">' . self::getStatusArray(self::STATUS_INACTIVE) . '</span>',
        ];

        return isset($array[$this->status]) ? $array[$this->status] : $this->status;
    }

    const HOME_LIMIT_MAIN = 4;
    const HOME_LIMIT_POPULAR = 3;

    /**
     * @return array|InsuranceProduct[]|\yii\db\ActiveRecord[]
     */
    public static function _getHomeMainItems()
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere(['is_main' => self::IS_MAIN])
            ->orderBy(['weight' => SORT_ASC, 'id' => SORT_DESC])
            ->limit(self::HOME_LIMIT_MAIN)->all();
    }

    /**
     * @return array|InsuranceProduct[]|\yii\db\ActiveRecord[]
     */
    public static function _getHomePopularItems()
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere(['is_popular' => self::IS_MAIN])
            ->orderBy(['weight' => SORT_ASC, 'id' => SORT_DESC])
            ->limit(self::HOME_LIMIT_POPULAR)->all();
    }


    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->legal_type_ids = ArrayHelper::getColumn($this->insuranceProductToLegalTypes, 'legal_type_id');
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (!empty($legal_type_ids = $this->legal_type_ids)) {
            if (!$insert) InsuranceProductToLegalType::deleteAll(['product_id' => $this->id]);
            foreach ($legal_type_ids as $legal_type_id) {
                $relation = new InsuranceProductToLegalType();
                $relation->product_id = $this->id;
                $relation->legal_type_id = $legal_type_id;
                $relation->save();
            }
        }

    }

}
