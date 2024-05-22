<?php

namespace backend\behaviors;

use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;

class MetaTitleBehavior extends AttributeBehavior
{
    /**
     * @var string
     */
    public $metaTitleAttribute = 'meta_title';

    /**
     * @var string
     */
    public $attribute = 'name';

    /**
     * @inheritdoc
     *
     * In case, when the property is `null`, the value of `Yii::$app->user->id` will be used as the value.
     */
    public $value;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->metaTitleAttribute],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => [$this->metaTitleAttribute],
            ];
        }
    }

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return empty($this->owner->{$this->metaTitleAttribute}) ?
                $this->owner->{$this->attribute} :
                $this->owner->{$this->metaTitleAttribute};
        }

        return parent::getValue($event);
    }
}
