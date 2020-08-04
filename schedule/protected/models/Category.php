<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.09.19
 * Time: 21:30
 */

namespace app\models;


use app\models\query\OwnerTrait;
use yii\db\ActiveRecord;
use app\models\query\CategoryQuery;
use Yii;

class Category extends ActiveRecord
{
    use OwnerTrait;

    const SCENARIO_OWNER = 'owner';

    public static function tableName() {
        return "{{%category}}";
    }

    public static function find() {
        return new CategoryQuery(get_called_class());
    }

    public function rules() {
        return [
            [['user_id', 'title'], 'required'],
            ['title', 'string', 'length'=>['min'=>2, 'max'=>60]],
            ['user_id', 'exist', 'targetClass'=>User::className(), 'targetAttribute'=>'id'],
            ['user_id', 'compare', 'compareValue'=>\Yii::$app->user->getId(), 'on'=>self::SCENARIO_OWNER],
        ];
    }

    public function beforeSave($insert) {
        if(!parent::beforeSave($insert)) {
            return false;
        }
        $date = date("Y-m-d H:i:s");
        if($this->isNewRecord) {
            $this->created_at = $date;
            $this->modified_at = $date;
        } else {
            $this->modified_at = $date;
        }
        return true;
    }

    public function attributeLabels() {
        return [
            "id"=>Yii::t("app", "ID"),
            "title"=>Yii::t("app", "Title"),
            "user_id"=>Yii::t("app", "User"),
            "created_at"=>Yii::t("app", "Created at"),
            "modified_at"=>Yii::t("app", "Modified at"),
        ];
    }
}