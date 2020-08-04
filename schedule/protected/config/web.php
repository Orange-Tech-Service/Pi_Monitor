<?php

$params = require(__DIR__ . '/params.php');
$urlManager = require(__DIR__ . '/url-manager.php');
$i18n = require(__DIR__ . '/i18n-web.php');
$config = [
    'id' => 'WebCron',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'app\bootstrap\AppBootstrap', 'app\bootstrap\EventListener'],
    'language'=>'en-US',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl'=>['user/login'],
            'on afterLogin'=>function($event) {
                $identity = $event->identity;
                $db = Yii::$app->db;
                $ip = Yii::$app->request->getUserIP();
                $date = date("Y-m-d H:i:s");
                $db->createCommand()->update("{{%user}}", [
                    "last_login_ip"=>$ip,
                    "last_login_at"=>$date,
                ], "id=:id", [":id"=>$identity->id])->execute();
            },
            'identityCookie' => [
                'name'=>'_identity',
                'path'=>$params['cookiePath']
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => $urlManager,
        'i18n'=>$i18n,
        'mailer' => require(__DIR__ . '/mailer.php'),
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'except' => ['yii\web\HttpException:404', 'yii\debug\*'],
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'request' => [
            'cookieValidationKey' => $params['cookieValidationKey'],
            'csrfCookie' => [
                'httpOnly' => true,
                'path' => $params['cookiePath'],
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        'css/bootstrap.min.css',
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        'js/bootstrap.min.js',
                    ]
                ],
            ],
        ],
        'session' => [
            'cookieParams' => [
                'path' => $params['cookiePath'],
            ],
        ],
    ],
    'params' => $params,
];


if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '84.55.60.33']
    ];
}
return $config;
