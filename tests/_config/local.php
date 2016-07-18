<?php
require('/app/vendor/dmstr/yii2-pages-module/vendor/autoload.php');

return yii\helpers\ArrayHelper::merge(
    #require('/app/src/config/main.php'),
    [],
    [
        'aliases' => [
            '@dmstr/modules/pages' => '@vendor/dmstr/yii2-pages-module',
            '@tests' => '@vendor/dmstr/yii2-pages-module/tests',
            '@vendor/insolita' => '@dmstr/modules/pages/vendor/insolita',
            '@vendor/bower/jsoneditor' => '@dmstr/modules/pages/vendor/bower/jsoneditor',
        ],
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=db;dbname=test',
                'username' => 'test',
                'password' => 'test',
                'charset' => 'utf8',
                'tablePrefix' => getenv('DATABASE_TABLE_PREFIX'),
                'enableSchemaCache' => YII_ENV_PROD ? true : false,
            ],
            'user' => [
                'class' => 'dmstr\web\User',
                #'enableAutoLogin' => true,
                #'loginUrl' => ['/user/security/login'],
                'identityClass' => 'dektrium\user\models\User',
                #'rootUsers' => ['admin'],
            ],
        ],
        'modules' => [
            'pages' => [
                'class' => 'dmstr\modules\pages\Module',
                #'layout' => '@admin-views/layouts/main',
            ],

            'treemanager' =>
            [
                'class' => 'kartik\tree\Module',
                #'layout' => '@admin-views/layouts/main',
                'treeViewSettings' => [
                    'nodeView' => '@vendor/dmstr/yii2-pages-module/views/treeview/_form',
                    'fontAwesome' => true,
                ],

            ]
        ],
        'params' => [
            'yii.migrations' => [
                '@vendor/dmstr/yii2-pages-module/migrations'
            ]
        ]
    ]
);
