<?php
namespace frontend\widgets;
/**
 * Created by PhpStorm.
 * User: Shohrux Haqberdiyev
 * Date: 29.09.2020
 * Time: 0:11
 */

use backend\models\review\Subscribe;
use yii\base\Widget;
use Yii;

class SubscribeWidget extends Widget
{
    public $model;
    public $message;
    public $type;

    public function init()
    {
        parent::init();
        $model = new Subscribe();
        $model->user_id = Yii::$app->user->id;
        if ($this->model === null) {
            $this->model = $model;
        }
        if ($this->message === null) {
            $this->message = null;
        }
        if ($this->type === null) {
            $this->type = null;
        }
    }

    public function run()
    {
        return $this->render('subscribe',[
            'model' => $this->model,
            'type' => $this->type,
            'message' => $this->message,
        ]);
    }

}