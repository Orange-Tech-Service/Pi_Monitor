<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.09.15
 * Time: 20:23
 */

namespace app\models;

use yii\db\ActiveRecord;
use yii;

class Profile extends ActiveRecord
{
    public $deleteAvatar;
    public $fileAvatar;

    public static function tableName() {
        return "{{%profile}}";
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id'=>'user_id']);
    }

    public function getAvatar() {
        return !empty($this->avatar) ? $this->getAvatarPath("@web") : Yii::getAlias("@web/static/images/avatar.png");
    }

    public function rules() {
        return [
            [['name', 'email'], 'required'],
            ['name', 'string', 'length'=>[4, 50]],
            ['email', 'string', 'length'=>[4, 100]],
            ['deleteAvatar', 'boolean'],
            ['email', 'email'],
            ['fileAvatar', 'file', 'extensions'=>['gif', 'png', 'jpg'], 'maxSize' => 1024*1024*3, 'checkExtensionByMimeType'=>false],
        ];
    }

    public function getAvatarPath($alias="@webroot") {
        return Yii::getAlias($alias."/uploads/".$this->user_id."/".$this->avatar);
    }

    public function setAvatarName($avatar, $extension) {
        $this->avatar = $this->getAvatarName($avatar, $extension);
    }

    public function getAvatarName($avatar, $extension) {
        return "avatar_".md5($this->user_id.$avatar).".".$extension;
    }

    public function unlinkAvatar() {
        $avatar = $this->getAvatarPath();
        if(file_exists($avatar) AND !is_dir($avatar)) {
            @unlink($avatar);
        }
    }

    public function attributeLabels() {
        return [
            "user_id"=>Yii::t("app", "User"),
            "email"=>Yii::t("app", "Email"),
            "name"=>Yii::t("app", "Name"),
            "avatar"=>Yii::t("app", "Profile picture"),
            "deleteAvatar"=>Yii::t("app", "Delete picture"),
            "fileAvatar"=>Yii::t("app", "Profile picture"),
        ];
    }
}