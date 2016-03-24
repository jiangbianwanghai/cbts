<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {

    public function index()
    {
        $data['PAGE_TITLE'] = '我的控制台';

        $leftTime = $data['leftTime'] = strtotime(date("Y-m-d", time()));
        $rightTime = $data['rightTime'] = strtotime(date("Y-m-d", strtotime("+1 day")));

        //获取时间筛选范围并分解起止时间
        $picker = $this->input->get('picker', TRUE);
        if ($picker) {
            $pickerArr = explode(' - ', $picker);
            if (count($pickerArr) == 2) {
                $leftTime = strtotime($pickerArr[0]);
                $rightTime = strtotime($pickerArr[1]);
                $data['day'] = round(($rightTime - $leftTime)/86400)-1;
                $data['leftTime'] = $leftTime;
                $data['rightTime'] = $rightTime;
            }
        }

        $this->load->helper('url');
        if (!$this->input->cookie('uids')) {
            redirect('/admin/signin', 'location');
        } else {
            $stacked_str = $stacked_test_str = $stackedMyIssueStr = $stackedMyTestStr = '';

            $data['stacked_str'] = '';
            $data['stacked_test_str'] = '';
            $data['stackedMyIssueStr'] = '';
            $data['stackedMyTestStr'] = '';
            $data['users'] = '';

            if (file_exists('./cache/users.conf.php')) {
                require './cache/users.conf.php';
                $data['users'] = $users;
            }

            $data['role'] = $users[$this->input->cookie('uids')]['role'];

            $this->load->model('Model_issue', 'issue', TRUE);
            $this->load->model('Model_test', 'test', TRUE);

            if ($users[$this->input->cookie('uids')]['role'] == 1) {
                //我的按天统计任务量
                $stackedMyIssue = $this->issue->stackedByQa($this->input->cookie('uids'), $leftTime, $rightTime);
                if ($stackedMyIssue) {
                    $stackedMyIssueStr = "[";
                    foreach ($stackedMyIssue as $key => $value) {
                        $stackedMyIssueStr .= "{ y: '".$value['perday']."', a: ".$value['close'].", b: ".$value['able']." },";
                    }
                    $stackedMyIssueStr .= "]";
                }
                $data['stackedMyIssueStr'] = $stackedMyIssueStr;

                //我的按天统计提测量
                $stackedMyTest = $this->test->stackedByQa($this->input->cookie('uids'), $leftTime, $rightTime);
                if ($stackedMyTest) {
                    $stackedMyTestStr = "[";
                    foreach ($stackedMyTest as $key => $value) {
                        $stackedMyTestStr .= "{ y: '".$value['perday']."', a: ".$value['other'].", b: ".$value['no']." },";
                    }
                    $stackedMyTestStr .= "]";
                }
                $data['stackedMyTestStr'] = $stackedMyTestStr;
            }

            if ($users[$this->input->cookie('uids')]['role'] == 2) {
                //我的按天统计任务量
                $stackedMyIssue = $this->issue->stacked($this->input->cookie('uids'), $leftTime, $rightTime);
                if ($stackedMyIssue) {
                    $stackedMyIssueStr = "[";
                    foreach ($stackedMyIssue as $key => $value) {
                        $stackedMyIssueStr .= "{ y: '".$value['perday']."', a: ".$value['close'].", b: ".$value['able']." },";
                    }
                    $stackedMyIssueStr .= "]";
                }
                $data['stackedMyIssueStr'] = $stackedMyIssueStr;

                //我的按天统计提测量
                $stackedMyTest = $this->test->stacked($this->input->cookie('uids'), $leftTime, $rightTime);
                if ($stackedMyTest) {
                    $stackedMyTestStr = "[";
                    foreach ($stackedMyTest as $key => $value) {
                        $stackedMyTestStr .= "{ y: '".$value['perday']."', a: ".$value['other'].", b: ".$value['no']." },";
                    }
                    $stackedMyTestStr .= "]";
                }
                $data['stackedMyTestStr'] = $stackedMyTestStr;
            }

            //按天统计任务量
            
            $stacked = $this->issue->stacked(0, $leftTime, $rightTime);
            if ($stacked) {
                $stacked_str = "[";
                foreach ($stacked as $key => $value) {
                    $stacked_str .= "{ y: '".$value['perday']."', a: ".$value['close'].", b: ".$value['able']." },";
                }
                $stacked_str .= "]";
            }
            $data['stacked'] = $stacked_str;

            //按天统计提测量
            
            $stackedTest = $this->test->stacked(0, $leftTime, $rightTime);
            if ($stackedTest) {
                $stacked_test_str = "[";
                foreach ($stackedTest as $key => $value) {
                    $stacked_test_str .= "{ y: '".$value['perday']."', a: ".$value['other'].", b: ".$value['no']." },";
                }
                $stacked_test_str .= "]";
            }
            $data['stacked_test'] = $stacked_test_str;

            

            $topUserStr = '';

            $topUser = $this->test->topUser();
            $topUserStr = '[';
            if ($topUser) {
                foreach ($topUser as $key => $value) {
                    $topUserStr .= "{ label: '".$users[$value['add_user']]['realname']."', value: ".$value['num']."},";
                }
            }
            $topUserStr .= ']';
            $data['topUserStr'] = $topUserStr;


            $topPassUserStr = '';

            $topPassUser = $this->test->topPassUser();
            $topPassUserStr = '[';
            if ($topPassUser) {
                foreach ($topPassUser as $key => $value) {
                    $topPassUserStr .= "{ label: '".$users[$value['add_user']]['realname']."', value: ".$value['num']."},";
                }
            }
            $topPassUserStr .= ']';
            $data['topPassUserStr'] = $topPassUserStr;


            $topAcceptUserStr = '';

            $topAcceptUser = $this->test->topAcceptUser();
            $topAcceptUserStr = '[';
            if ($topAcceptUser) {
                foreach ($topAcceptUser as $key => $value) {
                    $topAcceptUserStr .= "{ label: '".$users[$value['accept_user']]['realname']."', value: ".$value['num']."},";
                }
            }
            $topAcceptUserStr .= ']';
            $data['topAcceptUserStr'] = $topAcceptUserStr;

            $topUserIssueStr = '';

            $topUserIssue = $this->issue->topUser();
            $topUserIssueStr = '[';
            if ($topUserIssue) {
                foreach ($topUserIssue as $key => $value) {
                    $topUserIssueStr .= "{ label: '".$users[$value['add_user']]['realname']."', value: ".$value['num']."},";
                }
            }
            $topUserIssueStr .= ']';
            $data['topUserIssueStr'] = $topUserIssueStr;


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

    public function email($uid = 1) {
        $this->load->library('email');
        $this->config->load('extension', TRUE);
        $config = $this->config->item('email', 'extension');
        $this->email->initialize($config);

        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }

        $this->config->load('extension', TRUE);
        $home = $this->config->item('home', 'extension');

        $this->email->from($config['smtp_user'], 'CBTS提醒服务');
        $this->email->to($users[$uid]['email']);
        $this->email->subject('2016年第十二周周报 From CBTS');
        $this->email->message($users[$uid]['realname'].'，你好：<br />你的周报已经生成，请点击链接。<a href="'.$home.'/conf/profile/'.$uid.'?picker=2016-03-21+-+2016-03-26">查看周报</a><br/><br/><p style="font-size:10px">如果不想再收到此类消息，可以选择<a href="'.$home.'/admin/unsubscribe">退订</a></p>');
        $this->email->send();
    }

    public function upload() {
        if($_FILES['upload_file']) {
            $dir_name = date("Ymd", time());
            $dir = '/usr/local/nginx/html/cbts/static/upload/'.$dir_name;
            if (!is_dir($dir)) mkdir($dir, 0777);
            $config['upload_path'] = $dir; 
            $config['file_name'] = 'IMG_'.time();
            $config['overwrite'] = TRUE;
            $config["allowed_types"] = 'jpg|jpeg|png|gif';
            $config["max_size"] = 1024;
            $this->load->library('upload', $config);

            if(!$this->upload->do_upload('upload_file')) {               
                $error = $this->upload->display_errors();
                echo '{"success": false,"msg": "'.$error.'"}';
            } else {
                $data['upload_data']=$this->upload->data();
                $img=$data['upload_data']['file_name'];
                echo '{"success": true,"file_path": "'.'/static/upload/'.$dir_name.'/'.$img.'"}';                              
            }  
        }
    }

    public function unsubscribe() {
        $this->load->model('Model_users', 'users', TRUE);
        $flag = $this->users->unsubscribe();
        if ($flag) {
            echo '退订成功，<a href="/">使用CBTS</a>';
        } else {
            echo '退订失败';
        }
    }
}