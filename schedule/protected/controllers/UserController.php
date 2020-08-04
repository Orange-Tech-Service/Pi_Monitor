<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.09.13
 * Time: 20:24
 */
namespace app\controllers;

use app\models\ChangePasswordForm;
use app\models\LoginForm;
use app\models\Profile;
use app\models\Settings;
use app\models\User;
use app\components\AppController;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii;

class UserController extends AppController
{
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class'=>AccessControl::className(),
            'rules' => [
                [
                    'allow'=>true,
                    'actions'=>['login'],
                    'roles'=>['?'],
                ],
                [
                    'allow'=>true,
                    'roles'=>['@'],
                ]
            ],
        ];
        return $behaviors;
    }

    public function actionLogin() {
        if(!Yii::$app->user->isGuest) {
            $this->goHome();
        }
        $this->getView()->title = Yii::t("app", "Login Form");

        $badLoginCnt = 3;
        $showCaptcha = false;
        $sessionCnt = Yii::$app->session->get("badLoginCount", 0);
        $form = new LoginForm();

        if($sessionCnt >= $badLoginCnt) {
            $form->setScenario(LoginForm::SCENARIO_BADLOGINCOUNT);
            $showCaptcha = true;
        }
        if($sessionCnt == $badLoginCnt-1) {
            $showCaptcha = true;
        }

        if($form->load(Yii::$app->request->post())) {
            if($form->login()) {
                return $this->goBack();
            } else {
                Yii::$app->session->set('badLoginCount', $sessionCnt + 1);
            }
        }

        return $this->render("login", [
            "form"=>$form,
            "showCaptcha"=>$showCaptcha,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return Yii::$app->getResponse()->redirect(Url::toRoute("user/login"));
    }

    public function actionEdit() {
        /**
         * @var $user User;
         */
        $user = Yii::$app->user->identity;

        $changePasswordForm = new ChangePasswordForm();

        $this->getView()->title = Yii::t("app", "My Account");

        if($changePasswordForm->load(Yii::$app->request->post()) AND $changePasswordForm->validate()) {
            $user->setPassword($changePasswordForm->newPassword);
            $user->save();
            foreach ($changePasswordForm->attributes as $attribute=>$value) {
                $changePasswordForm->$attribute = NULL;
            }
        }

        if($user->profile->load(Yii::$app->request->post())) {
            $user->profile->fileAvatar = UploadedFile::getInstance($user->profile, "fileAvatar");
            if($user->profile->validate()) {
                if($user->profile->fileAvatar) {
                    $user->profile->unlinkAvatar();
                    $user->profile->setAvatarName($user->profile->fileAvatar->name, $user->profile->fileAvatar->extension);
                    $avatar_path = $user->profile->getAvatarPath();
                    FileHelper::createDirectory(dirname($avatar_path));
                    $user->profile->fileAvatar->saveAs($avatar_path);
                    $width = 128;
                    $height = 128;
                    $options = [
                        "quality"=>100,
                    ];
                    Image::thumbnail($avatar_path, $width, $height)->save($avatar_path, $options);
                }
                if($user->profile->deleteAvatar) {
                    $user->profile->unlinkAvatar();
                    $user->profile->avatar = null;
                }
                $user->profile->save(false);
            }
        }

        ($user->settings->load(Yii::$app->request->post()) AND $user->settings->save());

        return $this->render("edit", [
            "changePasswordForm"=>$changePasswordForm,
            "settings"=>$user->settings,
            "profile"=>$user->profile,
        ]);
    }

    public function actionCreate() {
        $user = new User();
        $user->login = "admin";
        $user->setPassword("admin");
        $user->setAccessToken();
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $user->role = User::ROLE_USER;
        $user->lang_id = Yii::$app->language;
        $user->save();
        var_dump($user->getErrors());
        if(!$user->hasErrors()) {
            $profile = new Profile();
            $profile->name = "Administrator";
            $profile->email = "admin@example.com";
            $profile->user_id = $user->id;

            $settings = new Settings();
            $settings->timezone = Yii::$app->params['timezone'];
            $settings->user_id = $profile->user_id;

            $profile->save();
            $settings->save();

            var_dump($profile->getErrors());
            var_dump($settings->getErrors());
        }
    }
}