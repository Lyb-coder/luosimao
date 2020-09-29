<?php


namespace Tool\Luosimao;


class Client
{
    protected $config = [];
    protected $common_config = [
        'scene' => 'login',
        'cache' => null
    ];
    /**
     * @param array $config
     */
    public function __construct($config = null)
    {
        if (function_exists('config')) {
            $this->config = array_merge($this->config,config('tool_lsm_sms'));
        }
        if ($config != []){
            $this->config = array_merge($this->config,$config);
        }
        if ($this->config == array()){
            throw new \Exception('配置信息不能为空');
        }
        $this->common_config = $this->config['common'];

        foreach ($this->common_config as $key => $val) {
            if (isset($this->common_config[$key])) {
                $this->common_config[$key] = $val;
            }
        }
    }
    /**
     * 手机号验证
     */
    public function isTell($value)
    {
        $rule = '^1[3-9][0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        return $result ? true : false;
    }
    /**
     * @param string|null $name
     * @param string $value
     * @param null $options
     * @param null $tag
     * @return mixed
     * @throws \Exception
     */
    protected function cache(string $name = null, $value = '', $options = null, $tag = null){
        if ($this->config['cache'] instanceof \Closure) {
            return $this->config['cache']($name,$value,$options,$tag);
        } else {
            throw new \Exception('请配置缓存信息');
        }
    }
}