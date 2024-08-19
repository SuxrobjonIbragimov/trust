<?php

namespace backend\models\post;

use backend\behaviors\TranslateDatabaseBehavior;
use common\components\behaviors\AuthorBehavior;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use backend\behaviors\SlugBehavior;
use yii\behaviors\TimestampBehavior;
use backend\behaviors\MetaTitleBehavior;
use himiklab\sitemap\behaviors\SitemapBehavior;

/**
 * This is the model class for table "post_categories".
 *
 * @property integer $id
 * @property string $slug
 * @property string $key
 * @property string $name
 * @property string $icon
 * @property string $image
 * @property string $description
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property string|null $totalVotes
 *
 * @property User $createdBy
 * @property User $deletedBy
 * @property User $updatedBy
 *
 * @property Posts[] $posts
 * @property Posts[] $activePosts
 */
class PostCategories extends ActiveRecord
{
    const KEY_WHY_CHOOSE_US = 'why_choose_us';
    const KEY_COMPANIES_SERVED = 'companies_served';
    const KEY_CLIENTS = 'clients';
    const KEY_PARTNERS = 'partners';
    const KEY_VACANCY = 'vacancy';

    const KEY_PRESS_CENTER = 'press_center';
    const KEY_NEWS = 'news';
    const KEY_EVENTS = 'events';
    const KEY_TENDERS = 'tenders';
    const KEY_GALLERY = 'gallery';

    const KEY_HEAD = 'head';
    const KEY_SERVICE = 'services';
    const OUR_ADVANTAGE = 'advantage';
    const KEY_LICENSE = 'license';
    const KEY_BRANCHES = 'branches';

    const KEY_COMPANY_CHARTER = 'company_charter';
    const KEY_MATERIAL_FACTS = 'material_facts';
    const KEY_FINANCE = 'finance';
    const KEY_LAWS = 'laws';
    const KEY_CODEX = 'codex';
    const KEY_DECREE = 'decree';
    const KEY_POSITION = 'position';
    const KEY_AFFILIATE_LIST = 'affiliate_list';
    const KEY_VALUABLE_PAPERS = 'valuable_papers';
    const KEY_PAID_DIVIDENDS = 'paid_dividends';
    const KEY_FILES = 'files';
    const KEY_ANTI_CORRUPTION_PROGRAM = 'anti-corruption-program';
    const KEY_FAQ = 'faq';
    const KEY_STRATEGY = 'strategy';
    const KEY_RESULTS_VOTING = 'results_voting';
    const KEY_NEWS_SHAREHOLDERS = 'news_shareholders';
    const KEY_REGULATIONS = 'regulations';
    const KEY_CORPORATE_GOVERNANCE = 'corporate_governance';
    const KEY_INFORMATION_ACQUISITION = 'information_acquisition';

    const KEY_BUSINESS_PLAN = 'business_plan';
    const KEY_PROGRESS_REPORT = 'progress_report';
    const KEY_AUDIT_REPORT = 'audit_report';

    const KEY_TEXT_BLOCK = 'text_block';

    const KEY_ONLINE_VOTING = 'online_voting';
    const KEY_USEFUL_LINKS = 'useful_links';

    const KEY_HOME_ABOUT_US = 'home_about_us';
    const KEY_COMPANY_SHARE_HOLDERS = 'company_share_holders';

    const ACTIVE_CHILD_LIMIT = 6;
    const ACTIVE_CHILD_LIMIT_FAQ = 5;
    const ACTIVE_CHILD_LIMIT_LATEST_NEWS = 3;
    const ACTIVE_CHILD_LIMIT_FACTS = 5;
    const ACTIVE_CHILD_LIMIT_LIMIT_PARTNERS = 16;
    const ACTIVE_CHILD_OUR_COMPETENCIES = 4;
    const ACTIVE_CHILD_LIMIT_LATEST_PORTFOLIO = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_categories';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            MetaTitleBehavior::className(),
            AuthorBehavior::className(),
            [
                'class' => TranslateDatabaseBehavior::className(),
                'translateAttributes' => ['name', 'description',  'meta_title', 'meta_keywords', 'meta_description'],
                'tableName' => static::tableName(),
            ],
            SlugBehavior::className(),
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['slug', 'status', 'updated_at']);
                    $model->andWhere(['status' => self::STATUS_ACTIVE]);
                    $model->andWhere(['!=', 'key', self::KEY_ONLINE_VOTING]);
                },
                'dataClosure' => function ($model) {
                    /** @var self $model */
                    return [
                        'loc' => Url::to(['/post/category', 'slug' => $model->slug], true),
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
        return [
            [['slug', 'key', 'name'], 'required'],
            [['description', 'meta_description'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'name', 'icon', 'image', 'meta_title', 'meta_keywords'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['key'], 'unique'],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
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
            'slug' => Yii::t('model', 'Slug'),
            'key' => Yii::t('model', 'Key'),
            'name' => Yii::t('model', 'Name'),
            'icon' => Yii::t('model', 'Icon'),
            'image' => Yii::t('model', 'Image'),
            'description' => Yii::t('model', 'Description'),
            'meta_title' => Yii::t('model', 'Meta Title'),
            'meta_keywords' => Yii::t('model', 'Meta Keywords'),
            'meta_description' => Yii::t('model', 'Meta Description'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
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
     * @return bool|int|mixed|string|null
     */
    public function getTotalVotes()
    {
        $total_votes = !empty($this->activePosts) ? $this->getActivePosts()->sum('views') : 0;
        return $total_votes;
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
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Posts::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivePosts()
    {
        return $this->hasMany(Posts::className(), ['category_id' => 'id'])
            ->onCondition(['status' => Posts::STATUS_ACTIVE])
            ->orderBy(['weight' => SORT_ASC, 'id' => SORT_DESC]);
    }

    /**
     * @return ActiveDataProvider
     */
    public function getPostsDataProvider()
    {
        $query = new ActiveDataProvider([
            'query' => Posts::find()->where([
                'category_id' => $this->id,
                'status' => Posts::STATUS_ACTIVE,
            ])->orderBy(['weight' => SORT_ASC, 'id' => SORT_DESC]),
            'sort' => false,
            'pagination' => [
                'defaultPageSize' => self::_getPaginationBYKeys($this->key)
            ]
        ]);
        if (!empty(Yii::$app->request->get('y')) && is_numeric(Yii::$app->request->get('y'))) {
            $query->query->andWhere(["EXTRACT(YEAR FROM published_date)" => intval(Yii::$app->request->get('y'))]);
        }
        return $query;
    }

    /**
     * Status
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = -1;

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
     * @param $keyword
     * @return PostCategories|false|mixed
     */
    public static function getItemByKey($keyword)
    {
        $cache_db = !empty(Yii::$app->params['cache']['db.htmlParl']) ? Yii::$app->params['cache']['db.htmlParl'] : 0;
        if ($cache_db) {
            $cache = Yii::$app->cache;
            $key = 'postCategories_'.$keyword.'_'._lang();
            $data = $cache->get($key);

            $dependency = new \yii\caching\FileDependency(['fileName' => 'lang.txt']);
            if ($data === false) {
                $model = self::findOne(['key' => $keyword]);
                $data = !empty($model) ? $model : false;
                $cache->set($key, $model, $cache_db, $dependency);
            }
        } else {
            $model = self::findOne(['key' => $keyword]);
            $data = !empty($model) ? $model : false;
        }
        return $data;
    }


    /**
     * KeyList Array
     * @param integer|null $key
     * @return array|string
     */
    public static function getKeyListArray($key = null)
    {
        $array = [
            self::KEY_WHY_CHOOSE_US => self::KEY_WHY_CHOOSE_US,
            self::KEY_COMPANIES_SERVED => self::KEY_COMPANIES_SERVED,
            self::KEY_PARTNERS => self::KEY_PARTNERS,
            self::KEY_NEWS => self::KEY_NEWS,
            self::KEY_EVENTS => self::KEY_EVENTS,
            self::KEY_CLIENTS => self::KEY_CLIENTS,
            self::KEY_VACANCY => self::KEY_VACANCY,
            self::KEY_GALLERY => self::KEY_GALLERY,
            self::KEY_HEAD => self::KEY_HEAD,
            self::KEY_LICENSE => self::KEY_LICENSE,
            self::KEY_COMPANY_CHARTER => self::KEY_COMPANY_CHARTER,
            self::KEY_MATERIAL_FACTS => self::KEY_MATERIAL_FACTS,
            self::KEY_FINANCE => self::KEY_FINANCE,
            self::KEY_LAWS => self::KEY_LAWS,
            self::KEY_CODEX => self::KEY_CODEX,
            self::KEY_DECREE => self::KEY_DECREE,
            self::KEY_POSITION => self::KEY_POSITION,
            self::KEY_BRANCHES => self::KEY_BRANCHES,
            self::KEY_TENDERS => self::KEY_TENDERS,
            self::KEY_AFFILIATE_LIST => self::KEY_AFFILIATE_LIST,
            self::KEY_VALUABLE_PAPERS => self::KEY_VALUABLE_PAPERS,
            self::KEY_PAID_DIVIDENDS => self::KEY_PAID_DIVIDENDS,
            self::KEY_STRATEGY => self::KEY_STRATEGY,
            self::KEY_RESULTS_VOTING => self::KEY_RESULTS_VOTING,
            self::KEY_NEWS_SHAREHOLDERS => self::KEY_NEWS_SHAREHOLDERS,
            self::KEY_REGULATIONS => self::KEY_REGULATIONS,
            self::KEY_CORPORATE_GOVERNANCE => self::KEY_CORPORATE_GOVERNANCE,
            self::KEY_INFORMATION_ACQUISITION => self::KEY_INFORMATION_ACQUISITION,
            self::KEY_FILES => self::KEY_FILES,
            self::KEY_FAQ => self::KEY_FAQ,

            self::KEY_BUSINESS_PLAN => self::KEY_BUSINESS_PLAN,
            self::KEY_PROGRESS_REPORT => self::KEY_PROGRESS_REPORT,
            self::KEY_AUDIT_REPORT => self::KEY_AUDIT_REPORT,
            self::KEY_ONLINE_VOTING => self::KEY_ONLINE_VOTING,
            self::KEY_TEXT_BLOCK => self::KEY_TEXT_BLOCK,
        ];

        return $key === null ? $array : $array[$key];
    }

    /**
     * KeyType Array
     * @param integer|null $key
     * @return array|string
     */
    public static function getKeyTypeArray($key = null)
    {
        $array = [
            self::KEY_WHY_CHOOSE_US => [self::KEY_WHY_CHOOSE_US],
            self::KEY_COMPANIES_SERVED => [self::KEY_COMPANIES_SERVED],
            self::KEY_PARTNERS => [
                self::KEY_PARTNERS => self::KEY_PARTNERS,
                self::KEY_CLIENTS => self::KEY_CLIENTS,
            ],
            self::KEY_PRESS_CENTER => [
                self::KEY_PRESS_CENTER => self::KEY_PRESS_CENTER,
                self::KEY_NEWS => self::KEY_NEWS,
                self::KEY_EVENTS => self::KEY_EVENTS,
                self::KEY_TENDERS => self::KEY_TENDERS,
                self::KEY_GALLERY => self::KEY_GALLERY,
            ],
            self::KEY_HEAD => [self::KEY_HEAD],
            self::KEY_VACANCY => [self::KEY_VACANCY],
            self::KEY_LICENSE => [self::KEY_LICENSE],
            self::KEY_FILES => [
                self::KEY_COMPANY_CHARTER => self::KEY_COMPANY_CHARTER,
                self::KEY_MATERIAL_FACTS => self::KEY_MATERIAL_FACTS,
                self::KEY_FINANCE => self::KEY_FINANCE,
                self::KEY_LAWS => self::KEY_LAWS,
                self::KEY_CODEX => self::KEY_CODEX,
                self::KEY_DECREE => self::KEY_DECREE,
                self::KEY_POSITION => self::KEY_POSITION,
                self::KEY_AFFILIATE_LIST => self::KEY_AFFILIATE_LIST,
                self::KEY_VALUABLE_PAPERS => self::KEY_VALUABLE_PAPERS,
                self::KEY_PAID_DIVIDENDS => self::KEY_PAID_DIVIDENDS,
                self::KEY_STRATEGY => self::KEY_STRATEGY,
                self::KEY_RESULTS_VOTING => self::KEY_RESULTS_VOTING,
                self::KEY_NEWS_SHAREHOLDERS => self::KEY_NEWS_SHAREHOLDERS,
                self::KEY_REGULATIONS => self::KEY_REGULATIONS,
                self::KEY_CORPORATE_GOVERNANCE => self::KEY_CORPORATE_GOVERNANCE,
                self::KEY_INFORMATION_ACQUISITION => self::KEY_INFORMATION_ACQUISITION,
                self::KEY_FILES => self::KEY_FILES,

                self::KEY_BUSINESS_PLAN => self::KEY_BUSINESS_PLAN,
                self::KEY_PROGRESS_REPORT => self::KEY_PROGRESS_REPORT,
                self::KEY_AUDIT_REPORT => self::KEY_AUDIT_REPORT,
            ],
            self::KEY_BRANCHES => [self::KEY_BRANCHES],
            self::KEY_FAQ => [self::KEY_FAQ],
            self::KEY_TEXT_BLOCK => [
                self::KEY_TEXT_BLOCK,
            ],
            self::KEY_ONLINE_VOTING => [
                self::KEY_ONLINE_VOTING,
            ],
        ];

        $response = $key;
        if ($key !== null) {
            foreach ($array as $key_ar => $value) {
                if (in_array($key,$value)) {
                    $response = $key_ar;
                    break;
                }
            }
        }
        return $response;
    }

    /**
     * KeyColClass Array
     * @param integer|null $key
     * @return array|string
     */
    public static function getKeyColClassArray($key = null)
    {
        $array = [
            self::KEY_WHY_CHOOSE_US => 'col-sm-3 col-md-3 col-lg-3',
            self::KEY_COMPANIES_SERVED => 'col-sm-3 col-md-3 col-lg-3',
            self::KEY_PARTNERS => 'col-8 col-sm-4 col-md-3 col-lg-2',
            self::KEY_PRESS_CENTER => 'col-12 col-sm-8 col-md-6 col-lg-4 col-xl-4 col-xxl-4 my-4',
            self::KEY_LICENSE => 'col-12 col-md-12 col-lg-4',
            self::KEY_VACANCY => 'col-12 col-sm-10 col-md-6 col-lg-4',
            self::KEY_BRANCHES => 'col-12 col-sm-6 col-md-4 col-lg-4 my-4 my-4',
            self::KEY_HEAD => 'col-lg-12 col-md-10 col-sm-12 col-12',
        ];

        $response = $array;
        if ($key !== null && !empty($array[$key])) {
            $response = $array[$key];
        } elseif ($key !== null) {
            $response = 'col-12';
        }
        return $response;
    }

    public static function _getSearchableKeys()
    {
        $array = [
            self::KEY_NEWS => self::KEY_NEWS,
            self::KEY_EVENTS => self::KEY_EVENTS,
            self::KEY_TENDERS => self::KEY_TENDERS,
            self::KEY_GALLERY => self::KEY_GALLERY,
        ];
        return $array;
    }

    /**
     * @return array
     */
    public static function _getSearchableItems()
    {
        return ArrayHelper::map(self::find()
            ->where(['key' => self::_getSearchableKeys()])
            ->all(),'id', 'id');
    }

    /**
     * @param $key
     * @return int
     */
    public static function _getPaginationBYKeys($key = null)
    {
        $array = [
            self::KEY_WHY_CHOOSE_US => 12,
            self::KEY_COMPANIES_SERVED => 12,
            self::KEY_PARTNERS => 12,
            self::KEY_NEWS => 12,
            self::KEY_EVENTS => 12,
            self::KEY_CLIENTS => 12,
            self::KEY_VACANCY => 12,
            self::KEY_GALLERY => 12,
            self::KEY_HEAD => 12,
            self::KEY_LICENSE => 12,
            self::KEY_COMPANY_CHARTER => 12,
            self::KEY_MATERIAL_FACTS => 12,
            self::KEY_FINANCE => 12,
            self::KEY_LAWS => 12,
            self::KEY_CODEX => 12,
            self::KEY_DECREE => 12,
            self::KEY_POSITION => 12,
            self::KEY_BRANCHES => 50,
            self::KEY_TENDERS => 12,
            self::KEY_AFFILIATE_LIST => 12,
            self::KEY_VALUABLE_PAPERS => 12,
            self::KEY_PAID_DIVIDENDS => 12,
            self::KEY_FILES => 12,
            self::KEY_FAQ => 12,
        ];

        $response = 12;
        if ($key !== null && !empty($array[$key])) {
            $response = $array[$key];
        }
        return $response;
    }

    /**
     * @return array
     */
    public function getVotingList()
    {
        $data = ArrayHelper::map($this->activePosts, 'id', 'title');
//        dd($data);
        return $data;
    }
}
