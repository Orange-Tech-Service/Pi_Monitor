<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2018.06.22
 * Time: 15:51
 */

namespace app\components;


use Symfony\Component\Process\Process;

class ScheduleProcess extends Process
{

    private $exceptionMessage;

    public function setExceptionMessage($message) {
        $this->exceptionMessage = $message;
    }

    public function isSuccessful()
    {
        return (parent::isSuccessful() AND empty($this->exceptionMessage));
    }

    public function getExitCodeText()
    {
        return empty($this->exceptionMessage) ? parent::getExitCodeText() : $this->exceptionMessage;
    }
}