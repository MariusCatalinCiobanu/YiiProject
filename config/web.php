<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'app\classes\Constants'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@constants' => '@app/classes',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '3d7tBveGabUDttMWV9vO3xIXPhxzZb4m',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            //set default login url
            'loginUrl' => ['login\login\index'],
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'login/login/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'clarisotmarius.ciobanu@gmail.com',
                'password' => 'parola123',
//                'username' => 'marius.ciobanu@clarisoft.com',
//                'password' => 'grafuri1',
                'port' => '587',
                'encryption' => 'TLS'
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => 'C:\usr\Yii2Project\logs\logs.log'
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Hide index.php
            'showScriptName' => false,
            // Use pretty URLs
            'enablePrettyUrl' => true,
            //if strictParsing is enabled it must match at least one rule
            //from rules to get accepted, otherwise it will throw a 404 
            'enableStrictParsing' => false,
            'rules' => [
                
                //routing for modules.
                //The module name isn't required in the url. So instead of urls
                //like login/login/index the url will be login/index
                //
                'class' => 'yii\web\UrlRule',
                
                //controller, action and id are variables that have the value equal to the
                //the regex after the ':'. on the right is the correct route. 
                //Variables are found between '<>'.
                '<controller:(admin-panel|login)>/<action:\w+>/<id:\d+>' => 'login/<controller>/<action>/<id>',
                '<controller:(admin-panel|login)>/<action:\w+>' => 'login/<controller>/<action>',
                '<controller:(trip)>/<action:\w+>/<id:\d+>' => 'trip/<controller>/<action>/<id>',
                '<controller:(trip)>/<action:\w+>' => 'trip/<controller>/<action>',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['ws/user']
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'authorizationMethods' => [
            'class' => 'app\components\AuthorizationMethods',
        ],
        'authorizationConstants' => [
            'class' => 'app\components\AuthorizationConstants',
        ],
    ],
    'modules' => [
        'login' => [
            'class' => 'app\modules\login\Login'
        ],
        'trip' => [
            'class' => 'app\modules\trip\Trip'
        ],
        'ws' => [
            'class' => 'app\modules\webService\Module'
        ],
    ],
    'params' => $params,
    //set default page
    'defaultRoute' => 'login/login/index'
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
