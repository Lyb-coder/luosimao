# Luosimao 短信平台SDK封装

[Luosimao官网][1]

集成Luosimao的短信发送并适配于Thinkphp6.0框架的composer包

###安装方式

commposer
```composer
composer require lyb/luosimao
```
git
```git
git clone git@github.com:Leslie1sMe/LuosimaoSms.git
```

download
```download
https://github.com/Lyb-coder/luosimao/archive/master.zip
```

###调用方式
```php
include '../vendor/autoload.php';

$config = [
    'common' => [
        //场景
        'scene' => 'login',
        //缓存方法配置
        'cache' => null
    ],
    //短信配置
    'sms' => [
        //Luosimao 的key后台获取
        'api_key'       => 'faff3sadfklasdjfksdjflsjldfj4e731cfe',
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
        //签名
        'sign'          => '【铁壳网络】',
        //模版信息
        'template' => [
            'register' => '验证码：*code*，用于手机注册，10分钟内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。',
            'login' => '验证码：*code*，用于手机登录，10分钟内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。',
            'new_password' => '验证码：*code*，用于修改密码，10分钟内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。',
        ],
    ],
    //验证码配置
    'captcha' => [
        //缓存前缀
        'cachePrefix' => 'cache:',
        // 验证码过期时间（s），默认 3 分钟
        'expire' => 180,
        // 验证码位数
        'length' => 6,
        // 验证码类型
        'type' => 1
    ]
];

$sms = (new \Tool\Luosimao\Sms\Sms($config))
//    ->setSmsConfig('send_type','send')
//    ->setMobileNumber(15111441767)
//    ->Send();
//    ->setSmsConfig('send_type','send_batch')
//    ->setMobileNumbers(['15222441767','15333441767'])
//    ->SendBatch();
    ->setSmsConfig('send_type','status')
    ->GetDeposit();
print_r($sms);
```

![Alipay(支付包)](https://typecho-blog1.oss-cn-beijing.aliyuncs.com/pay/alipay.png)
![Wechat(微信)](https://typecho-blog1.oss-cn-beijing.aliyuncs.com/pay/wechat.png)

[1]: https://luosimao.com/