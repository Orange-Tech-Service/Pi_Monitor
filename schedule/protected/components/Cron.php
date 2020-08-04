<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2015.12.05
 * Time: 15:03
 */

namespace app\components;
use Cron\CronExpression;
use Yii;


class Cron extends CronExpression {
    private $_stack = [];

    public function getMultipleNextRunDate($currentTime='now', $count) {
        return $this->getMultiple($currentTime, $count, true);
    }

    public function getMultiplePreviousRunDate($currentTime='now', $count) {
        return $this->getMultiple($currentTime, $count, false);
    }

    protected function getMultiple($currentTime, $count, $next = true) {
        $this->_stack = [];
        $d = null;
        for($i = 0; $i < $count; $i++) {
            if(!$d) {
                $d = $next ? $this->getNextRunDate($currentTime) : $this->getPreviousRunDate($currentTime);
            } else {
                $d = $next ? $this->getNextRunDate($d) : $this->getPreviousRunDate($d);
            }
            $this->_stack[] = $d;
        }
        return $this->_stack;
    }

    public static function isValidExpression($expression, \DateTime $now=null) {
        try {
            if(!$now) {
                $now = Yii::$app->getUser()->getIdentity()->getDateObject();
            }
            return parent::factory($expression)->getNextRunDate($now);
        } catch(\Exception $e) {
            return false;
        }
    }

    public function getStack() {
        return $this->_stack;
    }
}