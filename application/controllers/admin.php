<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {

    public function index()
    {
        $data['PAGE_TITLE'] = '我的控制台';
        $this->load->helper('url');
        if (!$this->input->cookie('username')) {
            redirect('/admin/signin', 'location');
        } else {
            $this->load->view('admin_home', $data);
        }
    }

    /**
     * 登录
     */
    public function login() {

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $this->load->model('Model_users', 'users', TRUE);
        $row = $this->users->checkUser($username, $password);
        if ($row) {
            $this->input->set_cookie('username', $username, 86400);
            $this->input->set_cookie('realname', $row['realname'], 86400);
            $feedback = $this->users->updateLoginTime($row['uid']);
            $array = array(
                'status' => true,
                'message' => '登录成功',
                'url' => '/'
            );
        } else {
            $url = "http://oa.gongchang.cn/userinfo.php?username=".$username."&password=".$password."&p=7232275";
            $result = file_get_contents($url);
            if ((int)$result == 200) {
                $this->input->set_cookie('username', $username, 86400);
                $this->input->set_cookie('realname', $username, 86400);
                $feedback = $this->users->add(array('username' => $username, 'password' => md5($password)));
                $array = array(
                    'status' => true,
                    'message' => '登录成功',
                    'url' => '/'
                );
            } else {
                $array = array(
                    'status' => false,
                    'message'=> '登录失败',
                    'url' => '/admin/signin'
                );
            }
        }
        echo json_encode($array);
    }
    
    /**
     * 登录面板
     */
    public function signin()
    {
        $this->load->helper(array('form', 'url'));
        if ($this->input->cookie('username')) {
            redirect('/', 'location');
        }
        $this->load->view('admin_login');
    }
    
    public function logout()
    {
        $this->load->helper(array('cookie', 'url'));
        set_cookie('username',0,0);
        set_cookie('realname',0,0);
        redirect('/', 'location');
    }
    
    public function captcha()
    {
        $this->load->helper(array('custom_captcha'));
        $this->load->library('session');
        $vals = array(
            'word' => rand(1000, 10000),
            'img_width' => 70,
            'img_height' => 30,
            'font_path' => './font/Duality.ttf'
        );
        $cap = create_custom_captcha($vals);
        $this->session->set_flashdata('captcha_word', $cap);
    }
    
    public function getoption()
    {
        require 'cache/1/category.php';
        echo json_encode($category);
    }
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */