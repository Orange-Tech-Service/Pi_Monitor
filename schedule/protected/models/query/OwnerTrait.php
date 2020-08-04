<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.10.25
 * Time: 14:14
 */

namespace app\models\query;
use yii\db\ActiveQuery;


/**
 * Class OwnerTrait
 * @package app\models\query
 */
trait OwnerTrait
{
    /**
     * @param $userId int
     * @param $t string
     * @return ScheduleQuery
     */
    public function owner($userId = null, $t = null) {
        if(!$userId) {
            $userId = \Yii::$app->getUser()->getId();
        }
        return parent::andWhere([
            "{$t}user_id"=>$userId
        ]);
    }
}