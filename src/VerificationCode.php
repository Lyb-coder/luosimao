<?php


namespace Tool\Luosimao;

/**
 * 在TP框架中使用验证码
 */
class VerificationCode extends SmsClient
{

    // 验证码
    protected $code = '';
    // 错误信息
    protected $error = '';


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
                $this->config['character'] = strtoupper($this->config['character']);
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
        for ($i = 0; $i < $this->config['length']; $i++) {
            $this->code .= $this->config['character'][random_int($range[0], $range[1])];
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
        $cacheCode = $this->config['cache']($this->config['cachePrefix'].$this->config['scene'].$this->config['mobile_number']);
        if($cacheCode){
            if ($this->config['code'] == $cacheCode){
                return true;
            }
            $this->LastError = '验证码不正确';
            return false;
        } else {
            $this->LastError = '验证码无效在或已过期';
            return false;
        }
    }
}