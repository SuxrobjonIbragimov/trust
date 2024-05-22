<?php

namespace backend\models\menu;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use backend\modules\translatemanager\behaviors\TranslateBehavior;

/**
 * This is the model class for table "menu_items".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property integer $parent_id
 * @property string $label
 * @property string $url
 * @property string $class
 * @property string $icon
 * @property string $description
 * @property integer $weight
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Menus $menu
 * @property MenuItems $parent
 * @property MenuItems[] $menuItems
 * @property MenuItems[] $menuItemsActive
 * @property MenuItems[] $menuItemsActiveCache
 */
class MenuItems extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_items}}';
    }

    /**
     * Status
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => TranslateBehavior::className(),
                'translateAttributes' => ['label', 'description'],
                'category' => static::tableName(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'label', 'url'], 'required'],
            [['menu_id', 'parent_id', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['label', 'url', 'class', 'icon'], 'string', 'max' => 255],
            [['weight'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS_INACTIVE],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menus::className(), 'targetAttribute' => ['menu_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => MenuItems::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'menu_id' => Yii::t('model', 'Menu'),
            'parent_id' => Yii::t('model', 'Parent'),
            'label' => Yii::t('model', 'Label'),
            'url' => Yii::t('model', 'Url'),
            'class' => Yii::t('model', 'Class'),
            'icon' => Yii::t('model', 'Icon'),
            'description' => Yii::t('model', 'Description'),
            'weight' => Yii::t('model', 'Weight'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menus::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MenuItems::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItems::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemsActive()
    {
        return $this->hasMany(MenuItems::className(), ['parent_id' => 'id'])
            ->andOnCondition(['status' => static::STATUS_ACTIVE])
            ->orderBy(['weight' => SORT_ASC]);
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function getMenuItemsActiveCache()
    {
        $id = $this->id;
        $cache_db = !empty(Yii::$app->params['cache']['db.menuItems']['frontend']) ? Yii::$app->params['cache']['db.menuItems']['frontend'] : 0;

        if ($cache_db) {
            $cache = Yii::$app->cache;
            $key = 'menuItems_tmp_'.$id.'_'._lang();
            $data = $cache->get($key);

            if ($data === false) {
                $data = $key;
                _cache_file(true);
                $cache->set($key, $data, $cache_db);
            }

            $dependency = new \yii\caching\FileDependency(['fileName' => 'lang.txt']);
            $result = self::getDb()->cache(function ($db) use ($id) {
                return self::find()->where(['parent_id' => $id,'status' => static::STATUS_ACTIVE])
                    ->orderBy(['weight' => SORT_ASC])
                    ->all();
            }, $cache_db, $dependency);

        } else {
            $result = self::find()->where(['parent_id' => $id,'status' => static::STATUS_ACTIVE])
                ->orderBy(['weight' => SORT_ASC])
                ->all();
        }
        return $result;

    }

    /**
     * Status Text
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

        return isset($array[$this->status]) ? $array[$this->status] : '';
    }

    /**
     * self model
     * @param array $condition
     * @return array
     */
    public static function getActiveMenuItemsByCondition($condition)
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->andWhere($condition)
            ->orderBy(['weight' => SORT_ASC])
            ->all();
    }

    /**
     * self model to array
     * @param integer $menu_id
     * @param integer $id
     * @return array
     */
    public static function getMenuItemsArray($menu_id, $id = 0)
    {
        $items = [];
        $index = '';

        if ($id == 0) {
            $models = self::findAll(['menu_id' => $menu_id, 'parent_id' => NULL]);
        } else {
            $models = self::find()->where(['menu_id' => $menu_id, 'parent_id' => NULL])->andWhere(['!=', 'id', $id])->all();
        }

        if (!empty($models)) {
            foreach ($models as $model) {
                $items[$model->id] = $index . $model->label;

                if ($id == 0) {
                    $childs = self::findAll(['parent_id' => $model->id]);
                } else {
                    $childs = self::find()->where(['parent_id' => $model->id])->andWhere(['!=', 'id', $id])->all();
                }

                if (!empty($childs))
                    $items = self::getMenuItemArrayChilds($id, $childs, $items, $index . '—');
            }
        }

        return $items;
    }

    /**
     * self model childs to array
     * @param integer $id
     * @param array $models
     * @param array $items
     * @param string $index
     * @return array
     */
    protected static function getMenuItemArrayChilds($id, $models, $items, $index)
    {
        /** @var self $model */
        foreach ($models as $model) {
            $items[$model->id] = $index . $model->label;

            if ($id == 0) {
                $childs = self::findAll(['parent_id' => $model->id]);
            } else {
                $childs = self::find()->where(['parent_id' => $model->id])->andWhere(['!=', 'id', $id])->all();
            }

            if (!empty($childs))
                $items = self::getMenuItemArrayChilds($id, $childs, $items, $index . '—');
        }

        return $items;
    }

}
