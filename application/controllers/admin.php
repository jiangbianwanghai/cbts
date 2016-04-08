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

    /**
     * 任务量统计
     */
    public function issueAnalytics() {
        $this->load->model('Model_issue', 'issue', TRUE);
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }
        $picker = $this->input->get('picker', TRUE);
        $dateRange = $this->getDateRange($picker);
        $type = $this->uri->segment(3, 0);
        $data['type'] = $type;
        if ($type == 'my') {
            $data['stackedMyIssueStr'] = '';
            if ($users[$this->input->cookie('uids')]['role'] == 1) {
                $stackedMyIssue = $this->issue->stackedByQa($this->input->cookie('uids'), $dateRange['leftTime'], $dateRange['rightTime']);
            }
            if ($users[$this->input->cookie('uids')]['role'] == 2) {
                $stackedMyIssue = $this->issue->stacked($this->input->cookie('uids'), $dateRange['leftTime'], $dateRange['rightTime']);
            }
            if ($stackedMyIssue) {
                $data['stackedMyIssueStr'] = "[";
                foreach ($stackedMyIssue as $key => $value) {
                    $data['stackedMyIssueStr'] .= "{ y: '".$value['perday']."', a: ".$value['close'].", b: ".$value['able']." },";
                }
                $data['stackedMyIssueStr'] .= "]";
            }
        }
        if ($type == 'all') {
            $stacked = $this->issue->stacked(0, $dateRange['leftTime'], $dateRange['rightTime']);
            if ($stacked) {
                $stacked_str = "[";
                foreach ($stacked as $key => $value) {
                    $stacked_str .= "{ y: '".$value['perday']."', a: ".$value['close'].", b: ".$value['able']." },";
                }
                $stacked_str .= "]";
            }
            $data['stacked'] = $stacked_str;
        }
        $this->load->view('analytics_issue', $data);
    }

    /**
     * 任务量统计
     */
    public function testAnalytics() {
        $this->load->model('Model_test', 'test', TRUE);
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }
        $picker = $this->input->get('picker', TRUE);
        $data['role'] = $users[$this->input->cookie('uids')]['role'];
        $dateRange = $this->getDateRange($picker);
        $type = $this->uri->segment(3, 0);
        $data['type'] = $type;
        if ($type == 'my') {
            $data['stackedMyTestStr'] = '';
            if ($users[$this->input->cookie('uids')]['role'] == 1) {
                $stackedMyTest = $this->test->stackedByQa($this->input->cookie('uids'), $dateRange['leftTime'], $dateRange['rightTime']);
            }
            if ($users[$this->input->cookie('uids')]['role'] == 2) {
                $stackedMyTest = $this->test->stacked($this->input->cookie('uids'), $dateRange['leftTime'], $dateRange['rightTime']);
            }
            if ($stackedMyTest) {
                $data['stackedMyTestStr'] = "[";
                foreach ($stackedMyTest as $key => $value) {
                    $data['stackedMyTestStr'] .= "{ y: '".$value['perday']."', a: ".$value['other'].", b: ".$value['no']." },";
                }
                $data['stackedMyTestStr'] .= "]";
            }
        }
        if ($type == 'all') {
            $stackedTest = $this->test->stacked(0, $dateRange['leftTime'], $dateRange['rightTime']);
            if ($stackedTest) {
                $stacked_test_str = "[";
                foreach ($stackedTest as $key => $value) {
                    $stacked_test_str .= "{ y: '".$value['perday']."', a: ".$value['other'].", b: ".$value['no']." },";
                }
                $stacked_test_str .= "]";
            }
            $data['stacked_test'] = $stacked_test_str;
        }
        $this->load->view('analytics_test', $data);
    }

    public function people() {
        $this->load->model('Model_issue', 'issue', TRUE);
        $this->load->model('Model_test', 'test', TRUE);
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }
        $type = $this->uri->segment(3, 0);
        $data['type'] = $type;

        //提测最多的人
        if ($type == 'test') {
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
        }
        
        //提测不通过最多的人
        if ($type == 'testpass') {
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
        }

        //受理最多的人
        if ($type == 'testaccept') {
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
        }

        //添加任务最多的人
        if ($type == 'issue') {
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
        }
        $this->load->view('top', $data);
    }

    /**
     * 获取时间范围
     * @access private
     * @param mixed $picker 时间区间。格式：2016-03-23+-+2016-03-24 
     * @return array (leftTime:开始时间,rightTime:截至时间,day:相差天数)
     */
    private function getDateRange($picker) {
        $array = array('leftTime' => '', 'rightTime' => '', 'day' => 0);
        //默认是当前时间
        $array['leftTime'] = strtotime(date("Y-m-d", time()));
        $array['rightTime'] = strtotime(date("Y-m-d", strtotime("+1 day")));
        //获取时间筛选范围并分解起止时间
        if ($picker) {
            $pickerArr = explode(' - ', $picker);
            if (count($pickerArr) == 2) {
                $array['day'] = round((strtotime($pickerArr[1]) - strtotime($pickerArr[0]))/86400)-1;
                $array['leftTime'] = strtotime($pickerArr[0]);
                $array['rightTime'] = strtotime($pickerArr[1]);
            }
        }
        return $array;
    }

    //输出每个人的月报
    public function tongji() {
        $uid = $this->uri->segment(3, 0);
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }
        if ($uid) {

            $taskNumByme = $taskNumWithme = $testNumByme = $testNumWait = $testNumPass = $butongguo = 0;

            if ($users[$uid]['role'] != 2) {
                exit('仅输出研发人员的数据');
            }

            $this->load->model('Model_issue', 'issue', TRUE);
            $this->load->model('Model_test', 'test', TRUE);
            $issueTj = $this->issue->tongji($uid);
            $taskNumByme = $issueTj['taskNumByme'];
            $testTj = $this->test->tongji($uid);
            $taskNumWithme = $testTj['taskNumWithme'];
            $testNumByme = $testTj['testNumByme'];
            $testNumWait = $testTj['testNumWait'];
            $testNumPass = $testTj['testNumPass'];
            $fenmu = $testNumByme - $testNumWait;
            if ($fenmu) {
                $butongguo = sprintf("%.2f", $testNumPass/($testNumByme - $testNumWait))*100;
            }
            

            echo $users[$uid]['realname'].'，你好：<br />';
            echo '根据你在CBTS上产生的数据，以下是你3月份（3.1~31）的统计结果。<br />';
            echo '你发起了 <b><font color="#ff0000">'.$taskNumByme.' </font></b> 个任务<br />';
            echo '你参与了 <b><font color="#ff0000">'.$taskNumWithme.' </font></b> 个任务<br />';
            echo '提交了 <b><font color="#ff0000">'.$testNumByme.' </font></b> 次测试<br />';
            echo '有 <b><font color="#ff0000">'.$testNumWait.' </font></b> 个正在等待测试<br />';
            echo '有 <b><font color="#ff0000">'.$testNumPass.' </font></b> 次不通过<br />';
            echo '不通过率（不通过/已提测量，不包含为提测的） <b><font color="#ff0000">'.$butongguo.'% </font></b> 次测试<br />';

        } else {
            $list = '';
            foreach ($users as $key => $value) {
                if ($value['role'] == 2) {
                    $list .= '<a href="/admin/tongji/'.$value['uid'].'" target="_blank">'.$value['realname'].'</a><br />';
                }
            }
            echo $list;
        }
    }

    public function curl() {
        $this->load->library('curl');
        $res = $this->curl->get('http://product.gongchang.com/s9106/CNS29003818551.html?gct=1.1.3-3-1-2.1');
        print_r($res);
    }
}