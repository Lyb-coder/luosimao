<?php
include '../vendor/autoload.php';

$config = [
    //Luosimao 的key后台获取
    'api_key'       => 'xxxxxxxxxxxxxxxxx',
    //返回类型支持json｜xml
    'format'        => 'json',
    //send:发送单条短信 （验证码、触发类）send_batch:批量发送(通知，提醒类) status:账户信息(余额查询)
    'send_type'     => 'send',
    //签名
    'sign' => '【全球至尊黑卡】',
    //场景值
    'scene' => 'login',
    //模版信息
    'template_info' => [
        'login' => [
            'template' => '验证码：*code*，用于手机登陆，半小时内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。',
            'replace' => [
                '*code*' => 123456
            ]
        ]
    ],
    //缓存方法配置
    'cache' => function(string $name = null, $value = '', $options = null, $tag = null){
        if (function_exists('cache')){
            return cache($name,$value,$options,$tag);
        }else{
            return true;
        }
    }
];

$code = new Tool\Luosimao\VerificationCode($config);
//获取随机验证码
print_r($code->create());