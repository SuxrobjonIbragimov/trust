<?php
/**
 * Created by PhpStorm.
 * User: shohr
 * Date: 13-Apr-20
 * Time: 17:54
 */

namespace common\components\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class SoftDeleteAuthorBehavior extends AttributeBehavior
{
    public $deletedByAttribute = 'deleted_by';

    public $value;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_DELETE => [$this->deletedByAttribute],
            ];
        }
    }

    /**
     * {@inheritdoc}
     *
     * In case, when the [[value]] is `null`, the result of the current user and current dateTime
     * will be used as value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return \Yii::$app->getUser()->id;
        }
        return parent::getValue($event);
    }

}