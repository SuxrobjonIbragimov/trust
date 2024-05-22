<?php

namespace common\models;

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use Yii;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use mihaildev\elfinder\InputFile;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $key
 * @property string $label
 * @property string $value
 * @property integer $type
 * @property integer $required
 * @property integer $created_at
 * @property integer $updated_at
 */
class Settings extends ActiveRecord
{
    const KEY_MAIN_LOGO = 'logo';
    const KEY_MAIN_LOGO_PNG = 'logo_png';
    const KEY_MAIN_FOOTER_LOGO = 'footer_logo';
    const KEY_SITE_NAME = 'site_name';
    const KEY_MAIN_PHONE = 'main_phone';
    const KEY_MAIN_EMAIL = 'main_email';
    const KEY_MAIN_TELEGRAM = 'main_telegram';
    const KEY_HOME_PAGE_FOOTER_TEXT = 'home_page_footer_text';
    const KEY_OSGO_LIMITED = 'osgo_limited';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'label'], 'required'],
            [['value'], 'string'],
            [['type', 'required', 'created_at', 'updated_at'], 'integer'],
            [['key', 'label'], 'string', 'max' => 64],
            [['key'], 'unique'],
            [['key'], 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u'],
            [['required'], 'default', 'value' => 0],
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
            'label' => Yii::t('model', 'Label'),
            'value' => Yii::t('model', 'Value'),
            'type' => Yii::t('model', 'Type'),
            'required' => Yii::t('model', 'Required'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }

    /**
     * Type
     */
    const TYPE_TEXT = 0;
    const TYPE_FILE = 1;
    const TYPE_TEXTAREA = 2;
    const TYPE_CKEDITOR = 3;
    const TYPE_CHECKBOX = 4;

    /**
     * Type Array
     * @return array
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_TEXT => Yii::t('model', 'Text'),
            self::TYPE_FILE => Yii::t('model', 'File'),
            self::TYPE_TEXTAREA => Yii::t('model', 'Textarea'),
            self::TYPE_CKEDITOR => Yii::t('model', 'CKEditor'),
            self::TYPE_CHECKBOX => Yii::t('model', 'Checkbox'),
        ];
    }

    /**
     * Type Field
     */
    public function getTypeField()
    {
        switch ($this->type) {
            case self::TYPE_TEXT:
                echo Html::input('text', $this->key, $this->value, [
                    'id' => $this->key,
                    'class' => 'form-control',
                    'required' => $this->required ? true : false
                ]);
                break;
            case self::TYPE_FILE:
                echo InputFile::widget([
                    'controller' => 'elfinder',
                    'path' => '',
                    'name' => $this->key,
                    'value' => $this->value,
                    'id' => $this->key,
                    'template' => '{input}<div class="input-group-btn">{button}</div>',
                    'options' => ['class' => 'form-control', 'required' => $this->required ? true : false],
                    'buttonOptions' => ['class' => 'btn btn-primary'],
                    'buttonName' => Yii::t('views', 'Select...'),
                    'multiple' => false
                ]);
                break;
            case self::TYPE_TEXTAREA:
                echo Html::textarea($this->key, $this->value, [
                    'id' => $this->key,
                    'class' => 'form-control',
                    'rows' => 6,
                    'required' => $this->required ? true : false
                ]);
                break;
            case self::TYPE_CKEDITOR:
                echo CKEditor::widget([
                    'name' => $this->key,
                    'value' => $this->value,
                    'options' => ['required' => $this->required ? true : false],
                    'editorOptions' => ElFinder::ckeditorOptions(
                        ['elfinder', 'path' => '/'],
                        [
                            'allowedContent' => true,
                            'height' => 250,
                            'toolbarGroups' => [
                                'mode', 'undo', 'selection',
                                ['name' => 'clipboard', 'groups' => ['clipboard', 'doctools', 'cleanup']],
                                ['name' => 'basicstyles', 'groups' => ['basicstyles', 'colors']],
                                ['name' => 'paragraph', 'groups' => ['align', 'templates', 'list', 'indent']],
                                'styles', 'insert', 'blocks', 'links', 'find', 'tools', 'about',
                            ],
                            'removeButtons' => 'Flash,Smiley,Iframe,HorizontalRule,SpecialChar,PageBreak'
                        ]
                    ),
                ]);
                break;
            case self::TYPE_CHECKBOX:
                echo Html::checkbox($this->key, $this->value, [
                    'id' => $this->key,
                    'class' => 'form-control',
                    'required' => $this->required ? true : false
                ]);
                break;
        }
    }

    /**
     * Get Site Logo Value
     * @return string
     */
    public static function getLogoValue($key='logo')
    {
        return self::getValueByKey($key);
    }

    /**
     * Get Site Logo Value
     * @return string
     */
    public static function getLogoValuePng($key='logo_png')
    {
        return self::getValueByKey($key);
    }

    /**
     * Get Setting Value
     * @param string $key
     * @return string
     */
    public static function getSettingValue($key)
    {
        return self::getValueByKey($key);
    }

    public static function getValueByKey($keyword)
    {
        $cache_db = !empty(Yii::$app->params['cache']['db.siteOption']) ? Yii::$app->params['cache']['db.siteOption'] : 0;
        if ($cache_db) {
            $cache = Yii::$app->cache;
            $key = 'siteOption_'.$keyword.'_'._lang();
            $data = $cache->get($key);

            $dependency = new \yii\caching\FileDependency(['fileName' => 'lang.txt']);
            if ($data === false) {
                $model = self::findOne(['key' => $keyword]);
                $data = !empty($model->value) ? $model->value : false;
                $cache->set($key, $data, $cache_db, $dependency);
            }
        } else {
            $model = self::findOne(['key' => $keyword]);
            $data = !empty($model->value) ? $model->value : false;
        }
        return $data;
    }
}
