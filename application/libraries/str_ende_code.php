<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class str_ende_code {
    //字符串转化为16进制
    function strToHex($string)
    {
        $hex = "";
        for ($i=0; $i<strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        $hex = strtoupper($hex);
        return $hex;
    }

    //16进制转化为字符串
    function hexToStr($hex)   
    {
        $string = "";
        for ($i=0; $i<strlen($hex)-1; $i+=2) {
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
}