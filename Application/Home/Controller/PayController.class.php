<?php

namespace Home\Controller;
use Think\Controller;
use Org\Payment\Alipay;
/**
 * 支付类
 */
class PayController extends Controller {

    /**
    * 支付宝
    */
    public function alipay($id=0){
        header("Content-type:text/html;charset=utf-8");
        //$Order = D("Order")->where(array("id"=>$id,'user_id'=>(int)session('id')))->find();
        //if(empty($Order)){
        //    return false;
        //}
        $alipay = new Alipay\Alipay();
        $alipay_config = C('alipay');
        $data = array(
            //服务器异步通知页面路径
            'notify_url'=>$alipay_config['notify_url'],
            'return_url'=>$alipay_config['return_url'],
            'out_trade_no'=>'21051238712641',//$Order['no'],
            'name'=>$alipay_config['order_name'],
            'total'=>0.01,//$Order['amount'],//元
            'content'=>$alipay_config['order_content'],
            'show_url'=>$alipay_config['show_url'],
        );
        //$this->success($alipay->pay($alipay_config,$data),"",true);
        echo $alipay->pay($alipay_config,$data);
    }

}
