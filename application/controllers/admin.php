<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {

    public function index()
    {
        $data['PAGE_TITLE'] = '我的控制台';
        $this->load->helper('url');
        if (!$this->input->cookie('uids')) {
            redirect('/admin/signin', 'location');
        } else {
            $stacked_str = $stacked_test_str = $stackedMyIssueStr = $stackedMyTestStr = '';
            //按天统计任务量
            $this->load->model('Model_issue', 'issue', TRUE);
            $stacked = $this->issue->stacked();
            if ($stacked) {
                $stacked_str = "[";
                foreach ($stacked as $key => $value) {
                    $stacked_str .= "{ y: '".$value['perday']."', a: ".$value['close'].", b: ".$value['able']." },";
                }
                $stacked_str .= "]";
            }
            $data['stacked'] = $stacked_str;

            //按天统计提测量
            $this->load->model('Model_test', 'test', TRUE);
            $stackedTest = $this->test->stacked();
            if ($stackedTest) {
                $stacked_test_str = "[";
                foreach ($stackedTest as $key => $value) {
                    $stacked_test_str .= "{ y: '".$value['perday']."', a: ".$value['other'].", b: ".$value['no']." },";
                }
                $stacked_test_str .= "]";
            }
            $data['stacked_test'] = $stacked_test_str;

            //我的按天统计任务量
            $stackedMyIssue = $this->issue->stacked($this->input->cookie('uids'));
            if ($stackedMyIssue) {
                $stackedMyIssueStr = "[";
                foreach ($stackedMyIssue as $key => $value) {
                    $stackedMyIssueStr .= "{ y: '".$value['perday']."', a: ".$value['close'].", b: ".$value['able']." },";
                }
                $stackedMyIssueStr .= "]";
            }
            $data['stackedMyIssueStr'] = $stackedMyIssueStr;

            //我的按天统计提测量
            $stackedMyTest = $this->test->stacked($this->input->cookie('uids'));
            if ($stackedMyTest) {
                $stackedMyTestStr = "[";
                foreach ($stackedMyTest as $key => $value) {
                    $stackedMyTestStr .= "{ y: '".$value['perday']."', a: ".$value['other'].", b: ".$value['no']." },";
                }
                $stackedMyTestStr .= "]";
            }
            $data['stackedMyTestStr'] = $stackedMyTestStr;


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

            //写Cookie刷新登录时间
            $this->input->set_cookie('uids', $row['uid'], 86400*15);
            $this->input->set_cookie('username', $username, 86400*15);
            $this->input->set_cookie('realname', $row['realname'], 86400*15);
            $feedback = $this->users->updateLoginTime($row['uid']);

            //刷新用户文件缓存
            $this->users->cacheRefresh();

            //返回响应状态
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
                    $this->input->set_cookie('uids', $feedback['uid'], 86400);
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
        if ($this->input->cookie('uids')) {
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
        delete_cookie('uids');
        delete_cookie('username');
        delete_cookie('realname');
        redirect('/', 'location');
    }
    
    public function refresh_users()
    {
        $this->load->model('Model_users', 'users', TRUE);
        $this->users->cacheRefresh();
        echo '1';
    }

    public function refresh_repos()
    {
        $this->load->model('Model_repos', 'repos', TRUE);
        $this->repos->cacheRefresh();
        echo '1';
    }
}