<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=webcron',
    'username' => 'ot_admin',
    'password' => '0rang3T3ch4758!',
    'charset' => 'utf8',
    'tablePrefix'=>'webcron_',
    'enableSchemaCache'=>true,
    'schemaCacheDuration'=>60*60*24*30,
];
