<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.12.02
 * Time: 23:07
 */

namespace app\components;
use Yii;

class Helper
{
    public static function covertToUserDate($time, $format="Y-m-d H:i:s", $userTz = null) {
        if(!$userTz) {
            $userTz = Yii::$app->getUser()->getIdentity()->settings->timezone;
        }
        $serverTz = date_default_timezone_get();
        $date = new \DateTime($time, new \DateTimeZone($serverTz));
        $date->setTimezone(new \DateTimeZone($userTz));
        return $date->format($format);
    }

    public static function convertToServer(\DateTime $date) {
        $serverTz = date_default_timezone_get();
        return $date->setTimezone(new \DateTimeZone($serverTz));
    }

    public static function isRegularExpression($string) {
        return @preg_match($string, '') !== FALSE;
    }
}