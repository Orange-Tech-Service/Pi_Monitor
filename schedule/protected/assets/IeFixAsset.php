<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.09.12
 * Time: 18:23
 */

namespace app\assets;


use yii\web\AssetBundle;
use yii\web\View;

class IeFixAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        "static/js/html5shiv.min.js",
        "static/js/respond.min.js",
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD,
        'condition' => 'lte IE9'
    ];
}