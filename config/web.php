<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$config = [
    'id' => 'basic',
    'name'=>'shop',
    'language'=>'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this
 'cookieValidationKey' => 'Vasiliy',
 'parsers' => [
    'application/json' => 'yii\web\JsonParser',
     'multipart/form-data' => 'yii\web\MultipartFormDataParser'

 ]
 ],
 'cache' => [
    'class' => 'yii\caching\FileCache',
],
 'user' => [
     'identityClass' => 'app\models\User',
     'enableSession' => false
 ],
 'errorHandler' => [
    'errorAction' => 'site/error',
],
 'mailer' => [
    'class' => 'yii\swiftmailer\Mailer',

 // send all mails to a file by default. You have to set
 // 'useFileTransport' to false and configure transport
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
 'db' => $db,

 'urlManager' => [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
        'POST register' => 'user/create',
        'POST auth' => 'user/login',
        'GET profile' => 'user/profile',
        'GET services' => 'service/list',
        'POST order' => 'order/order',
        'DELETE orders/<<order_number>>' => 'order/delete',
        'PUT profile' => 'user/change-profile',
        'POST service' => 'service/create',
        'PUT services/<<id_service>>' => 'service/change',
        'DELETE services/<<id_service>>' => 'service/delete',
        'GET profiles/<<id>>' => 'user/view-profile-by-admin'










    ],
],

'response' =>  [
    'class' => 'yii\web\Response',
    'on beforeSend' => function ($event) {
        $response = $event->sender;
        if ($response->data !== null && $response->statusCode==401) {
            if(array_key_exists('name',$response->data))
            if ($response->data['name']=='Unauthorized'){
                $response->data = ['error'=>['code'=>401, 'message'=>'Unauthorized']];
                header('Access-Control-Allow-Origin: *');
                header('Content-Type: application/json');
            }

        }
    },
],


    ],
 'params' => $params,
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',

        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',

        'allowedIPs' => ['127.0.0.1', '::1','*' ],
    ];
}
return $config;