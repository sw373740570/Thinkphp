<?php
return array(
	//'配置项'=>'配置值'
    
     //URL模式
    'url_model' => 2,
    //默认模块
    'default_module' => 'Home',
//    'SHOW_PAGE_TRACE' =>true,
    //数据库配置信息
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'thinkphp', //数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => '', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀 
    'DB_CHARSET' => 'utf8', // 字符集
    
    'sms' => include 'sms.php',
    'WECHAT' => include 'wechat.php',
);