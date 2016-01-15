<?php

namespace Org\Sms\Driver;

/* *
 * 类名：ChuanglanSmsApi
 * 功能：创蓝接口请求类
 * 详细：构造创蓝短信接口请求，获取远程HTTP数据
 * 版本：1.3
 * 日期：2014-07-16
 * 说明：
 * 以下代码只是为了方便客户测试而提供的样例代码，客户可以根据自己网站的需要，按照技术文档自行编写,并非一定要使用该代码。
 * 该代码仅供学习和研究创蓝接口使用，只是提供一个参考。
 */

class Chuanglan {

    private $status_code = array(
        '0' => '提交成功',
        '101' => '无此用户',
        '102' => '密码错',
        '103' => '提交过快（提交速度超过流速限制',
        '104' => '系统忙（因平台侧原因，暂时无法处理提交的短信）',
        '105' => '敏感短信（短信内容包含敏感词）',
        '106' => '消息长度错（>536或<=0）',
        '107' => '包含错误的手机号码',
        '108' => '手机号码个数错（群发>50000或<=0;单发>200或<=0）',
        '109' => '无发送额度（该用户可用短信数已使用完）',
        '110' => '不在发送时间内',
        '111' => '超出该账户当月发送额度限制',
        '112' => '无此产品，用户没有订购该产品',
        '113' => 'extno格式错（非数字或者长度不对）',
        '115' => '自动审核驳回',
        '116' => '签名不合法，未带签名（用户必须带签名的前提下）',
        '117' => 'IP地址认证错,请求调用的IP地址不是系统登记的IP地址',
        '118' => '用户没有相应的发送权限',
        '119' => '用户已过期',
        '120' => '短信内容不在白名单中'
    );

    /**
     * 发送短信
     *
     * @param string $mobile 手机号码
     * @param string $msg 短信内容
     * @param string $needstatus 是否需要状态报告
     * @param string $product 产品id，可选
     * @param string $extno   扩展码，可选
     */
    public function send($mobile, $msg = '', $needstatus = false, $product = '', $extno = '') {
        $code = rand(100000, 999999);
        $message = str_replace('[code]', $code, $this->message);

        //创蓝接口参数
        $postArr = array(
            'account' => $this->account,
            'pswd' => $this->password,
            'msg' => $message,
            'mobile' => $mobile,
            'needstatus' => true,
            'product' => $product,
            'extno' => $extno
        );

        $result = $this->curl($this->send_url, $postArr);
        $result_data = explode(',', $result);
        return array(
            'mobile' => $mobile,
            'code' => $code,
            'message' => $message,
            'status' => $result_data[1]
        );
    }

    /**
     * 查询额度
     *
     *  查询地址
     */
    public function query() {
        return $this->curl($this->balance_query_url, array('account' => $this->account, 'pswd' => $this->password));
    }

    /**
     * 处理返回值
     * 
     */
    public function exec($result) {
        return preg_split("/[,\r\n]/", $result);
    }

    /**
     * 通过CURL发送HTTP请求
     * @param string $url  //请求URL
     * @param array $postFields //请求参数 
     * @return mixed
     */
    private function curl($url, $postFields) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    //魔术获取
    public function __get($name) {
        return C("sms.{$name}");
    }

    //魔术设置
    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function getError($status) {
        if (isset($this->status_code[$status]))
            return $this->status_code[$status];

        return '短信供应商未知错误';
    }

}

?>