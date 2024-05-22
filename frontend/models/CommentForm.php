<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use backend\models\comment\Comments;

/**
 * CommentForm is the model behind the contact form.
 *
 * @property string $model
 * @property integer $model_id
 * @property integer $parent_id
 * @property float $rating
 * @property string $text
 */
class CommentForm extends Model
{
    public $model;
    public $model_id;
    public $parent_id;
    public $rating;
    public $text;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'model_id', 'text'], 'required'],
            [['model_id', 'parent_id'], 'integer'],
            [['rating'], 'number'],
            [['text'], 'string', 'max' => 500],
            [['model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rating' => Yii::t('model', 'Оцените товар'),
            'text' => Yii::t('model', 'Поле для текста'),
        ];
    }

    public function commentSave()
    {
        if (!$this->validate() || Yii::$app->user->isGuest)
            return false;
        $comment = new Comments([
            'author_id' => Yii::$app->user->id,
            'model' => $this->model,
            'model_id' => $this->model_id,
            'parent_id' => $this->parent_id,
            'rating' => $this->rating,
            'text' => $this->text,
            'sessions' => (string)Yii::$app->user->id
        ]);

        if ($comment->save()) {
            Yii::$app->session->setFlash('success', Yii::t('frontend', 'Спасибо за ваш отзыв, он появится после проверки модератором.'));
            return true;
        } else {
            Yii::$app->session->setFlash('error', Yii::t('frontend', 'Что-то пошло не так. Пожалуйста попробуйте еще раз.'));
        }

        return false;
    }
}
