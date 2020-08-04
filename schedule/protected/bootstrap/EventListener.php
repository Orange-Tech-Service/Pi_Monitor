<?php

/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.12.05
 * Time: 12:43
 */
namespace app\bootstrap;

use app\events\ChangedAttributesEvent;
use app\models\Schedule;
use app\models\Settings;
use yii\base\BootstrapInterface;
use yii\base\Event;
use app\components\Cron;
use Yii;
use yii\helpers\ArrayHelper;

class EventListener implements BootstrapInterface
{
    public function bootstrap($application) {
        Event::on(Settings::className(), Settings::EVENT_TIMEZONE_CHANGED, [$this, 'timezoneChanged']);
    }

    public function timezoneChanged(ChangedAttributesEvent $e) {
        if(!$newTz = ArrayHelper::getValue($e, "sender.timezone")) {
            return false;
        }
        $tblName = Schedule::tableName();
        $oldTz = $e->changedAttributes['timezone'];

        foreach(Schedule::find()->owner()->asArray()->each() as $cron) {
            $toUpd = [];
            try {
                $cronExp = Cron::factory($cron['expression']);
                $runDate = $cronExp->getNextRunDate(new \DateTime('now', new \DateTimeZone($newTz)));
                //$cronExp = new Cron($cron['expression'], $newTz);
                //$runDate = $cronExp->getNextRunDate();
                if($cron['stop_at_user']) {
                    $d = new \DateTime($cron['stop_at_user'], new \DateTimeZone($oldTz));
                    $d->setTimezone($newTz);
                    $toUpd['stop_at_user'] = $d->format("Y-m-d H:i:s");
                }
                $toUpd['send_at_user'] = $runDate->format("Y-m-d H:i:s");
                $runDate->setTimezone(new \DateTimeZone(Yii::$app->getTimeZone()));
                $toUpd['send_at_server'] = $runDate->format("Y-m-d H:i:s");
                Yii::$app->db->createCommand()->update($tblName, $toUpd, "id=:id", [":id"=>$cron['id']])->execute();
            } catch(\Exception $e) {
                continue;
            }
        }
        return true;
    }
}