<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * BusinessTaxAsset bundle for the Theme BusinessTax css and js files.
 */
class V1Asset extends AssetBundle
{
    public $basePath = '@webroot/themes/v1';
    public $baseUrl = '@web/themes/v1';
    public $css = [
        'datepicker/dist/datepicker.css',
        'styles/nice-select.css',
        'styles/select2.min.css',
        'styles/boxicons.min.css',
        'styles/swiper-bundle.min.css',
        'styles/animate.min.3.2.3.css',
        'styles/snow.css',
        'styles/pretty-checkbox.min.css',
//        'styles/additionalClasses.css',
        'styles/main.css',
    ];
    public $js = [
        'scripts/jquery.nice-select.js',
        'scripts/jquery.counterup.js',
        'scripts/waypoints.min.js',
        'scripts/swiper-bundle.min.js',
        'scripts/bootstrap-notify.min.js',
        'datepicker/dist/datepicker.js',
        'scripts/particles.js',
        'scripts/rellax.min.js',
        'scripts/jquery.mask.min.js',
        'scripts/select2.min.js',
        'scripts/app.js',
        'scripts/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();

        $v = '?v=0.0.1';
        foreach ($this->css as $key => $path) {
            $file_path = Yii::getAlias('@webroot/themes/v1/'.$path);
            if (file_exists($file_path)) {
                $v = '?v=' . filemtime($file_path);
            }
            $this->css[$key] = $path.$v;
        }
        foreach ($this->js as $key => $path) {
            $file_path = Yii::getAlias('@webroot/themes/v1/'.$path);
            if (file_exists($file_path)) {
                $v = '?v=' . filemtime($file_path);
            }
            $this->js[$key] = $path.$v;
        }
    }

}
