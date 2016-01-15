<?php

namespace Common\Model;

use Org\Sms\Sms;
use Think\Model;

/**
 * 短信类
 */
class SmsModel extends Model {

    protected $_validate = array(
        array('mobile', '/^1[3-9][0-9]{9}$/', '手机号格式不正确！', self::VALUE_VALIDATE),
    );
    protected $_auto = array(
        array('status', '1', self::MODEL_UPDATE),
        array('ip', 'get_client_ip', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_UPDATE, 'function'),
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
    );

    /**
     * 发送验证码
     * @param string $mobile 手机号
     * @return boolean 是否成功
     */
    public function send($mobile) {
        //验证码每天手机号最大数量
        if (parent::where(array('mobile' => $mobile, 'create_time' => array('gt', time())))->count() > (int) C('sms.mobile_day_max')) {
            $this->error = '手机号 ' . $mobile . ' 已超过每天发送验证码最大数量';
            return false;
        }

        //验证码每天ip最大数量
        if (parent::where(array('ip' => get_client_ip(), 'create_time' => array('gt', time())))->count() > (int) C('sms.ip_day_max')) {
            $this->error = 'ip ' . get_client_ip() . ' 已超过每天发送验证码最大数量';
            return false;
        }

        //验证码每天ip最大数量
        if ((time() - parent::where(array('mobile' => $mobile))->order('id desc')->getField('create_time')) < (int) C('sms.send_wait_time')) {
            $this->error = '发送间隔过于频繁';
            return false;
        }

        if (!$data = $this->create((new Sms())->send($mobile))) {
            return false;
        }

        if ($data['status'] != '0') {
            $this->error = (new Sms())->getError($data['code']);
            return false;
        }

        return parent::add($data);
    }

    /**
     * 验证验证码
     * @param string $mobile 手机号
     * @param string $code 验证码
     * @return boolean 验证
     */
    public function verify($mobile, $code) {
        $this->where(array('mobile' => $mobile))->order('id desc');
        if (!$data = parent::find()) {
            $this->error = '验证码错误';
            return false;
        }

        if ($data['code'] != $code) {
            $this->error = '验证码错误';
            return false;
        }

        if ($data['status'] != '0') {
            $this->error = '验证码已过期';
            return false;
        }

        return parent::save($this->create($data));
    }

    public function find($options = array()) {
        return FALSE;
    }

    public function add($data = '', $options = array(), $replace = false) {
        return FALSE;
    }

    public function addAll($dataList, $options = array(), $replace = false) {
        return FALSE;
    }

    public function save($data = '', $options = array()) {
        return FALSE;
    }

    public function delete($options = array()) {
        return FALSE;
    }

}
