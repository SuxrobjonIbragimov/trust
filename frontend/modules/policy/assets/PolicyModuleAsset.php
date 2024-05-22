<?php

namespace frontend\modules\policy\assets;
use Yii;
use yii\web\AssetBundle;

class PolicyModuleAsset extends AssetBundle
{

    public $sourcePath = '@frontend/modules/policy/public';
    public $css = ['css/policy.css'];
    public $js = ['js/policy.js'];


    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'frontend\assets\V1Asset',
    ];


    public function init()
    {
        parent::init();

        $v = '?v=0.0.1';
        foreach ($this->css as $key => $path) {
            $file_path = Yii::getAlias("{$this->sourcePath}/".$path);
            if (file_exists($file_path)) {
                $v = '?v=' . filemtime($file_path);
            }
            $this->css[$key] = $path.$v;
        }
        foreach ($this->js as $key => $path) {
            $file_path = Yii::getAlias("{$this->sourcePath}/".$path);
            if (file_exists($file_path)) {
                $v = '?v=' . filemtime($file_path);
            }
            $this->js[$key] = $path.$v;
        }
    }

}