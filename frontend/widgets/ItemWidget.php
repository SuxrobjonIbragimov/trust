<?php
namespace frontend\widgets;
/**
 * Created by PhpStorm.
 * User: Shohrux Haqberdiyev
 * Date: 21.09.2020
 * Time: 22:35
 */

use yii\base\Widget;

class ItemWidget extends Widget
{
    public $items = [];
    public $itemsOptions = [];
    public $itemsClass = '';
    public $imageClass = 'post-image';
    public $item = null;
    public $author = false;
    public $is_front = false;
    public $is_mini = false;
    public $list_view = false;
    public $imgSizes = [
        'width' => 520,
        'height' => 370,
    ];

    public function init()
    {
        parent::init();
        if (empty($this->items)) {
            $this->items[] = $this->item;
        }
    }

    public function run()
    {
        return $this->render('_item',[
            'items' => $this->items,
            'itemsOptions' => $this->itemsOptions,
            'itemsClass' => $this->itemsClass,
            'imageClass' => $this->imageClass,
            'is_front' => $this->is_front,
            'is_mini' => $this->is_mini,
            'imgSizes' => $this->imgSizes,
        ]);
    }

}