<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.12.17
 * Time: 23:14
 */

namespace app\models\query;


use yii\db\ActiveQuery;

class LogQuery extends ActiveQuery
{
    use OwnerTrait;
}