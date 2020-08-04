<?php

/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.09.20
 * Time: 14:15
 */
namespace app\models\query;

use yii\db\ActiveQuery;

class CategoryQuery extends ActiveQuery
{
    use OwnerTrait;
}