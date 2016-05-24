<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {

    private $_projectCache = array();

    public function __construct() {
        parent::__construct();
        if (file_exists('./cache/project.conf.php')) {
            require './cache/project.conf.php';
            $this->_projectCache = $project;
        }
    }

    public function index() {

        //设置页面标题
        $data['PAGE_TITLE'] = '我的面板';

        $data['planListByIssue'] = array();
        $this->load->model('Model_issue', 'issue', TRUE);

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $data['workflow'] = $this->config->item('workflow', 'extension');
        $data['workflowfilter'] = $this->config->item('workflowfilter', 'extension');
        $data['tasktype'] = $this->config->item('tasktype', 'extension');
        $config = $this->config->item('pages', 'extension');

        //获取筛选值
        $folder = $this->uri->segment(3, 'to_me');
        if (in_array($folder, array('to_me', 'from_me', 'partin'))) {
            $folder = $this->uri->segment(3, 'to_me');
        } else {
            $folder = 'to_me';
        }

        $data['folder'] = $folder;
        $data['projectMd5'] = $projectId = $this->uri->segment(4, 0);
        $data['planId'] = $projectId = $this->uri->segment(5, 0);
        $data['flow'] = $this->uri->segment(6, 0);
        $data['taskType'] = $this->uri->segment(7, 0);
        $offset = $this->uri->segment(8, 0);

        //解析筛选值
        $flow = '-1';
        if (isset($data['workflowfilter'][$data['flow']])) {
            $flow = $data['workflowfilter'][$data['flow']]['id'];
        }
        $taskType = 0;
        if (isset($data['tasktype'][$data['taskType']])) {
            $taskType = $data['tasktype'][$data['taskType']];
        }
        if ($data['projectMd5'] && isset($this->_projectCache[$data['projectMd5']])) {
            $projectId = $this->_projectCache[$data['projectMd5']]['id'];
        } else {
            $data['projectMd5'] = 0;
        }

        if ($folder == 'partin') {
            $rows = $this->issue->partin($this->input->cookie('uids'), $folder, $projectId, $data['planId'], $flow, $taskType, $config['per_page'], $offset);
            $data['projectListByIssue'] = $this->issue->projectByAccept($this->input->cookie('uids'));
            if ($projectId) {
                $data['planListByIssue'] = $this->issue->planByAccept($this->input->cookie('uids'), $projectId);
                if ($data['planListByIssue']) {
                    foreach ($data['planListByIssue'] as $key => $value) {
                        $data['planArr'][$value['id']] = $value;
                    }
                }
            }
        } else {
            $rows = $this->issue->listByUserId($this->input->cookie('uids'), $folder, $projectId, $data['planId'], $flow, $taskType, $config['per_page'], $offset);
            //获取任务所涉及到的项目列表
            $data['projectListByIssue'] = $this->issue->projectListByIssue($this->input->cookie('uids'), $folder);
            if ($projectId) {
                $data['planListByIssue'] = $this->issue->planListByIssue($this->input->cookie('uids'), $projectId, $folder);
                if ($data['planListByIssue']) {
                    foreach ($data['planListByIssue'] as $key => $value) {
                        $data['planArr'][$value['id']] = $value;
                    }
                }
            }
        }
        
        if ($rows['data']) {
            $ids = array();
            foreach ($rows['data'] as $key => $value) {
                $ids[]= $value['id'];
            }
            $star = $this->issue->starByBugId($ids);
            if ($star) {
                foreach ($star as $key => $value) {
                    $data['star'][$value['star_id']] = $value['star_id'];
                }
            }
        }

        $data['rows'] = $rows['data'];
        $data['total'] = $rows['total'];

        $this->load->library('pagination');
        $config['total_rows'] = $rows['total'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/admin/index/'.$this->uri->segment(3, 'to_me').'/'.$data['projectMd5'].'/'.$data['planId'].'/'.$data['flow'].'/'.$data['taskType'];
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['per_page'] = $config['per_page'];

        $this->load->view('admin_dashboard', $data);
    }

    /**
     * 星标列表控制器
     */
    public function star() {

        //设置页面标题
        $data['PAGE_TITLE'] = '星标记录';

        //获取筛选参数
        $offset = $this->uri->segment(3, 0);

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $data['workflow'] = $this->config->item('workflow', 'extension');
        $data['workflowfilter'] = $this->config->item('workflowfilter', 'extension');
        $data['tasktype'] = $this->config->item('tasktype', 'extension');
        $config = $this->config->item('pages', 'extension');

        //读取数据
        $this->load->model('Model_issue', 'issue', TRUE);
        $rows = $this->issue->starList(0, $config['per_page'], $offset);

        $data['rows'] = $rows['data'];
        $data['total'] = $rows['total'];

        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->helper('friendlydate');

        //分页
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/admin/star/';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['per_page'] = $config['per_page'];
        $data['folder'] = 'index';

        $this->load->view('admin_dashboard', $data);
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
            $password = md5($password);
            if ($password != $row['password']) {
                $array = array(
                    'status' => false,
                    'message' => '登录信息有误，请重试',
                    'url' => '/admin/signin'
                );
                echo json_encode($array);
                exit();
            }
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
            $array = array(
                'status' => false,
                'message' => '用户信息不存在',
                'url' => '/admin/signin'
            );
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
        $this->load->view('signin');
    }

    public function signup() {
        $this->load->helper(array('form', 'url'));
        if ($this->input->cookie('uids')) {
            redirect('/', 'location');
        }
        $this->load->view('signup');
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

    public function refresh_project()
    {
        $this->load->model('Model_project', 'project', TRUE);
        $this->project->cacheRefresh();
        echo '1';
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
            $config["max_size"] = 2048;
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

    public function reg() {

        //验证表单项
        $this->load->library('form_validation');
        if ($this->form_validation->run() == FALSE) {
            exit(json_encode(array('status' => false, 'error' => validation_errors())));
        }

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $email = $this->input->post('email');

        $this->load->model('Model_users', 'users', TRUE);

        //验证邮箱是否重复
        $row = $this->users->checkEmail($email);
        if ($row) {
            exit(json_encode(array('status' => false, 'error' => '此邮箱已存在')));
        }

        //验证邮箱是否重复
        $row = $this->users->checkUser($username);
        if ($row) {
            exit(json_encode(array('status' => false, 'error' => '此用户名已被暂用')));
        }

        //注册
        $this->input->set_cookie('username', $username, 86400);
        $this->input->set_cookie('realname', $username, 86400);
        $feedback = $this->users->add(array('username' => $username, 'password' => md5($password), 'realname' => $username, 'email' => $email, 'role' => 2));
        if ($feedback['status']) {
            $this->input->set_cookie('uids', $feedback['uid'], 86400);
            $this->users->cacheRefresh();
            $array = array(
                'status' => true,
                'message' => '注册成功',
                'url' => '/'
            );
            echo json_encode($array);
        }
    }
}