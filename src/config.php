<?php
// +----------------------------------------------------------------------
// | Luosimao 配置信息
// +----------------------------------------------------------------------

return [
    //Luosimao 的key后台获取
    'api_key'       => '',
    //返回类型支持json｜xml
    'format'        => 'json',
    //send:发送单条短信 （验证码、触发类）send_batch:批量发送(通知，提醒类) status:账户信息(余额查询)
    'send_type'     => 'send',
    //Http协议 http｜https
    'scheme'        => 'http',
    //接口地址
    'api_domain'    => 'sms-api.luosimao.com/v1/',
    //请求方式
    'method'        => 'post',
    //手机号
    'mobile_number' => '',
    //批量手机号
    'mobile_numbers' => [],
    //签名
    'sign' => '【铁壳网络】',
    //模版信息
    'template_info' => [
        'register' => [
            'template' => '验证码：*code*，用于手机注册，10分钟内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。',
            'replace' => [
                '*code*' => 123456
            ]
        ],
        'login' => [
            'template' => '验证码：*code*，用于手机登录，10分钟内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。',
            'replace' => [
                '*code*' => 123456
            ]
        ],
        'new_password' => [
            'template' => '验证码：*code*，用于修改密码，10分钟内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。',
            'replace' => [
                '*code*' => 123456
            ]
        ],
    ],
    //缓存前缀
    'cachePrefix' => 'VerificationCode',
    // 验证码字符池
    'character' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
    // 验证码过期时间（s），默认 3 分钟
    'expire' => 180,
    //短信重复发送时间
    'frequest_time' => 60,
    // 验证码位数
    'length' => 6,
    // 验证码类型
    'type' => 1,
    //缓存方法配置
    'cache' => function(string $name = null, $value = '', $options = null, $tag = null){
        if (function_exists('cache')){
            return cache($name,$value,$options,$tag);
        }
    }
];