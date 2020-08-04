<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.12.26
 * Time: 12:04
 */
return [
    'translations'=>[
        'app*'=>[
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/messages',
            'forceTranslation'=>true,
            'fileMap' => [
                'app' => 'app.php',
            ],
        ],
    ],
];
