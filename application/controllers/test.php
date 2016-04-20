<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class test extends CI_Controller {

    /**
     * 添加表单
     */
    public function add() {
    	$data['PAGE_TITLE'] = '提交代码';
        $aclUsers = array('71','72','69','60','73','74','20', '1');//只允许吕云毅，彭明明，张智龙，李奎，师旭
        if (!in_array($this->input->cookie('uids'), $aclUsers))
            exit('只有各个绩效圈的Leader才有提交测试的权限，请联系@吕云毅，@彭明明，@张智龙，@李奎，@师旭');
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->issue->fetchOne($id);
        if (!$data['row']) {
            exit("查询数据错误.");
        }
        //已经解决的任务不能再次添加提测信息
        $resolve = $this->issue->checkResolve($id);
        if ($resolve) {
            exit('已经解决的任务不能再次添加提测信息~');
        }
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->view('test_add', $data);
    }

    /**
     * 添加入库
     */
    public function add_ajax() {
    	$this->load->model('Model_test', 'test', TRUE);
        //提测版本号不能为空
        $test_flag = $this->input->post('test_flag');
        if ($test_flag <= 0) {
            $callBack = array(
                'status' => false,
                'message' => '提测版本号不能为空',
                'url' => '/test/add/'.$this->input->post('issue_id')
            );
            echo json_encode($callBack);
            exit();
        }
        //提测版本号不能已经存在
        $checkTestFlag = $this->test->checkFlag($this->input->post('repos_id'), $this->input->post('br'), $this->input->post('test_flag'));
        if (!$checkTestFlag) {
            $callBack = array(
                'status' => false,
                'message' => '提测版本已经存在',
                'url' => '/test/add/'.$this->input->post('issue_id')
            );
            echo json_encode($callBack);
            exit();
        }
        $post = array(
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
            //发RTX消息提醒受理人
            if (file_exists('./cache/repos.conf.php')) {
                require './cache/repos.conf.php';
            }
            if (file_exists('./cache/users.conf.php')) {
                require './cache/users.conf.php';
            }
            $this->config->load('extension', TRUE);
            $home = $this->config->item('home', 'extension');
            $home = $home."/issue/view/".$this->input->post('issue_id');

            $subject = $users[$this->input->cookie('uids')]['realname']."提醒你：".$repos[$this->input->post('repos_id')]['repos_name']."(".$this->input->post('test_flag').")请求提测";
            $this->rtx($users[$this->input->post('accept_user')]['username'],$home,$subject);

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
     *  我的任务列表
     */
    public function my() {
        $data['PAGE_TITLE'] = '我的提测列表';
        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');
        $offset = trim($this->uri->segment(3, 0));
        $this->load->model('Model_test', 'test', TRUE);
        $rows = $this->test->my($offset, $config['per_page']);
        $data['rows'] = $rows['data'];
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/test/my';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $this->load->view('test_my', $data);
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
     * 提测广场列表
     */
    public function plaza() {
        $data['PAGE_TITLE'] = '提测广场列表';

        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');

        //页码
        $offset = $this->uri->segment(7, 0);

        //阶段
        $rank = $this->uri->segment(3, 'dev');

        //任务状态
        $state = $this->uri->segment(4, 'wait');

        //申请角色
        $add_user = $this->uri->segment(5, 'all');

        //受理角色
        $accept_user = $this->uri->segment(6, 'all');

        //读取数据
        $this->load->model('Model_test', 'test', TRUE);

        $rows = $this->test->plaza($add_user, $accept_user, $rank, $state, $offset, $config['per_page']);
        $data['rows'] = $rows['data'];
        $data['total_rows'] = $rows['total_rows'];

        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/test/plaza/'.$rank.'/'.$state.'/'.$add_user.'/'.$accept_user;
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();

        $data['offset'] = $offset;
        $data['rank'] = $rank;
        $data['state'] = $state;
        $data['add_user'] = $add_user;
        $data['accept_user'] = $accept_user;

        $this->load->view('test_plaza', $data);
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
     * 我的待测
     */
    public function todo() {
        $data['PAGE_TITLE'] = '我的待测';
        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');
        $offset = trim($this->uri->segment(3, 0));
        $this->load->model('Model_test', 'test', TRUE);
        $rows = $this->test->todo($offset, $config['per_page']);
        $data['rows'] = $rows['data'];
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/test/todo';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $this->load->view('test_todo', $data);
    }

    /**
     * 分析
     */
    public function analytics() {

        $data = array(
            'PAGE_TITLE' => '', //页面标题
            'users' => array(), //用户缓存文件
            'repos' => array(), //代码库缓存文件
            'rankByUsers' => array(), //提测量人员排行
            'rankByUsersTotalNum' => 0, //提测量人员排行总记录数
            'rankByRepos' => array(), //提测量代码库排行
            'rankByReposTotalNum' => 0, //提测量代码库排行总记录数
            'day' => 0 //筛选天数
        );

        $leftTime = $data['leftTime'] = strtotime(date("Y-m-d", time()));
        $rightTime = $data['rightTime'] = strtotime(date("Y-m-d", strtotime("+1 day")));

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
        
        $data['PAGE_TITLE'] = '测试统计';

        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }

        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }

        $this->load->model('Model_test', 'test', TRUE);

        //提测量人员排行总记录数
        $data['rankByUsers'] = $this->test->rankByUsers($leftTime, $rightTime);
        if ($data['rankByUsers']) {
            foreach ($data['rankByUsers'] as $key => $value) {
                $data['rankByUsersTotalNum'] += $value['num'];
            }
        }

        //统计代码库提测量排行
        $data['rankByRepos'] = $this->test->rankByRepos($leftTime, $rightTime);
        if ($data['rankByRepos']) {
            foreach ($data['rankByRepos'] as $key => $value) {
                $data['rankByReposTotalNum'] += $value['num'];
            }
        }

        $this->load->view('test_analytics', $data);
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
                $subject = $users[$this->input->cookie('uids')]['realname']."提醒你：".$repos[$row['repos_id']]['repos_name']."(".$row['test_flag'].")测试通过，会择机发布到线上";
                $this->rtx($users[$row['add_user']]['username'],$home,$subject);
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
            $subject = $users[$this->input->cookie('uids')]['realname']."提醒你：".$repos[$row['repos_id']]['repos_name']."(".$row['test_flag'].")测试通过，会择机发布到线上";
            $this->rtx($users[$row['add_user']]['username'],$home,$subject);
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
                $subject = $users[$this->input->cookie('uids')]['realname']."提醒你：".$repos[$row['repos_id']]['repos_name']."(".$row['test_flag'].")测试不通过，并驳回了";
                $this->rtx($users[$row['add_user']]['username'],$home,$subject);
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
            $subject = $users[$this->input->cookie('uids')]['realname']."提醒你：".$repos[$row['repos_id']]['repos_name']."(".$row['test_flag'].")测试不通过，并驳回了";
            $this->rtx($users[$row['add_user']]['username'],$home,$subject);
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
        $this->config->load('extension', TRUE);
        $home = $this->config->item('home', 'extension');
        $url = $home."/issue/view/".$issue_id;
        $subject = $users[$this->input->cookie('uids')]['realname']."指派了一个提测给你";
        $this->rtx($username,$url,$subject);
        echo 1;
    }

    public function getbr() {
        $reposId = $this->uri->segment(3, 0);
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
        }

        $merge = $repos[$reposId]['merge'];

        if ($reposId) {
            if ($merge) {
                //打队列
                $this->config->load('extension', TRUE);
                $sqs = $this->config->item('sqs', 'extension');
                $sqs_url = $sqs."/?name=getbr&opt=put&data=";
                $sqs_url .= $reposId."&auth=mypass123";
                file_get_contents($sqs_url);
                sleep(3);
                $file = "/usr/local/nginx/html/cbts/cache/repos_br_".$reposId;
                if (file_exists($file)) {
                    $con = file_get_contents($file);
                    $conArr = array_filter(explode("\n", $con));
                    $str = '';
                    if ($conArr) {
                        foreach ($conArr as $key => $value) {
                            $str .='<option value="'.str_replace('/', '', $value).'">branches/'.$value.'</option>';
                        }
                        echo $str;
                    }
                }
            } else {
                if ($reposId == 42) {
                    echo '<option value="branches">branches</option>';
                } else {
                    echo '<option value="trunk">trunk</option>';
                }
            }
        }
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