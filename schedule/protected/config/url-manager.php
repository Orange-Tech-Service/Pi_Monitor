<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.12.26
 * Time: 12:01
 */

return [
    'enablePrettyUrl' => true,
    'showScriptName' => true,
    'ruleConfig' => [
        'class' => 'app\components\LanguageUrlRule',
    ],
    'rules'=>[
        '<language:[\w\-]+>/<controller:[\w\d\-]+>/<action:[\w\d\-]+>/<id:\d+>'=>'<controller>/<action>',
        '<language:[\w\-]+>/<controller:[\w\d\-]+>/<action:[\w\d\-]+>'=>'<controller>/<action>',
        '<language:[\w\-]+>/<controller:[\w\d\-]+>'=>'<controller>',
        '<language:[\w\-]+>'=>'site/index',
    ],
];