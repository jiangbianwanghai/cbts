<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class issue extends CI_Controller {

    /**
     * 项目ID
     */
    private $_projectId = 0;

    /**
     * 项目缓存数组
     */
    private $_projectCache = array();

    public function __construct() {

        parent::__construct();

        //载入项目缓存文件
        if (file_exists(FCPATH.'/cache/project.conf.php')) {
            require FCPATH.'/cache/project.conf.php';
            $this->_projectCache = $project;
        } else {
            show_error('项目缓存文件载入失败，请联系<a href="mailto:webmaster@jiangbianwanghai.com">江边望海</a>。', 500, '错误');
        }

        //如果没有项目ID的Cookie就默认一个项目ID
        $projectId = $this->input->cookie('projectId');
        if ($projectId) {
            if (isset($project[$projectId]))
                $this->_projectId = $projectId;
            else
                show_error('无法获取项目信息（计划，任务，BUG，提测四个模块操作前先在页面顶部选择项目），请 <a href="/">返回首页</a> 选择项目', 500, '错误');
        } else {
            $currProject = end($project);
            $this->_projectId = $currProject['md5'];
            $this->input->set_cookie('projectId', $currProject['md5'], 86400*15);
        }
    }

    /**
     * 任务列表
     */
    public function index() {

        //设置页面标题
        $data['PAGE_TITLE'] = '新增任务';

        //获取项目ID
        $projectId = $this->_projectCache[$this->_projectId]['id'];

        //获取筛选项
        $folder = $this->uri->segment(3, 'all');
        if (in_array($folder, array('all', 'to_me', 'from_me'))) {
            $folder = $this->uri->segment(3, 'all');
        } else {
            $folder = 'all';
        }
        $data['folder'] = $folder;
        $data['planId'] = $this->uri->segment(4, 0);
        $data['flow'] = $this->uri->segment(5, 0);
        $data['taskType'] = $this->uri->segment(6, 0);
        $offset = $this->uri->segment(7, 0);

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $data['workflow'] = $this->config->item('workflow', 'extension');
        $data['workflowfilter'] = $this->config->item('workflowfilter', 'extension');
        $data['tasktype'] = $this->config->item('tasktype', 'extension');
        $config = $this->config->item('pages', 'extension');

        //查询数据
        $this->load->model('Model_issue', 'issue', TRUE);
        $uid = 0;
        if (in_array($folder, array('to_me', 'from_me'))) {
            $uid = $this->input->cookie('uids');
        }

        //转移筛选值
        $flow = '-1';
        if (isset($data['workflowfilter'][$data['flow']])) {
            $flow = $data['workflowfilter'][$data['flow']]['id'];
        }
        $taskType = 0;
        if (isset($data['tasktype'][$data['taskType']])) {
            $taskType = $data['tasktype'][$data['taskType']];
        }

        //获取计划
        $data['planListByIssue'] = $this->issue->planListByIssue($uid, $projectId, $folder);
        if ($data['planListByIssue']) {
            foreach ($data['planListByIssue'] as $key => $value) {
                $data['planArr'][$value['id']] = $value;
            }
        }

        //查询数据
        $rows = $this->issue->listByUserId($uid, $folder, $projectId, $data['planId'], $flow, $taskType, $config['per_page'], $offset);
        $data['rows'] = $rows['data'];
        $data['total'] = $rows['total'];

        //获取已经星标的ID
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

        //分页
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/issue/index/'.$folder.'/'.$data['planId'].'/'.$data['flow'].'/'.$data['taskType'];
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['per_page'] = $config['per_page'];

        $this->load->view('issue_index', $data);
    }

    /**
     * 星标列表控制器
     */
    public function star() {

        //设置页面标题
        $data['PAGE_TITLE'] = '星标记录';

        //获取筛选参数
        $offset = $this->uri->segment(3, 0);

        //获取项目ID
        $projectId = $this->_projectCache[$this->_projectId]['id'];

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $data['workflow'] = $this->config->item('workflow', 'extension');
        $data['workflowfilter'] = $this->config->item('workflowfilter', 'extension');
        $data['tasktype'] = $this->config->item('tasktype', 'extension');
        $config = $this->config->item('pages', 'extension');

        //读取数据
        $this->load->model('Model_issue', 'issue', TRUE);
        $rows = $this->issue->starList($projectId, $config['per_page'], $offset);

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
        $config['base_url'] = '/issue/star/';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['per_page'] = $config['per_page'];
        $data['folder'] = 'index';

        $this->load->view('issue_index', $data);
    }

    public function add() {
        $data['PAGE_TITLE'] = '新增任务';

        //读取项目计划
        $data['planId'] = $this->input->get('planId', TRUE);
        $data['planRows'] = array();
        if (!$data['planId']) {
            $this->load->model('Model_plan', 'plan', TRUE);
            $data['planRows'] = $this->plan->planFolder($this->_projectCache[$this->_projectId]['id']);
        }

        if (!$data['planRows'] && !$data['planId']) {
            show_error('还未创建计划，请 <a href="/plan">创建计划</a> 后再创建任务', 500, '提醒');
        }

        //载入用户缓存文件
        if (file_exists(FCPATH.'/cache/users.conf.php')) {
            require FCPATH.'/cache/users.conf.php';
            $data['users'] = $users;
        }

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');

        $this->load->view('issue_add', $data);
    }

    /**
     * 异步添加
     */
    public function add_ajax() {

        //验证表单项
        $this->load->library('form_validation');
        if ($this->form_validation->run() == FALSE) {
            $callBack = array(
                'status' => false,
                'message' => validation_errors(),
            );
            echo json_encode($callBack);
            exit();
        }

        //验证计划ID的合法性
        $this->load->model('Model_plan', 'plan', TRUE);
        $currPlan = $this->plan->fetchOne($this->input->post('plan_id'));
        if (!$currPlan) {
            $callBack = array(
                'status' => false,
                'message' => '获取计划信息失败，请确认你的信息。',
            );
            echo json_encode($callBack);
            exit();
        }

        //准备提交数据
        $this->load->model('Model_issue', 'issue', TRUE);
        $post = array(
            'project_id' => $currPlan['project_id'],
            'plan_id' => $this->input->post('plan_id'),
            'type' => $this->input->post('type'),
            'level' => $this->input->post('level'),
            'issue_name' => $this->input->post('issue_name'),
            'issue_summary' => $this->input->post('issue_summary'),
            'accept_user' => $this->input->post('accept_user'),
        );

        //如果有相关链接就序列化它
        if ($this->input->post('issue_url')) {
            $post['url'] = serialize(array_filter(explode(PHP_EOL, $this->input->post('issue_url'))));
        }

        //入库
        $feedback = $this->issue->add($post);
        $url = '/plan';
        if ($this->input->post('plan_id')) {
            $url .= '?planId='.$this->input->post('plan_id');
        }
        if ($feedback['status']) {

            //写入接受列表
            $this->load->model('Model_accept', 'accept', TRUE);
            $this->accept->add(array('accept_user' => $this->input->cookie('uids'), 'accept_time' => time(), 'issue_id' => $feedback['id'], 'flow' => 1));
            $this->accept->add(array('accept_user' => $this->input->post('accept_user'), 'accept_time' => time(), 'issue_id' => $feedback['id'], 'flow' => 2));
            $callBack = array(
                'status' => true,
                'message' => '提交成功',
                'url' => $url
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

        $this->load->helper('friendlydate');

        $data = array(
            'PAGE_TITLE' => '', //页面标题
            'row' => array(), //任务详情
            'test' => array(), //任务相关的提测
            'total_rows' => 0, //任务相关的提测数量
            'repos' => array(), //代码库缓存文件
            'users' => array(), //用户信息缓存文件
            'shareUsers' => array(), //贡献代码的用户信息
            'bug' => array(),
            'bug_total_rows' => 0
        );

        //获取任务详情
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->issue->fetchOne($id);
        if (!$data['row']) {
            show_error('您查找的任务不存在，请 <a href="/">返回首页</a>', 500, '错误');
        }
        $data['PAGE_TITLE'] = 'ISSUE-'.$data['row']['id'].' - '.$data['row']['issue_name'].' - 任务详情';

        //获取相关提测记录
        $this->load->model('Model_test', 'test', TRUE);
        $rows = $this->test->listByIssueId($id);
        if ($rows['total']) {
            $data['test'] = $rows['data'];
            $data['total_rows'] = $rows['total'];
        }

        //计算提测成功率
        $data['rate'] = '无提测数据用于计算';
        $testIdArr = array();
        if ($rows['data']) {
            foreach ($rows['data'] as $key => $value) {
                if (isset($testIdArr[$value['repos_id']])) {
                    $testIdArr[$value['repos_id']] += 1;
                } else {
                    $testIdArr[$value['repos_id']] = 1;
                }
            }
            $maxTest = max($testIdArr);
            $data['rate'] = sprintf("%.2f", 1/$maxTest);
        }

        //获取相关BUG记录
        $this->load->model('Model_bug', 'bug', TRUE);
        $rows = $this->bug->listByIssueId($id);
        if ($rows['total']) {
            $data['bug'] = $rows['data'];
            $data['bug_total_rows'] = $rows['total'];
        }
        
        //验证BUG是否都已经处理
        $data['fixedFlag'] = 1;
        if ($rows['data']) {
            foreach ($rows['data'] as $key => $value) {
                if ($value['state'] == '0' || $value['state'] == '1') {
                    $data['fixedFlag'] = 0;
                    break;
                }
            }
        }

        //将任务关注人转为数组
        $data['row']['watch'] = unserialize($data['row']['watch']);

        //读取所属计划
        $data['plan'] = array();
        if ($data['row']['plan_id']) {
            $this->load->model('Model_plan', 'plan', TRUE);
            $data['plan'] = $this->plan->fetchOne($data['row']['plan_id']);
        }

        
        //读取受理信息
        $this->load->model('Model_accept', 'accept', TRUE);
        $data['acceptUsers'] = $this->accept->users($id);
        
        //载入文件缓存
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }

        //读取任务相关的评论
        $this->load->model('Model_issuecomment', 'issuecomment', TRUE);
        $rows = $this->issuecomment->rows($id);
        $data['comment'] = $rows['data'];

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');

        $this->load->view('issue_view', $data);
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

        //设置页面标题
        $data['PAGE_TITLE'] = '编辑任务';

        //获取传入数据
        $id = $this->uri->segment(3, 0);

        //验证数据是否存在
        $this->load->model('Model_issue', 'issue', TRUE);
        $row = $this->issue->fetchOne($id);
        if (!$row) {
            show_error('您查找的任务不存在，请 <a href="/">返回首页</a>', 500, '错误');
        }

        //验证是否有权编辑
        if ($row['add_user'] != $this->input->cookie('uids')) {
            show_error('只有任务创建人才可以编辑，请 <a href="/issue/view/'.$id.'">返回任务</a>', 500, '错误');
        }

        //已经解决的任务自动归档不能编辑了
        if ($row['resolve']) {
            show_error('已经解决的任务自动归档不能编辑了~，请 <a href="/issue/view/'.$id.'">返回任务</a>', 500, '错误');
        }

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');

        if ($row) {
            $data['row'] = $row;
            if ($data['row']) {
                $data['row']['url'] = unserialize($data['row']['url']);
            }
            $this->load->view('issue_edit', $data);
        } else {
            show_error('你查找的数据不存在', 500, '错误');
        }
    }

    /**
     * 异步更新
     */
    public function edit_ajax() {
        $this->load->model('Model_issue', 'issue', TRUE);
        $post = array(
            'id' => $this->input->post('id'),
            'type' => $this->input->post('type'),
            'level' => $this->input->post('level'),
            'issue_name' => $this->input->post('issue_name'),
            'issue_summary' => $this->input->post('issue_summary')
        );
        if ($this->input->post('issue_url')) {
            $post['url'] = serialize(array_filter(explode(PHP_EOL, $this->input->post('issue_url'))));
        }
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
     * 更改受理人
     */
    public function change_accept() {

        //获取参数
        $id = $this->uri->segment(3, 0);
        $uid = $this->input->get("value", TRUE);

        //载入用户缓存文件
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }

        //更改受理人
        $this->load->model('Model_issue', 'issue', TRUE);
        $row = $this->issue->fetchOne($id);
        $this->issue->update_accept($id, $uid);

        $this->load->model('Model_accept', 'accept', TRUE);

        //指派研发人员
        if ($row['workflow'] <= 1) {
            $acceptRow = $this->accept->rowByIssue($row['id'], 2);
            if ($acceptRow) {
                $this->accept->update($uid, $acceptRow['id']);
            } else {
                $this->accept->add(array('accept_user' => $uid, 'accept_time' => time(), 'issue_id' => $id, 'flow' => 2));
            }
        }

        //指派测试人员
        if ($row['workflow'] == 2) {
            $acceptRow = $this->accept->rowByIssue($row['id'], 3);
            if ($acceptRow) {
                $this->accept->update($uid, $acceptRow['id']);
            } else {
                $this->accept->add(array('accept_user' => $uid, 'accept_time' => time(), 'issue_id' => $id, 'flow' => 3));
            }
        }

        //指派上线人员
        if ($row['workflow'] == 6) {
            $acceptRow = $this->accept->rowByIssue($row['id'], 4);
            print_r($acceptRow);
            if ($acceptRow) {
                $this->accept->update($uid, $acceptRow['id']);
            } else {
                $this->accept->add(array('accept_user' => $uid, 'accept_time' => time(), 'issue_id' => $id, 'flow' => 4));
            }
        }

        echo 1;
    }

    public function star_ajax() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $data = array('add_user' => $this->input->cookie('uids'), 'add_time' => time(), 'star_id' => $id, 'star_type' => 1);
        $flag = $this->issue->starAdd($data);
        if ($flag) {
            $callBack = array(
                    'status' => true,
                    'message' => '标记成功'
                );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '标记失败'
            );
        }
        echo json_encode($callBack);
    }

    public function star_del() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $flag = $this->issue->starDel($id);
        if ($flag) {
            $callBack = array(
                    'status' => true,
                    'message' => '取消标记成功'
                );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '取消标记失败'
            );
        }
        echo json_encode($callBack);
    }

    public function coment_add_ajax() {
        $this->load->model('Model_issue', 'issue', TRUE);
        $this->load->model('Model_issuecomment', 'issuecomment', TRUE);
        $row = $this->issue->fetchOne($this->input->post('issue_id'));
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '无此数据',
                'url' => '/'
            );
            exit();
        }
        $post = array(
            'issue_id' => $this->input->post('issue_id'),
            'content' => $this->input->post('content'),
            'add_user' => $this->input->cookie('uids'),
            'add_time' => time(),
        );
        $feedback = $this->issuecomment->add($post);
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }
        $this->load->helper('friendlydate');
        if ($feedback['status']) {
            if ($this->input->cookie('uids') == $row['accept_user']) {
                $usertype = '当前受理人';
            } else {
                $usertype = '路人甲';
            }
            $callBack = array(
                'status' => true,
                'message' => array(
                    'content'=>html_entity_decode($this->input->post('content')),
                    'username'=>$users[$this->input->cookie('uids')]['username'],
                    'realname'=>$users[$this->input->cookie('uids')]['realname'],
                    'addtime' => friendlydate(time()),
                    'usertype' => $usertype
                )
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '提交失败'
            );
        }
        echo json_encode($callBack);
    }

    public function del_comment() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issuecomment', 'issuecomment', TRUE);
        $flag = $this->issuecomment->del($id);
        if ($flag) {
            $callBack = array(
                'status' => true,
                'message' => '删除成功'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '删除失败'
            );
        }
        echo json_encode($callBack);
    }

    public function watch() {
        $id = $this->uri->segment(3, 0);
        $add = $this->uri->segment(4, 0);
        $name = $this->input->cookie('username');
        $this->load->model('Model_issue', 'watch', TRUE);
        $flag = $this->watch->updateWatch($id, $name, $add);
        if ($flag) {
            $callBack = array(
                'status' => true,
                'message' => '操作成功'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '操作失败'
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 更改工作流
     */
    public function change_flow() {

        //获取参数
        $id = $this->uri->segment(3, 0);
        $flow = $this->uri->segment(4, 0);

        //验证工作流参数合法性
        $this->config->load('extension', TRUE);
        $workflowfilter = $this->config->item('workflowfilter', 'extension');
        if (!isset($workflowfilter[$flow])) {
            $callBack = array(
                'status' => false,
                'message' => '参数不合法',
                'url' => '/'
            );
            echo json_encode($callBack);
            exit();
        }

        //验证ID合法性
        $this->load->model('Model_issue', 'issue', TRUE);
        $row = $this->issue->fetchOne($id);
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '参数不合法',
                'url' => '/'
            );
            echo json_encode($callBack);
            exit();
        }

        //验证受理人是否合法
        /**if ($row['accept_user'] != $this->input->cookie('uids')) {
            $callBack = array(
                'status' => false,
                'message' => '受理人不是你，你无权操作！',
                'url' => '/issue/view/'.$id
            );
            echo json_encode($callBack);
            exit();
        }*/

        //验证是否还存在激活的BUG
        if ($flow == 'wait') {
            $this->load->model('Model_bug', 'bug', TRUE);
            $bugAct = $this->bug->getBugAct($id);
            if ($bugAct) {
                $callBack = array(
                    'status' => false,
                    'message' => '还有正常开启的BUG需要解决',
                    'url' => '/issue/view/'.$row['id']
                );
                echo json_encode($callBack);
                exit();
            }
        }

        //更改工作流
        $user = $this->input->cookie('uids');
        if ($flow == 'fixed') {
            $this->load->model('Model_accept', 'accept', TRUE);
            $acceptRow = $this->accept->rowByIssue($id, 3);
            if ($acceptRow) {
                $user = $acceptRow['accept_user'];
            }
        }
        if (!empty($row['watch'])) {
            $watch = unserialize($row['watch']);
            if (count($watch) > 0) {
                foreach ($watch as $k=>$v) {
                    $watch[$k] = $v . '@gongchang.com';
                }
                $subject = '[CITS]' . $row['issue_name'] . '状态变为' . $workflowfilter[$flow]['name'];
                $message = '你关注的任务 > ' . $row['issue_name'] . ' < 状态变为 ' . $workflowfilter[$flow]['name'];
                $message .= '<br>更多信息，请点击URL查阅 http://cbts.gongchang.net/issue/view/' . $row['id'];
                $message .= '<br><br><br><i>收到这封邮件，是因为您关注了该任务。如果不想收到类似邮件，请点击上方链接，取消关注任务。</i>';
                $message .= '<br><i>更多信息，请查询 http://cbts.gongchang.net</i>';
                $this->load->library('email');
                $this->config->load('extension', TRUE);
                $email = $this->config->item('email', 'extension');
                $this->email->initialize($email);
                $this->email->from($email['smtp_user']);
                $this->email->to($watch);
                $this->email->subject($subject);
                $this->email->message($message);
                $this->email->send();
            }
        }
        $flag = $this->issue->changeFlow($id, $workflowfilter[$flow]['id'], $user);
        if ($flag) {
            $callBack = array(
                'status' => true,
                'message' => '操作成功',
                'url' => '/issue/view/'.$row['id']
            );
        } else {
            $callBack = array(
                'status' => true,
                'message' => '操作失败',
                'url' => '/issue/view/'.$row['id']
            );
        }
        echo json_encode($callBack);

    }
}
