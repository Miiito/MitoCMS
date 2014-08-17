<?php

require( __DIR__ . '/loader.php' );

$common_cfg = require( __DIR__ . '/common.php');

$env_specific = load_config('web.php');
$env_specific_local = load_config('web.local.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'assetManager' => [
            'bundles' => [
                // override default jquery asset to use cdn with fallback
                'yii\web\JqueryAsset' => [
                    'class' => 'app\assets\JqueryAsset',
                    'fallback' => 'app\assets\JqueryAssetLocal',
                    'check'=>'window.jQuery',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
];

return yii\helpers\ArrayHelper::merge($common_cfg, $config, $env_specific, $env_specific_local);
