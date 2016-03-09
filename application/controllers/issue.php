<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class issue extends CI_Controller {

    /**
     * 添加表单
     */
    public function add() {
    	$data['PAGE_TITLE'] = '申请提测';
        $this->load->view('issue_add', $data);
    }

    /**
     * 异步添加
     */
    public function add_ajax() {
        $this->load->model('Model_issue', 'issue', TRUE);
        $post = array(
            'level' => $this->input->post('level'),
            'issue_name' => $this->input->post('issue_name'),
            'url' => $this->input->post('issue_url'),
            'issue_summary' => $this->input->post('issue_summary')
        );
        $feedback = $this->issue->add($post);
        if ($feedback['status']) {
            $callBack = array(
                'status' => true,
                'message' => '提交成功',
                'url' => '/issue/my'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '提交失败'.$feedback['message'],
                'url' => '/issue/add'
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 任务详情
     */
    public function view() {
        $id = $this->uri->segment(3, 0);

        //获取任务详情
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->issue->fetchOne($id);
        $data['PAGE_TITLE'] = 'ISSUE-'.$data['row']['id'].' - '.$data['row']['issue_name'].' - 任务详情';

        //获取相关提测记录
        $this->load->model('Model_test', 'test', TRUE);
        $data['test'] = $this->test->listByIssueId($id);

        //载入文件缓存
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }

        //获取贡献代码的用户信息
        $data['shareUsers'] = $this->test->shareUsers($id);
        $this->load->view('issue_view', $data);
    }

    /**
     * 我的任务列表
     */
    public function my() {
        $data['PAGE_TITLE'] = '我的任务列表';
        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');
        $offset = trim($this->uri->segment(3, 0));
        $this->load->model('Model_issue', 'issue', TRUE);
        $rows = $this->issue->my($offset, $config['per_page']);
        $data['rows'] = $rows['data'];
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/issue/my';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $this->load->view('issue_my', $data);
    }

    /**
     * 我的受理列表
     */
    public function todo() {
        $data['PAGE_TITLE'] = '我的受理列表';
        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');
        $offset = trim($this->uri->segment(3, 0));
        $this->load->model('Model_issue', 'issue', TRUE);
        $rows = $this->issue->todo($offset, $config['per_page']);
        $data['rows'] = $rows['data'];
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/issue/todo';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $this->load->view('issue_todo', $data);
    }

    /**
     * 任务广场列表
     */
    public function plaza() {
        $data['PAGE_TITLE'] = '任务广场列表';

        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');

        //页码
        $offset = $this->uri->segment(7, 0);

        //处理进度
        $resolve = $this->uri->segment(3, 'able');

        //任务状态
        $status = $this->uri->segment(4, 'able');

        //申请角色
        $add_user = $this->uri->segment(5, 'all');

        //受理角色
        $accept_user = $this->uri->segment(6, 'all');
        
        //读取数据
        $this->load->model('Model_issue', 'issue', TRUE);

        $rows = $this->issue->plaza($add_user, $accept_user, $status, $resolve, $offset, $config['per_page']);
        $data['rows'] = $rows['data'];
        $data['total_rows'] = $rows['total_rows'];


        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/issue/plaza/'.$resolve.'/'.$status.'/'.$add_user.'/'.$accept_user;
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();

        $data['offset'] = $offset;
        $data['resolve'] = $resolve;
        $data['status'] = $status;
        $data['add_user'] = $add_user;
        $data['accept_user'] = $accept_user;

        $this->load->view('issue_plaza', $data);
    }

    /**
     * 任务删除
     */
    public function del() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);

        //已经解决的任务自动归档不能删除了
        $resolve = $this->issue->checkResolve($id);
        if ($resolve) {
            $callBack = array(
                'status' => false,
                'message' => '已经解决的任务自动归档不能删除了',
                'url' => '/issue/my'
            );
            echo json_encode($callBack);
            exit(); 
        }

        //已经受理并且受理人不是自己是没有办法删除的
        $accpetUser = $this->issue->checkAccept($id);
        if (!empty($accpetUser) && $accpetUser != $this->input->cookie('uids')) {
            $callBack = array(
                'status' => false,
                'message' => '已经被别人受理了，你不能删除~',
                'url' => '/issue/my'
            );
            echo json_encode($callBack);
            exit(); 
        }

        //任务删除后相关的提测信息也需要删除
        $issue_flag = $this->issue->del($id);
        $callBack['url'] = '/issue/view/'.$id;
        if ($issue_flag) {
            $callBack['message'] = '任务删除成功';
            //删除相关的提测任务
            $this->load->model('Model_test', 'test', TRUE);
            $test_flag = $this->test->delByIssueID($id);
            if ($test_flag) {
                $callBack['status'] = true;
                $callBack['message'] .= '，相关提测也已经删除成功';
            } else {
                $callBack['status'] = false;
                $callBack['message'] .= '，相关提测删除失败';
            }
        } else {
            $callBack['status'] = false;
            $callBack['message'] = '任务删除失败';
        }
        echo json_encode($callBack);
    }

    /**
     * 任务关闭
     */
    public function close() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        //已经受理并且受理人不是自己是没有办法关闭的
        $accpetUser = $this->issue->checkAccept($id);
        if (!empty($accpetUser) && $accpetUser != $this->input->cookie('uids')) {
            $callBack = array(
                'status' => false,
                'message' => '已经被别人受理了，你不能进行关闭操作~',
                'url' => '/issue/view/'.$id
            );
            echo json_encode($callBack);
            exit(); 
        }
        $row = $this->issue->fetchOne($id);
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '数据错误',
                'url' => '/'
            );
            echo json_encode($callBack);
            exit();
        }

        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }

        $this->config->load('extension', TRUE);
        $home = $this->config->item('home', 'extension');
        $home = $home."/issue/view/".$id;

        $feedback = $this->issue->close($id);
        $subject = $users[$this->input->cookie('uids')]['realname']."提醒你：[".$row['issue_name']."]他给关闭了";
        $this->rtx($users[$row['add_user']]['username'],$home,$subject);

        if ($feedback) {
            $callBack = array(
                'status' => true,
                'message' => '关闭成功',
                'url' => '/issue/view/'.$id
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '关闭失败',
                'url' => '/issue/view/'.$id
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 任务关闭
     */
    public function open() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $row = $this->issue->fetchOne($id);
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '数据错误',
                'url' => '/'
            );
            echo json_encode($callBack);
            exit();
        }

        if ($this->input->cookie('uids') == $row['add_user'] || $this->input->cookie('uids') == $row['accept_user']) {
            if (file_exists('./cache/users.conf.php')) {
                require './cache/users.conf.php';
            }

            $this->config->load('extension', TRUE);
            $home = $this->config->item('home', 'extension');
            $home = $home."/issue/view/".$id;

            $feedback = $this->issue->open($id);
            $subject = $users[$this->input->cookie('uids')]['realname']."提醒你：[".$row['issue_name']."]重新开启了";
            $this->rtx($users[$row['add_user']]['username'],$home,$subject);

            if ($feedback) {
                $callBack = array(
                    'status' => true,
                    'message' => '关闭成功',
                    'url' => '/issue/view/'.$id
                );
            } else {
                $callBack = array(
                    'status' => false,
                    'message' => '关闭失败',
                    'url' => '/issue/view/'.$id
                );
            }
            echo json_encode($callBack);
        } else {
            $callBack = array(
                'status' => false,
                'message' => '非发布人或受理人不能进行此操作',
                'url' => '/issue/view/'.$id
            );
            echo json_encode($callBack);
            exit(); 
        }
    }

    /**
     * 任务已解决
     */
    public function resolve() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        //已经受理并且受理人不是自己是没有办法关闭的
        $accpetUser = $this->issue->checkAccept($id);
        if (!empty($accpetUser) && $accpetUser != $this->input->cookie('uids')) {
            $callBack = array(
                'status' => false,
                'message' => '已经被别人受理了，你不能进行解决操作~',
                'url' => '/issue/view/'.$id
            );
            echo json_encode($callBack);
            exit(); 
        }
        $row = $this->issue->fetchOne($id);
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '数据错误',
                'url' => '/'
            );
            echo json_encode($callBack);
            exit();
        }

        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }

        $this->config->load('extension', TRUE);
        $home = $this->config->item('home', 'extension');
        $home = $home."/issue/view/".$id;
        $feedback = $this->issue->resolve($id);
        $subject = $users[$this->input->cookie('uids')]['realname']."提醒你：[".$row['issue_name']."]已经解决并关闭了";
        $this->rtx($users[$row['add_user']]['username'],$home,$subject);
        if ($feedback) {
            $callBack = array(
                'status' => true,
                'message' => '解决成功',
                'url' => '/issue/view/'.$id
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '解决失败',
                'url' => '/issue/view/'.$id
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 编辑任务
     */
    public function edit() {
        $data['PAGE_TITLE'] = '编辑任务';
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        //已经解决的任务自动归档不能编辑了
        $resolve = $this->issue->checkResolve($id);
        if ($resolve) {
            exit('已经解决的任务自动归档不能编辑了~');
        }
        //已经受理并且受理人不是自己是没有办法编辑的
        $accpetUser = $this->issue->checkAccept($id);
        if (!empty($accpetUser) && $accpetUser != $this->input->cookie('uids')) {
            exit('已经被别人受理了，你不能编辑了~');
        }
        $row = $this->issue->fetchOne($id);
        if ($row) {
            $data['row'] = $row;
            $this->load->view('issue_edit', $data);
        } else {
            echo '你查找的数据不存在.';
        }
    }

    /**
     * 异步更新
     */
    public function edit_ajax() {
        $this->load->model('Model_issue', 'issue', TRUE);
        $post = array(
            'id' => $this->input->post('id'),
            'level' => $this->input->post('level'),
            'issue_name' => $this->input->post('issue_name'),
            'url' => $this->input->post('issue_url'),
            'issue_summary' => $this->input->post('issue_summary')
        );
        $feedback = $this->issue->update($post);
        if ($feedback) {
            $callBack = array(
                'status' => true,
                'message' => '更新成功',
                'url' => '/issue/view/'.$this->input->post('id')
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '更新失败',
                'url' => '/issue/edit/'.$this->input->post('id')
            );
        }
        echo json_encode($callBack);
    }


    /**
     * 分析
     */
    public function analytics() {
        $data['PAGE_TITLE'] = '任务统计';
        $this->load->view('issue_analytics', $data);
    }

    public function change_accept() {
        $id = $this->uri->segment(3, 0);
        $uid = $this->input->post("value");
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }


        $this->load->model('Model_issue', 'issue', TRUE);
        $this->issue->update_accept($id, $uid);

        $username =  $users[$uid]['username'];
        $this->config->load('extension', TRUE);
        $home = $this->config->item('home', 'extension');
        $url = $home."/issue/view/".$id;
        $subject = $users[$this->input->cookie('uids')]['realname']."指派了一个任务给你";
        $this->rtx($username,$url,$subject);
        echo 1;
    }

    private function rtx($toList,$url,$subject)
    {
        $subject = str_replace(array('#', '&', ' '), '', $subject);
        $pushInfo = array(
            'to' => $toList,
            'title' => 'CBTS提醒你：',     
            'msg' => $subject . $url,
            'delaytime' => '',                                                                                                                                                               
        );
        $receiver        = iconv("utf-8","gbk//IGNORE", $pushInfo['to']);
        $this->config->load('extension', TRUE);
        $rtx = $this->config->item('rtx', 'extension');
        $url = $rtx['url'].'/sendtortx.php?receiver=' . $receiver . '&notifytitle=' .$pushInfo['title']. '&notifymsg=' . $pushInfo['msg'] . '&delaytime=' . $pushInfo['delaytime'];           
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $str = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    }
}