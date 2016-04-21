<?php
class acl{
    private $CI;
    
    public function __construct()
    {
        $this->CI = &get_instance();
    }
    
    public function index()
    {
        if ($this->CI->uri->segment(1) != 'admin' && $this->CI->uri->segment(2) != 'signin' && $this->CI->uri->segment(1) != 'api' && $this->CI->uri->segment(2) != 'check_status') {
            $this->CI->load->helper('url');
            if (!$this->CI->input->cookie('uids')) {
                redirect('/admin/signin', 'location');
            }
        }
    }
}
