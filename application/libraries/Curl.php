<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Curl
{
    /**
     * 超时时间
     * @var integer
     */
    private $_timeout = 10;

    /**
     * 初始化配置信息
     * 在控制器中调去的方式：$this->load->library('curl', array('timeout'=>30));
     * @param array $config 传递的参数
     */
    public function __construct($config = array())
    {
        if (isset($config['timeout']) && is_numeric($config['timeout']))
            $this->_timeout = $config['timeout'];
    }

    /**
     * 以get方式获取要请求url的内容及状态码
     * @param  string $url  要请求的url
     * @return string | false
     */
    public function get($url)
    {
        $data = array(
        	'output' => false, //请求返回的内容
        	'httpcode' => 0 //请求反馈的状态码
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->agent());
        curl_setopt($ch, CURLOPT_HTTPHEADER , $this->header());
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
        $data['output'] = curl_exec($ch);
        $data['httpcode'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $data['total_time'] = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        curl_close($ch);
        return $data;
    }

    /**
     * 以get方式获取要请求url的内容及状态码
     * @param  string $url  要请求的url
     * @return string | false
     */
    public function post($url, $post_data)
    {
        $data = array(
            'output' => false, //请求返回的内容
            'httpcode' => 0 //请求反馈的状态码
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->agent());
        curl_setopt($ch, CURLOPT_HTTPHEADER , $this->header());
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $data['output'] = curl_exec($ch);
        $data['httpcode'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $data['total_time'] = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        curl_close($ch);
        return $data;
    }

    /**
     * 设置"User-Agent: "头的字符串
     * 
     * @param null
     * @return string
     * @access protected
     */
    public function agent()
    {
        $array = array(
            'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727)',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.3 (KHTML, like Gecko) Chrome/6.0.472.63 Safari/534.3',
            'Opera/8.01 (J2ME/MIDP; Opera Mini/3.1.9024/1724; zh; U; ssr)', 
            'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; zh-cn) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B367 Safari/531.21.10',
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; Acoo Browser; GTB6; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; InfoPath.1; .NET CLR 3.5.30729; .NET CLR 3.0.30618)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Acoo Browser; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; .NET CLR 2.0.50727)',
            'Mozilla/5.0 (X11; U; Linux i686; en-GB; rv:1.8.1) Gecko/20061031 BonEcho/2.0',
            'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en; rv:1.9.0.19) Gecko/2010111021 Camino/2.0.6 (MultiLang) (like Firefox/3.0.19)',
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        );
        return $array[array_rand($array)];
    }

    /**
     * 设置伪造的头部
     * 
     * @param null
     * @return array
     * @access protected
     */
    protected function header()
    {
        //墨西哥、英国、美国、孟加拉ip段
        $ipHeaderArr = array('201.102.219', '178.32.54', '18.79.32', '202.53.163');
        $ipEnd = mt_rand(10,255);
        $ipHeader = mt_rand(0,3);
        $ip = $ipHeaderArr[$ipHeader].'.'.$ipEnd;
        $array = array(
            'CLIENT-IP:'.$ip,
            'X-FORWARDED-FOR:'.$ip,
            'Accept-Language:zh-CN,zh;q=0.8,en;q=0.6',
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Charset:UTF-8,utf-8;q=0.7,*;q=0.3'
        );
        return $array;
    }
}