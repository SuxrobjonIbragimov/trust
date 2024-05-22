<?php

namespace backend\models\menu;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use backend\modules\translatemanager\behaviors\TranslateBehavior;

/**
 * This is the model class for table "menus".
 *
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property MenuItems[] $menuItems
 * @property MenuItems[] $menuItemsActive
 * @property MenuItems[] $menuItemsActiveCache
 */
class Menus extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menus}}';
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
                'translateAttributes' => ['name', 'description'],
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
            [['key', 'name'], 'required'],
            [['description'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['key', 'name'], 'string', 'min' => 3, 'max' => 32],
            [['key'], 'unique'],
            [['key'], 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u'],
            [['status'], 'default', 'value' => self::STATUS_INACTIVE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'key' => Yii::t('model', 'Key'),
            'name' => Yii::t('model', 'Name'),
            'description' => Yii::t('model', 'Description'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItems::className(), ['menu_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItemsActive()
    {
        return $this->hasMany(MenuItems::className(), ['menu_id' => 'id'])
            ->andOnCondition(['status' => MenuItems::STATUS_ACTIVE, 'parent_id' => NULL])
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
            $key = 'menu_tmp_'.$id.'_'._lang();
            $data = $cache->get($key);

            if ($data === false) {
                $data = $key;
                _cache_file(true);
                $cache->set($key, $data, $cache_db);
            }

            $dependency = new \yii\caching\FileDependency(['fileName' => 'lang.txt']);
            $result = MenuItems::getDb()->cache(function ($db) use ($id) {
                return MenuItems::find()->where(['menu_id' => $id,'parent_id' => NULL,'status' => static::STATUS_ACTIVE])
                    ->orderBy(['weight' => SORT_ASC])
                    ->all();
            }, $cache_db, $dependency);
        } else {
            $result = MenuItems::find()->where(['menu_id' => $id,'parent_id' => NULL,'status' => static::STATUS_ACTIVE])
                ->orderBy(['weight' => SORT_ASC])
                ->all();
        }

        return $result;

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

        return isset($array[$this->status]) ? $array[$this->status] : '';
    }

    /**
     * Get Admin menu items
     * @return array
     */
    public static function getAdminMenu()
    {
        $data = [];
        if (($menu = self::findOne(['key' => 'admin_menu', 'status' => self::STATUS_ACTIVE])) !== null) {

            array_push($data, [
                'label' => $menu->name,
                'options' => ['class' => 'header']
            ]);

            if (!empty($models = MenuItems::getActiveMenuItemsByCondition(['menu_id' => $menu->id, 'parent_id' => NULL]))) {
                /** @var MenuItems $model */
                foreach ($models as $model) {
                    if (!empty($childs = self::getAdminMenuItemChilds($model->id))) {
                        array_push($data, [
                            'label' => $model->label,
                            'icon' => $model->class,
                            'url' => ($model->url != '#') ? [$model->url] : $model->url,
                            'items' => $childs
                        ]);
                    } else {
                        array_push($data, [
                            'label' => $model->label,
                            'icon' => $model->class,
                            'url' => ($model->url != '#') ? [$model->url] : $model->url,
                        ]);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @param integer $id
     * @return array
     */
    protected static function getAdminMenuItemChilds($id)
    {
        $data = [];

        if (!empty($models = MenuItems::getActiveMenuItemsByCondition(['parent_id' => $id]))) {
            /** @var MenuItems $model */
            foreach ($models as $model) {
                if (!empty($childs = self::getAdminMenuItemChilds($model->id))) {
                    array_push($data, [
                        'label' => $model->label,
                        'icon' => $model->class,
                        'url' => ($model->url != '#') ? [$model->url] : $model->url,
                        'items' => $childs
                    ]);
                } else {
                    array_push($data, [
                        'label' => $model->label,
                        'icon' => $model->class,
                        'url' => ($model->url != '#') ? [$model->url] : $model->url,
                    ]);
                }
            }
        }

        return $data;
    }

}
