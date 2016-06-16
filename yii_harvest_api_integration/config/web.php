<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'atomic-payroll',
    'aliases' => [
        'harvest' => "@vendor/gridonic/hapi/src/Harvest",
        'gii' => "@vendor/yiisoft/yii2-gii"
    ],
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'], //array of components that should be run during the application bootstrapping process
    /* 'catchAll' => [ // used for when the site ist offline
        'site/offline',
        'param1' => 'value1',
        'param2' => 'value2',
    ], */
    'components' => [ // these can be accessed using: \Yii::$app->ComponentID ie Yii::$app()->user
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ILDdjIkmp97cCXhtYjjIE2t0JTCSBwvl',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'formatter' => [
            'dateFormat' => 'yyyy.mm.dd',
            'decimalSeparator' => '.',
            'thousandSeparator' => ',',
            'currencyCode' => '$',
       ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'users'=>'user/index',
                'projects'=>'project/index',
                'departments'=>'department/index',
                'companies'=>'company/index',
                'employees'=>'employee/index',
                'payroll'=>'payroll/index',
                'earningscode'=>'payrollotherearningscode/index',
                'manage'=>'site/manage',
                'home'=>'site/index',
                'login'=>'site/login',
                'logout'=>'site/logout',
                'payroll/<id:\d+>'=>'payroll',
                'payroll/import/<type:\w+>'=>'payroll/import',
                'payroll/export/<id:\d+>'=>'payroll/export',
                'payroll/summary/<id:\d+>'=>'payroll/summary',
                'payroll/employee/<employee_id:\d+>/payroll_id/<id:\d+>'=>'payroll/employee',
                'payroll/reimport/<id:\d+>/employee/<employee_id:\d+>'=>'payroll/reimport',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'controllerMap' => [ // map a controller ID to an arbitrary controller class.
        [
//            'account'   => 'app\controllers\UserController',
        ],
    ],
    'defaultRoute' => 'site/index',
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'modules' => [],
    'name' => 'Atomic Fiction Payroll',
    'params' => $params,
    'timeZone' => 'America/New_York',
    'version' => '1.0', // version of this web application
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
