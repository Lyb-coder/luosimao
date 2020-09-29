<?php


namespace Tool\Luosimao;


class SmsClient
{
    protected $config = [
        //签名
        'sign' => '【铁壳网络】',
        //Template
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
        //Luosimao 的key后台获取
        'api_key' => '',
        //返回类型支持json｜xml
        'format' => 'json',
        //send:发送单条短信 （验证码、触发类）send_batch:批量发送(通知，提醒类) status:账户信息(余额查询)
        'send_type' => 'send',
        //Http协议 http｜https
        'scheme' => 'http',
        //接口地址
        'api_domain' => 'sms-api.luosimao.com/v1/',
        //请求方式
        'method' => 'post',
        //手机号
        'mobile_number' => '',
        //批量手机号
        'mobile_numbers' => [],
        //缓存前缀
        'cachePrefix' => 'VerificationCode',
        // 验证码字符池
        'character' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        // 验证码过期时间（s），默认 3 分钟
        'expire' => 180,
        //场景值
        'scene' => 'register',
        //短信重复发送时间
        'frequest_time' => 60,
        // 验证码位数
        'length' => 6,
        // 验证码类型
        'type' => 1,
        //缓存方法配置
        'cache' => ''
    ];
    //错误信息
    public $LastError;

    //短信接口地址
    protected $Url = '';

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (function_exists('config')) {
            $config = array_merge($config,config('tool_lsm_sms'));
        }

        foreach ($config as $key => $val) {
            if (isset($this->config[$key])) {
                $this->config[$key] = $val;
            }
        }
        if (!isset($this->config['api_key'])) {
            die("api key error.");
        }

        $this->Url = "{$this->config['scheme']}://{$this->config['api_domain']}{$this->config['send_type']}.{$this->config['format']}";

    }

    public function LastError()
    {
        return $this->LastError;
    }
}