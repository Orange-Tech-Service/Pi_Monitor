<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.11.18
 * Time: 23:26
 */

namespace app\components\switch_column;


use yii\web\AssetBundle;

class SwitchAsset extends AssetBundle
{
    public $sourcePath = '@app/components/switch_column/bundle';
    public $css = [
        'css/bootstrap-switch.css',
    ];
    public $js = [
        'js/bootstrap-switch.min.js',
        'js/switcher.js?v=0.1',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}