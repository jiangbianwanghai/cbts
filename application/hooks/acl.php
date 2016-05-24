<?php
class acl{
    private $CI;
    
    public function __construct()
    {
        $this->CI = &get_instance();
    }
    
    public function index()
    {
        if ($_SERVER['HTTP_HOST'] == '192.168.8.91:8888') {
            echo '<script>alert("由于IP地址随时可能调整，请使用http://cbts.gongchang.net访问本系统");</script>';
        }
        
        if ($this->CI->uri->segment(1) != 'admin' && $this->CI->uri->segment(2) != 'signin' && $this->CI->uri->segment(1) != 'api' && $this->CI->uri->segment(2) != 'check_status') {
            $this->CI->load->helper('url');
            if (!$this->CI->input->cookie('uids')) {
                redirect('/admin/signin', 'location');
            }
        }
    }
}
