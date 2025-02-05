<?php
/**
 * Created by PhpStorm.
 * User: nodir
 * Date: 6/12/17
 * Time: 4:07 PM
 */

namespace backend\behaviors;


use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\validators\UniqueValidator;
use yii\behaviors\SluggableBehavior;
use dosamigos\transliterator\TransliteratorHelper;

class SlugBehavior extends SluggableBehavior
{
    /** @var string */
    public $slugAttribute = 'slug';

    /** @var string|array */
    public $attribute = 'name';

    /** @var bool */
    public $ensureUnique = true;

    /** @var string */
    public $replacement = '_';

    /** @var bool */
    public $lowercase = true;

    /** @var bool */
    public $immutable = true;

    /**
     * @var string
     * @link http://userguide.icu-project.org/transforms/general
     */
    public $transliterateOptions = 'Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;';
    /** @var bool */
    private $slugIsEmpty = false;

    /**
     * @inheritdoc
     * @param ActiveRecord $owner
     */
    public function attach($owner)
    {
        $this->attribute = (array)$this->attribute;
        $primaryKey = $owner->primaryKey();
        $primaryKey = is_array($primaryKey) ? array_shift($primaryKey) : $primaryKey;

        if (in_array($primaryKey, $this->attribute) && $owner->getIsNewRecord())
            $this->attributes[ActiveRecord::EVENT_AFTER_INSERT] = $this->slugAttribute;

        parent::attach($owner);
    }

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        /* @var $owner ActiveRecord */
        $owner = $this->owner;

        if (!empty($owner->{$this->slugAttribute}) && !$this->slugIsEmpty && $this->immutable) {
            $slug = $owner->{$this->slugAttribute};
        } else {
            if ($owner->getIsNewRecord())
                $this->slugIsEmpty = true;

            if ($this->attribute !== null) {
                $attributes = $this->attribute;

                $slugParts = [];
                foreach ($attributes as $attribute) {
                    $slugParts[] = ArrayHelper::getValue($this->owner, $attribute);
                }
                $slug = $this->slug(implode($this->replacement, $slugParts), $this->replacement, $this->lowercase);

                if (!$owner->getIsNewRecord() && $this->slugIsEmpty) {
                    $owner->{$this->slugAttribute} = $slug;
                    $owner->save(false, [$this->slugAttribute]);
                }
            } else {
                $slug = parent::getValue($event);
            }
        }

        if ($this->ensureUnique) {
            $baseSlug = $slug;
            $iteration = 0;
            while (!$this->validateSlug($slug)) {
                $iteration++;
                $slug = $this->generateUniqueSlug($baseSlug, $iteration);
            }
        }

        return $slug;
    }

    /**
     * @param string $string
     * @param string $replacement
     * @param bool $lowercase
     * @return string
     */
    private function slug($string, $replacement, $lowercase = true)
    {
        $string = $this->transliterate($string);
        $string = preg_replace('/[^\/\\\a-zA-Z0-9=\s—–-]+/u', '', $string);
        $string = preg_replace('/[\/\\\=\s—–-]+/u', $replacement, $string);
        $string = trim($string, $replacement);

        return $lowercase ? mb_strtolower($string) : $string;
    }

    /**
     * @param string $string
     * @return string
     */
    private function transliterate($string)
    {
        return (extension_loaded('intl') === true) ?
            transliterator_transliterate($this->transliterateOptions, $string) :
            TransliteratorHelper::process($string);
    }

    /**
     * @inheritdoc
     */
    protected function validateSlug($slug)
    {
        /* @var $validator UniqueValidator */
        $validator = Yii::createObject(array_merge([
            'class' => UniqueValidator::className()
        ], $this->uniqueValidator));

        /** @var ActiveRecord $model */
        $model = clone $this->owner;
        $model->clearErrors();
        $model->{$this->slugAttribute} = $slug;

        $validator->validateAttribute($model, $this->slugAttribute);
        return !$model->hasErrors();
    }

    /**
     * @inheritdoc
     */
    protected function generateUniqueSlug($baseSlug, $iteration)
    {
        return is_callable($this->uniqueSlugGenerator) ?
            call_user_func($this->uniqueSlugGenerator, $baseSlug, $iteration, $this->owner) :
            $baseSlug . $this->replacement . ($iteration + 1);
    }
}
