<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class test extends CI_Controller {

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
     * 添加表单
     */
    public function add() {

        //设置页面标题
    	$data['PAGE_TITLE'] = '提交代码';

        //获取传入参数
        $id = $this->uri->segment(3, 0);

        //验证数据是否存在
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->issue->fetchOne($id);
        if (!$data['row'])
            show_error('您查找的任务不存在，请 <a href="/">返回首页</a>', 500, '错误');

        //已经解决的任务不能再次添加提测信息
        if ($data['row']['resolve'])
            show_error('已经解决的任务不能再次添加提测信息~，请 <a href="/issue/view/'.$id.'">返回任务</a>', 500, '错误');
        
        if (file_exists(FCPATH.'/cache/repos.conf.php')) {
            require FCPATH.'/cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        
        if (file_exists(FCPATH.'/cache/users.conf.php')) {
            require FCPATH.'/cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->view('test_add', $data);
    }

    /**
     * 添加入库
     */
    public function add_ajax() {

        //获取任务ID
        $issueId = $this->input->post('issue_id');

        //验证任务ID合法性
        $this->load->model('Model_issue', 'issue', TRUE);
        $row = $this->issue->fetchOne($issueId);
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '任务ID不合法',
                'url' => '/test/add/'.$issueId
            );
            echo json_encode($callBack);
            exit();
        }

        //提测版本号不能为空
        $test_flag = $this->input->post('test_flag');
        if ($test_flag <= 0 && is_numeric($test_flag)) {
            $callBack = array(
                'status' => false,
                'message' => '提测版本号不能为空',
                'url' => '/test/add/'.$issueId
            );
            echo json_encode($callBack);
            exit();
        }

        //提测版本号不能已经存在
        $this->load->model('Model_test', 'test', TRUE);
        /**if (is_numeric($test_flag)) {
            $checkTestFlag = $this->test->checkFlag($this->input->post('repos_id'), $this->input->post('br'), $this->input->post('test_flag'));
            if (!$checkTestFlag) {
                $callBack = array(
                    'status' => false,
                    'message' => '提测版本已经存在',
                    'url' => '/test/add/'.$issueId
                );
                echo json_encode($callBack);
                exit();
            }
        }*/

        $post = array(
            'project_id' => $row['project_id'],
            'plan_id' => $row['plan_id'],
            'issue_id' => $this->input->post('issue_id'),
            'repos_id' => $this->input->post('repos_id'),
            'br' => $this->input->post('br'),
            'test_flag' => $this->input->post('test_flag'),
            'test_summary' => $this->input->post('test_summary'),
            'accept_user' => $this->input->post('accept_user'),
            'accept_time' => time()
        );

        $feedback = $this->test->add($post);
        if ($feedback['status']) {
            $callBack = array(
                'status' => true,
                'message' => '提交成功',
                'url' => '/issue/view/'.$this->input->post('issue_id')
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '提交失败',
                'url' => '/test/add/'.$this->input->post('issue_id')
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 编辑提测
     */
    public function edit() {
        $data['PAGE_TITLE'] = '编辑提测';
        $issueId = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->issue->fetchOne($issueId);
        if (!$data['row']) {
            exit("查询数据错误.");
        }
        //只有发布人和受理人可以编辑
        if ($data['row']['add_user'] == $this->input->cookie('uids') || $data['row']['accept_user'] == $this->input->cookie('uids')) {
            $testId = $this->uri->segment(4, 0);
            $this->load->model('Model_test', 'test', TRUE);
            $row = $this->test->fetchOne($testId);
            if ($row) {
                $data['test'] = $row;
                if (file_exists('./cache/repos.conf.php')) {
                    require './cache/repos.conf.php';
                    $data['repos'] = $repos;
                }
                $this->load->view('test_edit', $data);
            } else {
                echo '你查找的数据不存在.';
            }
        } else {
            exit("只有发布人和受理人可以编辑");
        }

        
    }

    /**
     * 异步更新
     */
    public function edit_ajax() {
        $this->load->model('Model_test', 'test', TRUE);
        $post = array(
            'id' => $this->input->post('test_id'),
            'repos_id' => $this->input->post('repos_id'),
            'test_flag' => $this->input->post('test_flag'),
            'test_summary' => $this->input->post('test_summary')
        );
        $feedback = $this->test->update($post);
        if ($feedback) {
            $callBack = array(
                'status' => true,
                'message' => '更新成功',
                'url' => '/issue/view/'.$this->input->post('issue_id')
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '更新失败',
                'url' => '/issue/edit/'.$this->input->post('issue_id')
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 任务删除
     */
    public function del() {
        $testId = $this->uri->segment(3, 0);
        $issueId = $this->uri->segment(4, 0);
        $this->load->model('Model_test', 'test', TRUE);
        //验证传参是否正确
        $row = $this->test->fetchOne($testId);
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '查询数据错误',
                'url' => '/issue/view/'.$issueId
            );
            echo json_encode($callBack);
            exit();
        }

        //只有发布人和受理人可以编辑
        if ($row['add_user'] == $this->input->cookie('uids') || $row['accept_user'] == $this->input->cookie('uids')) {
            //一旦提测都无法删除
            if ($row['rank'] > 0) {
                $callBack = array(
                    'status' => false,
                    'message' => '已经提测，无法删除',
                    'url' => '/issue/view/'.$issueId
                );
            } else {
                $feedback = $this->test->del($testId);
                if ($feedback) {
                    $callBack = array(
                        'status' => true,
                        'message' => '删除成功',
                        'url' => '/issue/view/'.$issueId
                    );
                } else {
                    $callBack = array(
                        'status' => false,
                        'message' => '删除失败',
                        'url' => '/issue/view/'.$issueId
                    );
                }
            }
        } else {
            $callBack = array(
                'status' => false,
                'message' => '只有发布人和受理人可以删除',
                'url' => '/issue/view/'.$issueId
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 提测详情
     */
    public function view() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_test', 'test', TRUE);
        $row = $this->test->fetchOne($id);
        echo '<p>'.nl2br($row['test_summary']).'</p>';
    }

    /**
     * 读取当前版本与上一个版本的日志
     */
    public function log() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_test', 'test', TRUE);
        $row = $this->test->fetchOne($id);
        $file = '/usr/local/nginx/html/cbts/cache/test_log_'.$row['id'].'.log';
        if (file_exists($file)) {
            $content = file_get_contents('/usr/local/nginx/html/cbts/cache/test_log_'.$row['id'].'.log');
            echo nl2br($content);
        } else {
            //获取上一个任务
            $prevRow = $this->test->prev2($row['repos_id'], $row['test_flag']);
            if ($prevRow) {
                $prev_flag = $prevRow['test_flag'];
            } else {
                $prev_flag = $row['test_flag'] - 5;
            }
            $this->config->load('extension', TRUE);
            $sqs = $this->config->item('sqs', 'extension');
            //打队列
            $sqs_url = $sqs."/?name=logdiff&opt=put&data=";
            $sqs_url .= $row['id']."|".$row['repos_id']."|".$prev_flag."|".$row['test_flag']."|".$row['br']."&auth=mypass123";
            file_get_contents($sqs_url);
            echo '请关闭窗口等候2秒，再点击就有了';
        }
        
    }

    /**
     * 读取当前版本与上一个版本的文件差异
     */
    public function diff() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_test', 'test', TRUE);
        $row = $this->test->fetchOne($id);
        $content = file_get_contents('/usr/local/nginx/html/cbts/cache/test_diff_'.$row['id'].'.log');
        echo nl2br($content);
    }

    /**
     * 某个版本库的提测列表
     */
    public function repos() {
        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');
        $repos_id = $this->uri->segment(3, 0);
        $offset = $this->uri->segment(4, 0);
        $this->load->model('Model_test', 'test', TRUE);
        $rows = $this->test->repos($repos_id, $offset, $config['per_page']);
        $data['rows'] = $rows['data'];
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $data['PAGE_TITLE'] = $repos[$repos_id]['repos_name'].'的提测历史';

        $this->load->library('pagination');
        $config['uri_segment'] = 4;
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/test/repos/'.$repos_id;
        $this->pagination->initialize($config);
        $data['offset'] = $offset;
        $data['pages'] = $this->pagination->create_links();

        $this->load->view('test_repos', $data);
    }

    /**
     * 提测
     */
    public function tice() {

        //获取提测id
        $id = $this->uri->segment(3, 0);

        //根据id验证记录是否存在
        $this->load->model('Model_test', 'test', TRUE);
        $row = $this->test->fetchOne($id);

        //不存在则直接返回数据错误
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '数据错误',
                'url' => '/'
            );
            echo json_encode($callBack);
            exit();
        }

        $callBack['url'] = '/issue/view/'.$row['issue_id'];

        //验证是否有权限提测
        if ($row['accept_user'] != $this->input->cookie('uids')) {
            $callBack['status'] = false;
            $callBack['message'] = '非受理人不能提测';
            echo json_encode($callBack);
            exit();
        }

        //验证提测所属的任务是否被受理
        $this->load->model('Model_issue', 'issue', TRUE);
        $flag = $this->issue->checkAccept($row['issue_id']);
        if (!$flag) {
            //对提测所属的任务标记标记受理人，谁第一个提测，谁就是该提测所属任务的受理人
            $this->issue->accept($row['issue_id']);
        }

        //提测标记受理
        $this->test->accept($id);

        //验证是否需要合并后提测
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }

        $this->config->load('extension', TRUE);
        $sqs = $this->config->item('sqs', 'extension');
        $cap = $this->config->item('cap', 'extension');

        if ($row['br'] == 'dev' || $row['repos_id'] == '43') {
            if ($repos[$row['repos_id']]['merge']) {
                //需要合并
                //获取该版本库的前面的一个提测任务
                $prevRow = $this->test->prev2($row['repos_id'], $row['test_flag']);
                if ($prevRow) {
                    if (($prevRow['state'] == 0 || $prevRow['state'] == 1) && ($prevRow['br'] == $row['br'])) {
                        $callBack = array(
                            'status' => true,
                            'message' => '前面有测试任务正在进行，请稍后',
                            'url' => '/issue/view/'.$row['issue_id']
                        );
                        $this->test->returntice($id);
                        echo json_encode($callBack);
                        exit();
                    }
                    $oldversion = $prevRow['trunk_flag'];
                } else {
                    $oldversion = 1;
                }

                $reason = $users[$row['add_user']]['realname']."提测，并由".$users[$this->input->cookie('uids')]['realname']."进行测试，解决的问题是ISSUE-".$row['issue_id'];
                

                //打队列，数据顺序：test_id[提测任务ID]|add_user[提测任务添加人]|repos_id[代码ID]|oldversion[测试任务前一个标识]|curr_flag[当前标识]
                $sqs_url = $sqs."/?name=mergev2&opt=put&data=";
                $sqs_url .= $row['id']."|".$row['add_user']."|".$row['repos_id']."|".$oldversion."|".$row['test_flag']."|".$reason."|".$row['issue_id']."|".$row['accept_user']."&auth=mypass123";
                file_get_contents($sqs_url);
            } else {
                //获取该版本库的前面的一个提测任务
                $prevRow = $this->test->prev2($row['repos_id'], $row['test_flag']);
                if ($prevRow) {
                    if ($prevRow['state'] == 0 || $prevRow['state'] == 1) {
                        $callBack = array(
                            'status' => true,
                            'message' => '前面有测试任务正在进行，请稍后',
                            'url' => '/issue/view/'.$row['issue_id']
                        );
                        $this->test->returntice($id);
                        echo json_encode($callBack);
                        exit();
                    }
                    $oldversion = $prevRow['test_flag'];
                } else {
                    $oldversion = 1;
                }

                //组合发布API参数
                $cap_url = $cap."/pub/deployapi/?oldversion=".$oldversion."&newversion=".$row['test_flag']."&appname=".$repos[$row['repos_id']]['repos_name_other']."&reason=".$users[$row['add_user']]['realname']."提交代码".$users[$this->input->cookie('uids')]['realname']."测试"."&secret=7232275";
                //echo $cap_url;
                $con = file_get_contents($cap_url);
                //echo $con;
                $con_arr = json_decode($con, true);

                //获取PID
                $pid = 0;
                if ($con_arr['status']) {
                    $pid = $con_arr['pid'];
                } else {
                    $this->test->returntice($id);
                }
                if ($pid) {
                    //打队列
                    $sqs_url = $sqs."/?name=stateupdatev2&opt=put&data=";
                    $sqs_url .= $row['id']."|".$pid."|".$row['add_user']."|".$row['test_flag']."|".$row['issue_id']."|".$row['accept_user']."&auth=mypass123";
                    file_get_contents($sqs_url);
                }
            }
        } else {
            //打队列通知Worker部署分支的某个版本到测试环境
            $sqs_url = $sqs."/?name=tice&opt=put&data=";
            $sqs_url .= $row['id']."|".$row['add_user']."|".$row['repos_id']."|".$row['br']."|".$row['test_flag']."|".$row['issue_id']."|".$row['accept_user']."&auth=mypass123";
            file_get_contents($sqs_url);
        }

        $callBack['status'] = true;
        $callBack['message'] = '部署中……';
        echo json_encode($callBack);
    }

    /**
     * 测试通过
     */
    public function success() {

        //验证传入的ID是否有效
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_test', 'test', TRUE);
        $row = $this->test->fetchOne($id);
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '数据错误',
                'url' => '/'
            );
            echo json_encode($callBack);
            exit();
        }

        //不是受理本人不能操作
        if ($row['accept_user'] != $this->input->cookie('uids')) {
            $callBack = array(
                'status' => false,
                'message' => '只有受理人才有权限操作',
                'url' => '/issue/view/'.$row['issue_id']
            );
            echo json_encode($callBack);
            exit();
        }

        //载入必要的缓存文件
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }

        //读取配置文件
        $this->config->load('extension', TRUE);
        $cap = $this->config->item('cap', 'extension');
        $home = $this->config->item('home', 'extension');
        $home .= '/issue/view/'.$row['issue_id'];

        //在dev或者trunk提测的走的是CAP提供的API，所以审核通过的时候需要请求CAP的审核接口以便变更CAP中的记录状态
        if (!empty($row['br']) && ($row['br'] == 'dev' || $row['br'] == 'trunk')) {
            $cap_url = $cap."/pub/vertifyapi/?appname=".$repos[$row['repos_id']]['repos_name_other']."&version=".$row['trunk_flag']."&operate=4&secret=7232275";
            $this->load->library('curl');
            $res = $this->curl->get($cap_url);
            if ($res['httpcode'] == '200') {
                $this->test->changestat($row['id'], 3);
                $callBack = array(
                    'status' => true,
                    'message' => '操作成功',
                    'url' => '/issue/view/'.$row['issue_id']
                );
                echo json_encode($callBack);
            } else {
                $callBack = array(
                    'status' => false,
                    'message' => '请求CAP的接口失败，请再重试一次',
                    'url' => '/issue/view/'.$row['issue_id']
                );
                echo json_encode($callBack);
                exit();
            }
        } else { //走capistrano提测方法，直接修改数据库状态即可
            $this->test->changestat($row['id'], 3);
            $callBack = array(
                'status' => true,
                'message' => '操作成功',
                'url' => '/issue/view/'.$row['issue_id']
            );
            echo json_encode($callBack);
        }
    }

    /**
     * 测试不通过
     */
    public function fail() {

        //验证传入的ID是否有效
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_test', 'test', TRUE);
        $row = $this->test->fetchOne($id);
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '数据错误',
                'url' => '/'
            );
            echo json_encode($callBack);
            exit();
        }

        //不是受理本人不能操作
        if ($row['accept_user'] != $this->input->cookie('uids')) {
            $callBack = array(
                'status' => false,
                'message' => '不是受理人本人没有权限操作',
                'url' => '/issue/view/'.$row['issue_id']
            );
            echo json_encode($callBack);
            exit();
        }

        //载入必要的缓存文件
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }
        
        //读取配置文件
        $this->config->load('extension', TRUE);
        $cap = $this->config->item('cap', 'extension');
        $home = $this->config->item('home', 'extension');
        $home .= '/issue/view/'.$row['issue_id'];

        if (!empty($row['br']) && ($row['br'] == 'dev' || $row['br'] == 'trunk')) {
            $cap_url = $cap."/pub/vertifyapi/?appname=".$repos[$row['repos_id']]['repos_name_other']."&version=".$row['trunk_flag']."&operate=2&secret=7232275";
            $this->load->library('curl');
            $res = $this->curl->get($cap_url);
            if ($res['httpcode'] == '200') {
                $this->test->changestat($row['id'], '-3');
                
                $callBack = array(
                    'status' => true,
                    'message' => '操作成功',
                    'url' => '/issue/view/'.$row['issue_id']
                );
                echo json_encode($callBack);
            } else {
                $callBack = array(
                    'status' => false,
                    'message' => '请求CAP的接口失败，请再重试一次',
                    'url' => '/issue/view/'.$row['issue_id']
                );
                echo json_encode($callBack);
                exit();
            }
        } else { //走capistrano提测方法，直接修改数据库状态即可
            $this->test->changestat($row['id'], '-3');
            
            $callBack = array(
                'status' => true,
                'message' => '操作成功',
                'url' => '/issue/view/'.$row['issue_id']
            );
            echo json_encode($callBack);
        }
    }

    /**
     * 更改提测状态
     */
    public function change_tice() {
        $id = $this->uri->segment(3, 0);
        $status = $this->uri->segment(4, 0);
        $this->load->model('Model_test', 'test', TRUE);
        $row = $this->test->fetchOne($id);
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '数据错误',
                'url' => '/'
            );
            echo json_encode($callBack);
            exit();
        }

        if ($status == 'zhanyong') {
            $prevRow = $this->test->prev2($row['repos_id'], $row['test_flag']);
            if ($prevRow) {
                $callBack = array(
                    'status' => true,
                    'message' => '前面有测试任务正在使用，请稍后',
                    'url' => '/issue/view/'.$row['issue_id']
                );
                echo json_encode($callBack);
                exit();
            }
        }
        $flag = $this->test->changeTice($id, $status);
        if ($flag) {
            $callBack = array(
                'status' => true,
                'message' => '操作成功',
                'url' => '/issue/view/'.$row['issue_id']
            );
        } else {
            $callBack = array(
                'status' => true,
                'message' => '操作失败',
                'url' => '/issue/view/'.$row['issue_id']
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 发布到生产环境
     */
    public function cap_production() {

        //验证ID是否合法
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_test', 'test', TRUE);
        $row = $this->test->fetchOne($id);
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '数据错误',
                'url' => '/'
            );
            echo json_encode($callBack);
            exit();
        }

        //不是受理本人不能操作
        if ($row['accept_user'] != $this->input->cookie('uids')) {
            $callBack = array(
                'status' => false,
                'message' => '不是受理人本人没有权限操作',
                'url' => '/issue/view/'.$row['issue_id']
            );
            echo json_encode($callBack);
            exit();
        }

        //验证后续的样式是否提测，如果提测通过就不能再发当前这个版本，并标记，已覆盖
        $over_flag = $this->test->checkOver($row['id'], $row['repos_id'], $row['test_flag']);
        if ($over_flag) {
            $callBack = array(
                'status' => false,
                'message' => '后续版本已经上线，此版本已被覆盖',
                'url' => '/issue/view/'.$row['issue_id']
            );
            echo json_encode($callBack);
            exit();
        }

        //获取该版本库的前面的一个提测任务
        $prevRow = $this->test->prev2($row['repos_id'], $row['test_flag']);
        if ($prevRow) {
            $oldversion = $prevRow['trunk_flag'];
        } else {
            $oldversion = 1;
        }

        //
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }

        $this->config->load('extension', TRUE);
        $cap = $this->config->item('cap', 'extension');
        $sqs = $this->config->item('sqs', 'extension');

        //组合发布API参数
        $cap_url = $cap."/pub/deployapi/?oldversion=".$oldversion."&newversion=".$row['trunk_flag']."&appname=".$repos[$row['repos_id']]['repos_name_other']."&reason=".$users[$row['accept_user']]['realname']."上线&environment=formal&secret=7232275";
        //echo $cap_url;exit();
        $con = file_get_contents($cap_url);
        //echo $con;
        $con_arr = json_decode($con, true);

        //获取PID
        $pid = 0;
        if ($con_arr['status']) {
            $pid = $con_arr['pid'];
            //更改状态为发布中
            $this->test->cap($row['id']);
        }
        if ($pid) {
            //打队列
            $sqs_url = $sqs."/?name=capproduction&opt=put&data=";
            $sqs_url .= $row['id']."|".$pid."|".$row['add_user']."|".$row['trunk_flag']."|".$row['issue_id']."|".$row['accept_user']."|".$row['repos_id']."&auth=mypass123";
            file_get_contents($sqs_url);
        }

        $callBack = array(
            'status' => true,
            'message' => '操作成功',
            'url' => '/issue/view/'.$row['issue_id']
        );
        echo json_encode($callBack);
    }

    public function change_accept() {
        $name = $this->input->post("name");
        $name_arr = explode('-', $name);
        $issue_id = $name_arr[1];
        $test_id = $name_arr[2];
        $uid = $this->input->post("value");
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }


        $this->load->model('Model_test', 'test', TRUE);
        $this->test->update_accept($test_id, $uid);

        $username =  $users[$uid]['username'];
        echo 1;
    }

    /**
     * 获取代码库的分支
     */
    public function getbr() {

        //获取输入的参数
        $id = $this->uri->segment(3, 0);

        //带入代码库缓存文件
        if (file_exists(FCPATH.'/cache/repos.conf.php'))
            require FCPATH.'/cache/repos.conf.php';

        //验证输入的参数合法性
        if (!isset($repos[$id]))
            exit(json_encode(array('status' => false, 'error' => '输入的参数有误', 'code' => 3001)));

        //组合队列url
        $this->config->load('extension', TRUE);
        $sqs = $this->config->item('sqs', 'extension');
        $sqsUrl = $sqs."/?name=tree&opt=put&data=";
        $sqsUrl .= $id."&auth=mypass123";

        //发送消息给后端worker
        $this->load->library('curl');
        $res = $this->curl->get($sqsUrl);
        if ($res['httpcode'] != 200)
            exit(json_encode(array('status' => false, 'error' => '消息队列出现异常', 'code' => 1001)));

        //等待2秒中，让worker把查询结果写入缓存
        sleep(2);

        //获取生成的缓存文件并解析它
        $cacheFile = FCPATH.'/cache/repos_'.$id.'_tree';
        if (!file_exists($cacheFile))
            exit(json_encode(array('status' => false, 'error' => '文件不存在', 'code' => 1002)));

        $con = file_get_contents($cacheFile);
        if ($con)
        $conArr = unserialize($con);

        if (!$conArr)
            exit(json_encode(array('status' => false, 'error' => '格式异常', 'code' => 1003)));

        $str = '';
        foreach ($conArr as $key => $value) {
            $str .='<option value="'.$value.'">'.$value.'</option>';
        }
        $callBack = array('status' => true, 'output' => $str);
        echo json_encode($callBack);
    }
}
