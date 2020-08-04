<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.12.05
 * Time: 13:22
 */

namespace app\events;


use yii\base\Event;

class ChangedAttributesEvent extends Event
{
    public $changedAttributes;
}