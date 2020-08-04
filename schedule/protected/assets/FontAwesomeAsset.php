<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.09.12
 * Time: 19:14
 */

namespace app\assets;


use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/components-font-awesome';
    public $css = [
        'css/font-awesome.min.css'
    ];
    public function init() {
        $this->publishOptions['beforeCopy'] = function($from, $to) {
            if(!preg_match("#\.[\w\d]+$#iu", $from)) {
                $dirname = pathinfo($from, PATHINFO_BASENAME);
            } else {
                $dirname = basename(dirname($from));
            }
            return $dirname === 'fonts' || $dirname === 'css';
        };
    }
}