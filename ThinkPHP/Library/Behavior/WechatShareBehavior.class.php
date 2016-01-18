<?php

namespace Behavior;

/**
 * 行为扩展：微信分享
 */
class WechatShareBehavior {

    /**
     * 
     * @param type $params 包括分享的标题、描述、跳转链接、图片链接
     * $params = array{
     *     'title'=>'分享标题',
     *     'desc'=>'分享描述',
     *     'link'=>'http://www.baidu.com',
     *     'img_url'=>'http://www.xxx.com/images/share.jpg'     
     * }
     */
    public function run($params) {
        $timestamp = time();
        $wxnonceStr = "wechatShare";
        $jsapiTicket = $this->getTicket();
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $signature = sha1("jsapi_ticket=$jsapiTicket&noncestr=$wxnonceStr&timestamp=$timestamp&url=$url");
        
        echo "<script type='text/javascript' src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
                <script type='text/javascript'>
                    // 微信配置
                    wx.config({
                        debug: false,
                        appId: '".C('WECHAT.APP_ID')."', 
                        timestamp: '".$timestamp."', 
                        nonceStr: '".$wxnonceStr."', 
                        signature: '".$signature."',
                        jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage'] // 功能列表，我们要使用JS-SDK的什么功能
                    });
                    // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在 页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready 函数中。
                    wx.ready(function(){
                        // 获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
                        wx.onMenuShareTimeline({
                            title: '".$params['title']."', // 分享标题
                            link:'".$params['link']."',
                            imgUrl: '".$params['img_url']."', // 分享图标
                            success: function () { 
                            // 用户确认分享后执行的回调函数，根据需求自己修改
                                //afterShare();
                            },
                        });
                        // 获取“分享给朋友”按钮点击状态及自定义分享内容接口
                        wx.onMenuShareAppMessage({
                            title: '".$params['title']."', // 分享标题
                            desc: '".$params['desc']."', // 分享描述
                            link:'".$params['link']."',
                            imgUrl: '".$params['img_url']."', // 分享图标
                            type: 'link', // 分享类型,music、video或link，不填默认为link
                            success: function () { 
                            // 用户确认分享后执行的回调函数，根据需求自己修改
                                //afterShare();
                            },
                        });
                    });
                </script>";
    }
    
    /**
     * 该方法需要从配置文件中获取appid、secret，根据配置文件自行修改
     * @return type
     */
    private function getToken(){
        !C('WECHAT.APP_ID') && E('config WECHAT.APP_ID must set');
        !C('WECHAT.APP_KEY') && E('config WECHAT.APP_KEY must set');
        $token = S('access_token');
        if (!$token) {
            $url ='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.C('WECHAT.APP_ID').'&secret='.C('WECHAT.APP_KEY');
            $res = file_get_contents($url);
            $res = json_decode($res,true);         
            $token = $res['access_token'];
            // 注意：这里需要将获取到的token缓存起来（或写到数据库中）
            // 不能频繁的访问https://api.weixin.qq.com/cgi-bin/token，每日有次数限制
            // 通过此接口返回的token的有效期目前为2小时。令牌失效后，JS-SDK也就不能用了。
            // 因此，这里将token值缓存1小时，比2小时小。缓存失效后，再从接口获取新的token，这样
            // 就可以避免token失效。
            // S()是ThinkPhp的缓存函数，如果使用的是不ThinkPhp框架，可以使用你的缓存函数，或使用数据库来保存。
            S('access_token', $token, 3600);
        }
        return $token;
    }
    
    private function getTicket(){
        $ticket = S('wx_ticket');
        if (!empty($ticket)) {
            return $ticket;
        }
        $token = S('access_token');
        empty($token) && $token = $this->getToken();
        if (empty($token)) {
            return $ticket;
        }
        $url2 = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi",$token);
        $res = file_get_contents($url2);
        $res = json_decode($res, true);
        $ticket = $res['ticket'];
        // 注意：这里需要将获取到的ticket缓存起来（或写到数据库中）
        // ticket和token一样，不能频繁的访问接口来获取，在每次获取后，我们把它保存起来。
        S('wx_ticket', $ticket, 3600);
        return $ticket;
    }

}