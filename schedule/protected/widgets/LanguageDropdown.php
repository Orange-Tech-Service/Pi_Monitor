<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2016.01.17
 * Time: 16:47
 */

namespace app\widgets;


use yii\base\Widget;
use Yii;

class LanguageDropdown extends Widget
{
    public function run() {
        $languages = (array) Yii::$app->params['languages'];
        $route = Yii::$app->controller->route;
        $error = Yii::$app->errorHandler->errorAction;

        if(count($languages) < 2 OR $route == $error) {
            return null;
        }
        return $this->render("//widgets/language", array(
            "languages"=>$languages,
        ));
    }
}