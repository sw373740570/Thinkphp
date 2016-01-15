<?php

namespace Org\Sms;

use Org\Sms\Driver\Chuanglan;

class Sms {

    private $driver;

    public function __construct() {
        $this->driver = new Chuanglan();
    }

    public function send($mobile) {
        return $this->driver->send($mobile, str_replace('[code]', rand(100000, 999999), C("sms.message")), true);
    }

    public function getError($status) {
        return $this->driver->getError($status);
    }

}
