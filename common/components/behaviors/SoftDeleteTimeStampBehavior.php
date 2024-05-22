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

class SoftDeleteTimeStampBehavior extends AttributeBehavior
{
    public $deletedAtAttribute = 'deleted_at';

    public $value;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_DELETE => [$this->deletedAtAttribute],
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
            return _date_current();
        }
        return parent::getValue($event);
    }

}