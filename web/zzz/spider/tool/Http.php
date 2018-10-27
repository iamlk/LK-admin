<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/9/1
 * Time: 17:12
 */

namespace  spider\tool;

class Http{

    public static function curl($url, $paramStr=[],$flag='get',$fromurl='https://www.baidu.com/'){
        $file = dirname(__FILE__).'/../../cookie.txt';
        $cookie = file_get_contents($file);
        $user_agent = "Mozilla/5.7 (Windows NT 6.1; WOW64; rv:33.0) Gecko/20100101 Firefox/33.0";
        $curl = curl_init();
        if($flag=='post'){//post传递
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($paramStr));
        }
        curl_setopt($curl, CURLOPT_URL, $url);//地址

        //curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));  //构造IP

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_REFERER, $fromurl);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);#10s超时时间

        curl_setopt ($curl, CURLOPT_USERAGENT, $user_agent);
        curl_setopt ($curl, CURLOPT_COOKIE, $cookie);
        //curl_setopt ($curl, CURLOPT_COOKIEFILE, $cookie);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($curl);
        curl_close($curl);
        return $str;
    }
}