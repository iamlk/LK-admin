<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/7/29
 * Time: 10:21
 */
namespace app\vendor\aes;

use Yii;

class AES
{
    protected $cipher=MCRYPT_RIJNDAEL_128;
    protected $mode=MCRYPT_MODE_ECB;
    protected $pad_method='pkcs7';
    protected $secret_key='1qaz2wsx3edc4rfv';
    protected $iv='';


    protected function pad_or_unpad($str, $ext)
    {
        if (!is_null($this->pad_method)) {

            $func_name = __CLASS__ . '::' . $this->pad_method . '_' . $ext . 'pad';

            if (is_callable($func_name)) {

                $size = mcrypt_get_block_size($this->cipher, $this->mode);

                return call_user_func($func_name, $str, $size);
            }
        }

        return $str;
    }

    protected function pad($str)
    {
        return $this->pad_or_unpad($str, '');
    }

    protected function unpad($str)
    {
        return $this->pad_or_unpad($str, 'un');
    }

    public function encrypt($str)
    {
        $str = $this->pad($str);

        $td = mcrypt_module_open($this->cipher, '', $this->mode, '');

        if (empty($this->iv)) {

            $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

        } else {

            $iv = $this->iv;

        }

        mcrypt_generic_init($td, $this->secret_key, $iv);

        $cyper_text = mcrypt_generic($td, $str);

        $rt = base64_encode($cyper_text);

        mcrypt_generic_deinit($td);

        mcrypt_module_close($td);

        return $rt;
    }

    public function decrypt($str)
    {
        $td = mcrypt_module_open($this->cipher, '', $this->mode, '');

        if (empty($this->iv)) {

            $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

        } else {

            $iv = $this->iv;

        }

        mcrypt_generic_init($td, $this->secret_key, $iv);

        $decrypted_text = mdecrypt_generic($td, base64_decode($str));

        $rt = $decrypted_text;

        mcrypt_generic_deinit($td);

        mcrypt_module_close($td);

        return $this->unpad($rt);
    }

    public static function pkcs7_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);

        return $text . str_repeat(chr($pad), $pad);
    }

    public static function pkcs7_unpad($text)
    {
        $pad = ord($text[strlen($text) - 1]);

        if ($pad > strlen($text)) return false;

        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;

        return substr($text, 0, -1 * $pad);
    }
}

