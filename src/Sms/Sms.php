<?php

namespace Tool\Luosimao\Sms;

use Tool\Luosimao\Client;

class Sms extends Client
{
    protected $sms_config = [
        'api_key' => '',
        'format' => 'json',
        'send_type' => 'send',
        'scheme' => 'http',
        'api_domain' => 'sms-api.luosimao.com/v1/',
        'method' => 'post',
        'sign' => '【铁壳网络】',
        'replace' => [
            '*code*' => 123456,
            '*address*' => "北京市**********",
            '*mobile*' => 15011332767,
            '*name*' => 'My Name'
        ],
        'template' => [
            'register' => '验证码：*code*，用于手机注册，10分钟内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。',
            'login' => '验证码：*code*，用于手机登录，10分钟内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。',
            'new_password' => '验证码：*code*，用于修改密码，10分钟内有效。验证码提供给他人可能导致帐号被盗，请勿泄露，谨防被骗。',
        ],
    ];
    /**
     * @var array|string 最后一次异常
     */
    public $LastError;
    /**
     * @var string|int 手机号
     */
    protected $mobileNumber;
    /**
     * @var string 手机号发送的短信信息
     */
    protected $message;
    /**
     * @var array 批量手机号
     */
    protected $mobileNumbers;
    /**
     * @var string 批量手机号发送的短信信息
     */
    protected $messages = null;
    /**
     * 短信接口地址
     */
    protected $Url = '';

    /**
     * @param array|null $config
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
        $sms_config = $this->config['sms'];

        foreach ($sms_config as $key => $val) {
            if (isset($this->sms_config[$key])) {
                $this->sms_config[$key] = $val;
            }
        }
        if (!isset($this->sms_config['api_key'])) {
            throw new \Exception('api_key不能为空');
        }

        $this->Url = "{$this->sms_config['scheme']}://{$this->sms_config['api_domain']}{$this->sms_config['send_type']}.{$this->sms_config['format']}";

    }

    /**
     * 手机号设置
     * @param string|int $mobileNumber
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
        return $this;
    }
    /**
     * 短信内容设置
     * @param array $replace
     */
    public function setMessage($replace)
    {
        $template = $this->sms_config['template'][$this->common_config['scene']];
        foreach ($replace as $k => $v) {
            $template = str_replace($k, $v, $template);
        }
        $this->message = $template.$this->sms_config['sign'];
        return $this;
    }

    /**
     * 批量手机号设置
     * @param array $mobileNumbers
     */
    public function setMobileNumbers($mobileNumbers)
    {
        $this->mobileNumbers = $mobileNumbers;
        return $this;
    }
    /**
     * 短信内容设置
     * @param array $replaces
     */
    public function setMessages(array $replaces)
    {
        $template = $this->sms_config['template'][$this->common_config['scene']];
        foreach ($replaces as $replace) {
            foreach ($replace as $k => $v) {
                $template = str_replace($k, $v, $template);
            }
            $this->messages[] = $template . $this->sms_config['sign'];
        }

        return $this;
    }
    //触发，单发，适用于验证码，订单触发提醒类
    public function Send()
    {
        if (!$this->isTell($this->mobileNumber)) {
            throw new \Exception('手机号异常');
        }
        $param = array(
            'mobile' => $this->mobileNumber,
            'message' => $this->message,
        );
        $res = $this->Request($this->Url, $param);
        return @json_decode($res, true);
    }

    //批量发送，用于大批量发送
    public function SendBatch($mobile_list = array(), $message = array(), $time = '')
    {
        $mobile_list = is_array($mobile_list) ? implode(',', $mobile_list) : $mobile_list;
        $param = array(
            'mobile_list' => $mobile_list,
            'message' => $message,
            'time' => $time,
        );
        $res = $this->Request($this->Url, $param);
        return @json_decode($res, true);
    }

    //获取短信账号余额
    public function GetDeposit()
    {
        $this->config['method'] = 'get';
        $res = $this->Request($this->Url);
        return @json_decode($res, true);
    }

    /**
     * @param string $type 接收类型，用于在服务器端接收上行和发送状态，接收地址需要在luosimao后台设置
     * @param array $param 传入的参数，从推送的url中获取，官方文档：https://luosimao.com/docs/api/
     */
    public function Recv($type = 'status', $param = array())
    {
        if ($type == 'status') {
            if ($param['batch_id'] && $param['mobile'] && $param['status']) { //状态
                // do record
            }
        } else if ($type == 'incoming') { //上行回复
            if ($param['mobile'] && $param['message']) {
                // do record
            }
        }
    }

    /**
     * @param string $api_url 接口地址
     * @param array $param post参数
     * @param int $timeout 超时时间
     * @return bool
     */
    private function Request($api_url = '', $param = array(), $timeout = 5)
    {

        if (!$api_url) {
            throw new \Exception('url不能为空');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if (parse_url($api_url)['scheme'] == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-' . $this->sms_config['api_key']);
        if ($this->sms_config['method'] == 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        }

        $res = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($error) {
            $this->LastError[] = $error;
            return false;
        }
        return $res;
    }

}