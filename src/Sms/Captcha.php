<?php


namespace Tool\Luosimao\Sms;


use Tool\Luosimao\Client;

class Captcha extends Client
{
    /**
     * @var string 验证码池
     */
    private $character = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected $captcha_config = [
        'cachePrefix' => 'cache:',
        'expire' => 180,
        'length' => 6,
        'type' => 1
    ];
    /**
     * 验证码
     */
    protected $code;

    /**
     * @param array|null $config
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
        $captcha_config = $this->config['captcha'];

        foreach ($captcha_config as $key => $val) {
            if (isset($this->captcha_config[$key])) {
                $this->captcha_config[$key] = $val;
            }
        }

    }

    /**
     * 生成验证码
     * @return string
     * @throws \Exception
     */
    public function create()
    {
        switch ($this->config['type']) {
            case 1://纯数字型验证码
                $range = [0, 9];
                break;
            case 2://纯小写字母型验证码
                $range = [10, 35];
                break;
            case 3://纯大写字母型验证码
                $range = [36, 61];
                break;
            case 4://数字与小写字母混合型验证码
                $range = [0, 35];
                break;
            case 5://数字与大写字母混合型验证码
                $this->character = strtoupper($this->character);
                $range = [0, 35];
                break;
            case 6://小写字母与大写字母混合型验证码
                $range = [10, 61];
                break;
            case 7://数字、小写字母和大写字母混合型验证码
                $range = [0, 61];
                break;
            default://报错：不支持的验证码类型
                throw new \Exception('不支持的验证码类型');
        }
        //拼接验证码
        for ($i = 0; $i < $this->captcha_config['length']; $i++) {
            $this->code .= $this->captcha_config['character'][random_int($range[0], $range[1])];
        }

        return $this->code;
    }

    /**
     * 验证码验证
     * @return bool
     */
    public function check()
    {
        //获取缓存验证码
        $cacheCode = $this->cache($this->captcha_config['cachePrefix'] . $this->captcha_config['scene'] . $this->captcha_config['mobile_number']);
        if ($cacheCode) {
            if ($this->captcha_config['code'] == $cacheCode) {
                return true;
            }
            throw new \Exception('验证码不正确');
        } else {
            throw new \Exception('验证码无效在或已过期');
        }
    }
}