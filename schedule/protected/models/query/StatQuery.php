<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.12.26
 * Time: 20:23
 */

namespace app\models\query;


use yii\db\ActiveQuery;

class StatQuery extends ActiveQuery
{
    use OwnerTrait;
}