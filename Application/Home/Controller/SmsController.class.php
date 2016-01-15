<?php

namespace Home\Controller;
use Think\Controller;
/**
 * 短信类
 */
class SmsController extends Controller {

    /**
     * 发送验证码
     * @param string $mobile
     * @return json
     */
    public function index($mobile) {
//        if (D("User")->where(array("account" => $mobile))->find())
//            $this->error('该帐号已存在！', '', true);
        D('Sms')->send($mobile) ? $this->success('发送成功', '', true) : $this->error(D('Sms')->getError(), '', true);
    }

}
