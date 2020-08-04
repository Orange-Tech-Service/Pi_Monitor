<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2016.01.18
 * Time: 09:43
 */

namespace app\components;


use yii\web\UrlRule;

class LanguageUrlRule extends UrlRule
{
    public function createUrl($manager, $route, $params) {
        if(!isset($params['language'])) {
            $params['language'] = \Yii::$app->language;
        }
        return parent::createUrl($manager, $route, $params);
    }
}