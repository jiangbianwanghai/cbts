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
        $this->config->load('extension', TRUE);
        $rtx = $this->config->item('rtx', 'extension');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $this->load->model('Model_users', 'users', TRUE);
        $row = $this->users->checkUser($username, $password);
        if ($row) {
            $this->input->set_cookie('uids', $row['uid'], 86400*15);
            $this->input->set_cookie('username', $username, 86400*15);
            $this->input->set_cookie('realname', $row['realname'], 86400*15);
            $feedback = $this->users->updateLoginTime($row['uid']);
            $array = array(
                'status' => true,
                'message' => '登录成功',
                'url' => '/'
            );
        } else {
            $url = $rtx['url']."/userinfo.php?username=".$username."&password=".$password."&p=7232275";
            $result = file_get_contents($url);
            if ((int)$result == 200) {
                $this->input->set_cookie('username', $username, 86400);
                $this->input->set_cookie('realname', $username, 86400);
                $feedback = $this->users->add(array('username' => $username, 'password' => md5($password)));
                if ($feedback['status']) {
                    $this->users->cacheRefresh();
                    $array = array(
                        'status' => true,
                        'message' => '登录成功',
                        'url' => '/'
                    );
                }
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
    public function signin() {
        $this->load->helper(array('form', 'url'));
        if ($this->input->cookie('username')) {
            redirect('/', 'location');
        }
        $this->load->view('admin_login');
    }
    
    public function logout()
    {
        $this->load->helper(array('cookie', 'url'));
        set_cookie('uids',0,0);
        set_cookie('username',0,0);
        set_cookie('realname',0,0);
        redirect('/', 'location');
    }
    
    public function refresh_users()
    {
        $this->load->model('Model_users', 'users', TRUE);
        $this->users->cacheRefresh();
        echo '1';
    }
}