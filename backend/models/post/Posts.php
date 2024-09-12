<?php

namespace backend\models\post;

use backend\behaviors\TranslateDatabaseBehavior;
use common\components\behaviors\AuthorBehavior;
use common\models\User;
use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use backend\behaviors\SlugBehavior;
use yii\behaviors\TimestampBehavior;
use backend\behaviors\MetaTitleBehavior;
use himiklab\sitemap\behaviors\SitemapBehavior;
use yii\imagine\Image;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $slug
 * @property string $title
 * @property string $image
 * @property string $summary
 * @property string $body
 * @property string $icon
 * @property string $svg_code
 * @property string $file
 * @property string $url
 * @property string $source_link
 * @property int $published_date
 * @property string $address
 * @property string $work_position
 * @property string $work_phone
 * @property string $work_email
 * @property string $work_telegram
 * @property string $work_days
 * @property string $work_time
 * @property float $latitude
 * @property float $longitude
 * @property string $type
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $number_pointer
 * @property integer $views
 * @property integer $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property string $category_key
 *
 * @property User $createdBy
 * @property User $deletedBy
 * @property User $updatedBy
 *
 * @property PostFile[] $postFiles
 * @property PostCategories $category
 * @property ProductToPost[] $productToPosts
 */
class Posts extends ActiveRecord
{
    const MAX_IMAGE_SIZE = 2; // MB

    public $category_key; // for validation

    public $uploaded_images;
    public $_images;
    public $deleted_images;
    public $sorted_images;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            AuthorBehavior::className(),
            [
                'class' => SlugBehavior::className(),
                'attribute' => 'title'
            ],
            [
                'class' => MetaTitleBehavior::className(),
                'attribute' => 'title'
            ],
            [
                'class' => TranslateDatabaseBehavior::className(),
                'translateAttributes' => ['title', 'summary', 'body', 'address', 'file', 'work_position', 'work_days', 'work_time', 'meta_title', 'meta_keywords', 'meta_description'],
                'tableName' => static::tableName(),
            ],
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['slug', 'status', 'updated_at']);
                    $model->andWhere(['status' => self::STATUS_ACTIVE]);
                },
                'dataClosure' => function ($model) {
                    /** @var self $model */
                    return [
                        'loc' => Url::to(['/post/view', 'slug' => $model->slug], true),
                        'lastmod' => $model->updated_at,
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.8
                    ];
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $visible_field_in_types = (Yii::$app->user->can('accessAdministrator')) ? [] : Posts::_getVisibilityFields();
        $this_key = $this->category_key;

        return [
            [['category_id', 'slug', 'title', ], 'required'],
            [['category_id', 'views', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['summary', 'body', 'meta_description', 'svg_code'], 'string'],
            [['file', 'image',  'category_key'], 'safe'],
            [['url', 'source_link', 'published_date', 'address', 'work_position', 'work_phone', 'work_email', 'work_telegram', 'work_days', 'work_time', 'work_days', 'type',], 'string'],
            [['slug', 'title', 'image', 'meta_title', 'meta_keywords'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['latitude', 'longitude'], 'number'],
            [['views'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PostCategories::className(), 'targetAttribute' => ['category_id' => 'id']],

            [['image'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['image']));
            }],
            [['file'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['file']));
            }],
            [['summary'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['summary']));
            }],
            [['body'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['body']));
            }],
            [['address'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['address']));
            }],
            [['latitude'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['latitude']));
            }],
            [['longitude'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['longitude']));
            }],
            [['work_days'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['work_days']));
            }],
            [['work_time'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['work_time']));
            }],
            [['work_phone'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['work_phone']));
            }],
            [['work_email'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['work_email']));
            }],
            [['work_telegram'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['work_telegram']));
            }],
            [['published_date'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return in_array($model->category_key,$visible_field_in_types['published_date']);
            }],
//            [['meta_title'], 'required', 'when' => function($model) use ($visible_field_in_types){
//                return in_array($model->category_key,$visible_field_in_types['meta_title']);
//            }],

            [['uploaded_images', 'deleted_images', 'sorted_images', ], 'string'],
//            [['_images'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 6, 'maxSize' => self::MAX_IMAGE_SIZE*1024*1024],
            [['_images'], 'required', 'when' => function($model) use ($visible_field_in_types){
                return (in_array($model->category_key,$visible_field_in_types['images']));
            }],

            [['deleted_at'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'category_id' => Yii::t('model', 'Category'),
            'slug' => Yii::t('model', 'Slug'),
            'title' => Yii::t('model', 'Title'),
            'summary' => Yii::t('model', 'Summary'),
            'body' => Yii::t('model', 'Body'),
            'icon' => Yii::t('model', 'Icon'),
            'image' => Yii::t('model', 'Image'),
            'svg_code' => Yii::t('model', 'Svg Code'),
            'file' => Yii::t('model', 'File'),
            'url' => Yii::t('model', 'Url'),
            'source_link' => Yii::t('model', 'Source Link'),
            'published_date' => Yii::t('model', 'Published Date'),
            'work_position' => Yii::t('model', 'Work Position'),
            'work_phone' => Yii::t('model', 'Work Phone'),
            'work_email' => Yii::t('model', 'Work Email'),
            'work_telegram' => Yii::t('model', 'Work Telegram'),
            'work_days' => Yii::t('model', 'Work Days'),
            'work_time' => Yii::t('model', 'Work Time'),
            'latitude' => Yii::t('model', 'Latitude'),
            'longitude' => Yii::t('model', 'Longitude'),
            'address' => Yii::t('model', 'Address'),
            'type' => Yii::t('model', 'Type'),
            'meta_title' => Yii::t('model', 'Meta Title'),
            'meta_keywords' => Yii::t('model', 'Meta Keywords'),
            'meta_description' => Yii::t('model', 'Meta Description'),
            'uploaded_images' => Yii::t('model', 'Images'),
            'views' => Yii::t('model', 'Views'),
            'weight' => Yii::t('model', 'Weight'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }


    public function upload()
    {
        $data = [];
        foreach ($this->_images as $file) {
            $folder = '/post/images/';
            $directory = Yii::getAlias('@uploadsPath'.$folder);
            if (!is_dir($directory)) {
                \yii\helpers\FileHelper::createDirectory($directory);
            }

            $ext = pathinfo($file->name, PATHINFO_EXTENSION);
            $name = pathinfo($file->name, PATHINFO_FILENAME);
            $generateName = Yii::$app->security->generateRandomString() . ".{$ext}";
            $path = Yii::getAlias('@uploadsPath') . $folder . $generateName;
            if ($file->saveAs($path)) {
                $path = Yii::getAlias('@uploadsUrl') . $folder . $generateName;
                $data[] = [
                    'generate_name' => $generateName,
                    'name' => $name,
                    'path' => $path,
                ];
            }
        }

        return json_encode($data);
    }

    /**
     * @return false
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $this->status = self::STATUS_DELETED;
        $this->deleted_at = time();
        $this->deleted_by = Yii::$app->getUser()->getId();
        $this->save(false);

        return false;
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
     * Gets query for [[DeletedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
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
     * Gets query for [[PostFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostFiles()
    {
        return $this->hasMany(PostFile::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(PostCategories::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductToPosts()
    {
        return $this->hasMany(ProductToPost::className(), ['post_id' => 'id']);
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

    /**
     * @return array
     */
    public static function _getVisibilityFields()
    {
        return [
            'image' => [
                PostCategories::KEY_HEAD,
                PostCategories::KEY_LICENSE,
                PostCategories::KEY_PRESS_CENTER,
                PostCategories::KEY_VACANCY,
                PostCategories::KEY_CLIENTS,
                PostCategories::KEY_PARTNERS,
                PostCategories::KEY_COMPANY_CHARTER,
                PostCategories::KEY_COMPANY_SHARE_HOLDERS,
                ],
            'slug' => [
                PostCategories::KEY_PRESS_CENTER,
                PostCategories::KEY_TENDERS,
                PostCategories::KEY_VACANCY,
                PostCategories::KEY_GALLERY,
                ],
            'summary' => [
                PostCategories::KEY_HEAD,
                PostCategories::KEY_WHY_CHOOSE_US,
                PostCategories::KEY_PRESS_CENTER,
                PostCategories::KEY_VACANCY,
                PostCategories::KEY_FAQ,
                PostCategories::KEY_USEFUL_LINKS,
                PostCategories::KEY_COMPANY_SHARE_HOLDERS,
                PostCategories::KEY_COMPANY_CHARTER,
                ],
            'body' => [
                PostCategories::KEY_PRESS_CENTER,
                PostCategories::KEY_VACANCY,
                PostCategories::KEY_COMPANY_SHARE_HOLDERS,
                ],
            'icon' => [
                ],
            'svg_code' => [
                ],
            'file' => [
                PostCategories::KEY_FILES,
                PostCategories::KEY_ANTI_CORRUPTION_PROGRAM,
                PostCategories::KEY_COMPANY_SHARE_HOLDERS,
                PostCategories::KEY_ANNOUNCEMENTS,
                PostCategories::KEY_COMPANY_CHARTER,
                ],
            'url' => [
                PostCategories::KEY_HOME_ABOUT_US,
                ],
            'source_link' => [
                PostCategories::KEY_USEFUL_LINKS,
                ],
            'published_date' => [
                PostCategories::KEY_FILES,
                ],
            'address' => [
                PostCategories::KEY_BRANCHES,
                ],
            'work_position' => [
                PostCategories::KEY_HEAD,
                ],
            'work_phone' => [
                PostCategories::KEY_HEAD,
                PostCategories::KEY_BRANCHES,
                ],
            'work_email' => [
                PostCategories::KEY_HEAD,
                ],
            'work_telegram' => [
//                PostCategories::KEY_HEAD,
                ],
            'work_days' => [
                PostCategories::KEY_HEAD,
//                PostCategories::KEY_BRANCHES,
                ],
            'work_time' => [
//                PostCategories::KEY_BRANCHES,
                ],
            'latitude' => [
                PostCategories::KEY_BRANCHES,
                ],
            'longitude' => [
                PostCategories::KEY_BRANCHES,
                ],
            'type' => [
                PostCategories::KEY_TENDERS,
                ],
            'meta_title' => [
//                PostCategories::KEY_PRESS_CENTER,
//                PostCategories::KEY_VACANCY,
//                PostCategories::KEY_GALLERY,
                ],
            'views' => [
                ],
            'weight' => [
                ],
            'status' => [
                ],
            'files' => [
                PostCategories::KEY_GALLERY,
                PostCategories::KEY_FILES,
                ],
            'images' => [
                PostCategories::KEY_GALLERY,
                ],
            'created_at' => [
                PostCategories::KEY_HEAD,
                PostCategories::KEY_PRESS_CENTER,
                PostCategories::KEY_VACANCY,
                PostCategories::KEY_FILES,
                PostCategories::KEY_VACANCY,
                PostCategories::KEY_BRANCHES,
                PostCategories::KEY_PARTNERS,
                PostCategories::KEY_CLIENTS,
                PostCategories::KEY_FAQ,
                ],
            'updated_at' => [
                ],
        ];
    }


    /**
     * Default Image Url
     * @return string
     */
    public function getDefaultImageUrl()
    {
        return isset($this->postFiles[0]) ? $this->postFiles[0]->path : '';
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

        if (!empty($uploadedImages = json_decode($this->uploaded_images))) {
            if (empty($position = PostFile::find()->where(['post_id' => $this->id])->max('position'))) $position = 0;
            foreach ($uploadedImages as $file) {
                $image = new PostFile();
                $image->post_id = $this->id;
                $image->generate_name = $file->generate_name;
                $image->name = $file->name;
                $image->path = $file->path;
                $image->position = $position;
                $image->save();
                $position++;
            }
        }

        if (!empty($deletedImages = json_decode($this->deleted_images))) {
            foreach ($deletedImages as $generate_name) {
                if (!empty($image = PostFile::findOne(['generate_name' => $generate_name]))) {
                    $basePath = str_replace("backend", "", Yii::$app->basePath);
                    if ($image->delete())
                    {
                        $filePath = $basePath . $image->path;
                        if (is_file($filePath)){
                            unlink($filePath);
                        }
                    }
                }
            }
        }

        if (!empty($sortedImages = json_decode($this->sorted_images))) {
            $position = 0;
            foreach ($sortedImages as $item) {
                if (!empty($image = PostFile::findOne(['generate_name' => $item->key]))) {
                    $image->position = $position;
                    $image->save();
                    $position++;
                }
            }
        }
    }

}
