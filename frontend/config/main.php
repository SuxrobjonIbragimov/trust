<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'language' => 'uz-UZ',
    'bootstrap' => [
        'log',
        'languagepicker',
    ],
    'controllerNamespace' => 'frontend\controllers',
    'homeUrl' => '/',
    'modules' => [
        'sitemap' => [
            'class' => 'himiklab\sitemap\Sitemap',
            'models' => [
                \backend\models\post\Posts::className(),
                \backend\models\post\PostCategories::className(),
            ],
            'urls' => [
                [
                    'loc' => '/',
                    'priority' => 1,
                ],
            ],
        ],
        'policy' => [
            'class' => 'frontend\modules\policy\Module',
        ],
        'payment' => [
            'class' => 'frontend\modules\payment\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'languagepicker' => [
            'class' => 'common\components\languagepicker\Component',
            'languages' => function () {
                return \backend\modules\translatemanager\models\Language::getLanguageNames(true);
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/v1',
                'baseUrl' => '@web/themes/v1',
                'pathMap' => [
                    '@app/views' => '@app/themes/v1',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<module:policy>/<controller:\w+>/<action:[\w\-]+>' => '<module>/<controller>/<action>',
                '<module:payment>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<slug:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                ['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@webroot/themes/v1',
                    'js' => [
                        'scripts/jquery.js',
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@webroot/themes/v1',
                    'css' => [
                        'styles/framework/bootstrap.min.css'
                    ],
                    'js' => [
                        'scripts/bootstrap.bundle.min.js'
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => '@webroot/themes/v1',
                    'css' => [
                        'styles/framework/bootstrap.min.css'
                    ],
                    'js' => [
                        'scripts/bootstrap.bundle.min.js'
                    ],
                ],
                'yii\bootstrap4\BootstrapAsset' => [
                    'sourcePath' => '@webroot/themes/v1',
                    'css' => [
                        'styles/framework/bootstrap.min.css'
                    ],
                    'js' => [
                        'scripts/bootstrap.bundle.min.js'
                    ],
                ],
                'yii\bootstrap4\BootstrapPluginAsset' => [
                    'sourcePath' => '@webroot/themes/v1',
                    'css' => [
                        'styles/framework/bootstrap.min.css'
                    ],
                    'js' => [
                        'scripts/bootstrap.bundle.min.js'
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
