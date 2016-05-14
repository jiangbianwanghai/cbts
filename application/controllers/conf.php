<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class conf extends CI_Controller {

    /**
     * 代码库添加表单
     */
    public function repos() {
    	$data['PAGE_TITLE'] = '添加代码库';
        $this->load->view('conf_repos_form', $data);
    }

    /**
     * 代码库添加入库
     */
    public function repos_add() {
    	$this->load->model('Model_repos', 'repos', TRUE);
        $post = array(
            'repos_name' => $this->input->post('repos_name'),
            'repos_name_other' => $this->input->post('repos_name_other'),
            'repos_url' => $this->input->post('repos_url'),
            'repos_summary' => $this->input->post('repos_summary'),
            'merge' => $this->input->post('merge')
        );
        $feedback = $this->repos->add($post);
        if ($feedback['status']) {
            $this->repos->cacheRefresh();
            $callBack = array(
                'status' => true,
                'message' => '提交成功',
                'url' => '/conf/repos_list'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '提交失败'.$feedback['message'],
                'url' => '/conf/repos'
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 代码库列表
     */
    public function repos_list() {
        $data['PAGE_TITLE'] = '添加代码库';
        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');
        $offset = trim($this->uri->segment(3, 0));
        $this->load->model('Model_repos', 'repos', TRUE);
        $rows = $this->repos->rows($offset, $config['per_page']);
        $data['rows'] = $rows['data'];
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/conf/repos_list';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();

        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }

        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        
        $this->load->view('conf_repos_list', $data);
    }

    /**
     * 删除代码库
     */
    public function repos_del() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_repos', 'repos', TRUE);
        $feedback = $this->repos->del($id);
        if ($feedback) {
            $this->repos->cacheRefresh();
            $callBack = array(
                'status' => true,
                'message' => '提交成功'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '提交失败'
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 编辑代码库面板
     */
    public function repos_edit() {
        $data['PAGE_TITLE'] = '编辑代码库';
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_repos', 'repos', TRUE);
        $row = $this->repos->fetchOne($id);
        if ($row) {
            $data['row'] = $row;
            $this->load->view('conf_repos_edit', $data);
        } else {
            echo '你查找的数据不存在.';
        }
    }

    /**
     * 代码库更新入库
     */
    public function repos_update() {
        $this->load->model('Model_repos', 'repos', TRUE);
        $post = array(
            'id' => $this->input->post('id'),
            'repos_name' => $this->input->post('repos_name'),
            'repos_name_other' => $this->input->post('repos_name_other'),
            'repos_url' => $this->input->post('repos_url'),
            'repos_summary' => $this->input->post('repos_summary'),
            'merge' => $this->input->post('merge')
        );
        $feedback = $this->repos->update($post);
        if ($feedback) {
            $this->repos->cacheRefresh();
            $callBack = array(
                'status' => true,
                'message' => '更新成功',
                'url' => '/conf/repos_list'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '更新失败',
                'url' => '/conf/repos_edit/'.$this->input->post('id')
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 个人资料面板
     */
    public function profile() {

        //设置页面标题
        $data['PAGE_TITLE'] = '个人资料';

        //验证数据是否存在
        $id = $this->uri->segment(3, 0);
        $data['id'] = $id;
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            if (!isset($users[$id])) {
                exit('你查找的数据不存在');
            }
            $data['users'] = $users;
        }

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

        //载入代码库缓存文件
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }

        $data['role'] = $users[$id]['role'];

        //获取创建的任务列表
        $this->load->model('Model_issue', 'issue', TRUE);
        $rows = $this->issue->profile($id, $data['role'], $leftTime, $rightTime, 0, 100);
        $data['issue_total'] = $rows['total_rows'];
        $data['issue'] = $rows['data'];

        //获取创建的提测列表
        $this->load->model('Model_test', 'test', TRUE);
        $rows = $this->test->profile($id, $data['role'], $leftTime, $rightTime, 0, 100);
        $data['test_total'] = $rows['total_rows'];
        $data['test'] = $rows['data'];

        if ($users[$id]['role'] == 1) {
            //我的按天统计任务量
            $stackedMyIssue = $this->issue->stackedByQa($id, $leftTime, $rightTime);
            $stackedMyIssueStr = '';
            if ($stackedMyIssue) {
                $stackedMyIssueStr = "[";
                foreach ($stackedMyIssue as $key => $value) {
                    $stackedMyIssueStr .= "{ y: '".$value['perday']."', a: ".$value['close'].", b: ".$value['able']." },";
                }
                $stackedMyIssueStr .= "]";
            }
            $data['stackedMyIssueStr'] = $stackedMyIssueStr;

            //我的按天统计提测量
            $stackedMyTest = $this->test->stackedByQa($id, $leftTime, $rightTime);
            $stackedMyTestStr = '';
            if ($stackedMyTest) {
                $stackedMyTestStr = "[";
                foreach ($stackedMyTest as $key => $value) {
                    $stackedMyTestStr .= "{ y: '".$value['perday']."', a: ".$value['other'].", b: ".$value['no']." },";
                }
                $stackedMyTestStr .= "]";
            }
            $data['stackedMyTestStr'] = $stackedMyTestStr;
        }

        if ($users[$id]['role'] == 2) {
            //我的按天统计任务量
            $stackedMyIssue = $this->issue->stacked($id, $leftTime, $rightTime);
            $stackedMyIssueStr = '';
            if ($stackedMyIssue) {
                $stackedMyIssueStr = "[";
                foreach ($stackedMyIssue as $key => $value) {
                    $stackedMyIssueStr .= "{ y: '".$value['perday']."', a: ".$value['close'].", b: ".$value['able']." },";
                }
                $stackedMyIssueStr .= "]";
            }
            $data['stackedMyIssueStr'] = $stackedMyIssueStr;

            //我的按天统计提测量
            $stackedMyTest = $this->test->stacked($id, $leftTime, $rightTime);
            $stackedMyTestStr = '';
            if ($stackedMyTest) {
                $stackedMyTestStr = "[";
                foreach ($stackedMyTest as $key => $value) {
                    $stackedMyTestStr .= "{ y: '".$value['perday']."', a: ".$value['other'].", b: ".$value['no']." },";
                }
                $stackedMyTestStr .= "]";
            }
            $data['stackedMyTestStr'] = $stackedMyTestStr;
        }

        $this->load->view('conf_users_profile', $data);
    }

    /*
     获取代码库列表
    */
    public function repos_view(){

        //获取输入参数
        $id = $this->uri->segment(3, 0);

        //读取数据
        $this->load->model('Model_repos', 'repos', TRUE);
        $row = $this->repos->fetchOne($id);

        //验证参数的合法性
        if (!$row) {
            $output = array('status' => false, 'message' => '输入参数有误');
            echo json_encode($output);
        }

        //载入用户缓存文件
        if (file_exists(FCPATH.'/cache/users.conf.php')) {
            require FCPATH.'/cache/users.conf.php';
        }

        //输出
        $merge = array(0 => '不需要', 1 => '需要');
        $output = array(
            'status' => true,
            'message' => array(
                'repos_name' => $row['repos_name'],
                'repos_name_other' => $row['repos_name_other'],
                'repos_url' => $row['repos_url'],
                'repos_summary' => $row['repos_summary'],
                'merge' => $merge[$row['merge']],
                'add_user' => $users[$row['add_user']]['realname'],
                'add_time' => $row['add_time'] ? date('Y/m/d H:i:s', $row['add_time']) : 'N/A',
                'last_user' => $users[$row['add_user']]['realname'],
                'last_time' => $row['last_time'] ? date('Y/m/d H:i:s', $row['last_time']) : 'N/A'
            )
        );

        echo json_encode($output);
    }
}