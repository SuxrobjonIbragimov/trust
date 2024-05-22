<?php
namespace frontend\widgets;
/**
 * Created by PhpStorm.
 * User: Shohrux Haqberdiyev
 * Date: 29.03.2020
 * Time: 0:11
 */

use backend\models\content\SocialNetwork;
use yii\base\Widget;
use Yii;

class SocialNetworksWidget extends Widget
{
    public $socialNetworks;
    public $is_mobile = false;
    public $has_image = false;
    public $tag_class = 'footer-social d-flex align-items-center justify-content-between nav';
    public $item_class = '';
    public $item_class_has = false;

    public function init()
    {
        parent::init();
        if ($this->socialNetworks === null) {
            $cache_db = !empty(Yii::$app->params['cache']['db.socialNetwork']) ? Yii::$app->params['cache']['db.socialNetwork'] : null;
            if ($cache_db) {
                $socialNetworks_ = SocialNetwork::getDb()->cache(function ($db) {
                    return SocialNetwork::find()
                        ->select(['id','name','url','html_class as fa_icon', 'html_item_class', 'image'])
                        ->where(['status'=>SocialNetwork::STATUS_ACTIVE])
                        ->orderBy('weight')
                        ->indexBy('id')
                        ->asArray()
                        ->all();
                },$cache_db);
            } else {
                $socialNetworks_ = SocialNetwork::find()
                    ->select(['id','name','url','html_class as fa_icon', 'html_item_class', 'image'])
                    ->where(['status'=>SocialNetwork::STATUS_ACTIVE])
                    ->orderBy('weight')
                    ->indexBy('id')
                    ->asArray()
                    ->all();
            }

            $this->socialNetworks = $socialNetworks_;
        }

    }

    public function run()
    {
        return $this->render('socialNetwork',[
            'socialNetworks' => $this->socialNetworks,
            'has_image' => $this->has_image,
            'tag_class' => $this->tag_class,
            'item_class' => $this->item_class,
            'item_class_has' => $this->item_class_has,
        ]);
    }

}