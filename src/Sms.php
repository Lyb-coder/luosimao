<?php

namespace Tool\Luosimao;

class Sms extends SmsClient
{

    // 手机号字段名
    private $mobileName = 'mobile';

    //触发，单发，适用于验证码，订单触发提醒类
    public function Send($mobile)
    {
        $template_info = $this->config['template_info'][$this->config['scene']];
        $template = $template_info['template'];
        $replace = $template_info['replace'];
        foreach ($replace as $k => $v){
            $template = str_replace($k,$v,$template);
        }
        $message = $template.$this->config['sign'];
        if ($this->config['cache']($this->mobileName.$this->config['scene']))
        $param = array(
            'mobile' => $mobile,
            'message' => $message,
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
            die("error api_url");
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
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-' . $this->config['api_key']);
        if ($this->config['method'] == 'post'){
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