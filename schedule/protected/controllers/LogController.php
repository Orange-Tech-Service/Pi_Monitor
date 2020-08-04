<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2018.06.09
 * Time: 15:19
 */

namespace app\controllers;


use app\components\AppController;
use app\models\Log;
use yii\helpers\Inflector;
use yii\web\HttpException;
use Yii;

class LogController extends AppController
{
    public function actionClear() {
        $period = \Yii::$app->request->get("period");
        $method = "clear".Inflector::id2camel($period);
        if(!method_exists($this, $method)) {
            throw new HttpException(404, Yii::t("app", "The page you are looking for doesn't exists"));
        }
        $condition = $this->$method();

        Log::deleteAll($condition);
        Yii::$app->getSession()->setFlash("success", Yii::t("app", "Logs have been cleared"));
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    protected function clearOneDay() {
        $dt = new \DateTime();
        $dt->modify("-1 day");
        return ["<", "added_at", $dt->format("Y-m-d H:i:s")];
    }

    protected function clearOneWeek() {
        $dt = new \DateTime();
        $dt->modify("-1 week");
        return ["<", "added_at", $dt->format("Y-m-d H:i:s")];
    }

    protected function clearThreeMonths() {
        $dt = new \DateTime();
        $dt->modify("-3 months");
        return ["<", "added_at", $dt->format("Y-m-d H:i:s")];
    }

    protected function clearSixMonths() {
        $dt = new \DateTime();
        $dt->modify("-6 months");
        return ["<", "added_at", $dt->format("Y-m-d H:i:s")];
    }

    protected function clearOneMonth() {
        $dt = new \DateTime();
        $dt->modify("-1 month");
        return ["<", "added_at", $dt->format("Y-m-d H:i:s")];
    }

    protected function clearOneYear() {
        $dt = new \DateTime();
        $dt->modify("-1 year");
        return ["<", "added_at", $dt->format("Y-m-d H:i:s")];
    }

    protected function clearAll() {
        return [];
    }
}