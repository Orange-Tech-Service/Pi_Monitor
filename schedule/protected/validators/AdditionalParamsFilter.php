<?php

/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.11.10
 * Time: 22:42
 */
namespace app\validators;

use yii\validators\Validator;
use yii\base\InvalidConfigException;

class AdditionalParamsFilter extends Validator
{
    public $dbattr = null;

    public function init() {
        parent::init();
        if(empty($this->dbattr)) {
            throw new InvalidConfigException('The "dbattr" property must be set.');
        }
    }
    public function validateAttribute($model, $attribute) {
        $additional = $model->$attribute;
        $key = isset($additional['key']) ? $additional['key'] : null;
        $value = isset($additional['value']) ? $additional['value'] : null;
        $additionalData = [];
        if(is_array($key) AND is_array($value)) {
            foreach($key as $id=>$k) {
                if(!empty($k)) {
                    $additionalData[$k] = $value[$id];
                }
            }
        }

        $prop = $this->dbattr;
        $model->$prop = json_encode($additionalData);
    }
}