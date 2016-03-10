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
        $data['PAGE_TITLE'] = '个人资料';
        $id = $this->uri->segment(3, 0);
        $data['id'] = $id;
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            if (!isset($users[$id])) {
                exit('你查找的数据不存在');
            }
            $data['users'] = $users;
        }

        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }

        //获取创建的任务列表
        $id = trim($this->uri->segment(3, 0));
        $this->load->model('Model_issue', 'issue', TRUE);
        $rows = $this->issue->profile($id, 0, 100);
        $data['issue_total'] = $rows['total_rows'];
        $data['issue'] = $rows['data'];

        //获取创建的提测列表
        $this->load->model('Model_test', 'test', TRUE);
        $rows = $this->test->profile($id, 0, 100);
        $data['test_total'] = $rows['total_rows'];
        $data['test'] = $rows['data'];

        //我的按天统计任务量
        $stackedMyIssue = $this->issue->stacked($id);
        if ($stackedMyIssue) {
            $stackedMyIssueStr = "[";
            foreach ($stackedMyIssue as $key => $value) {
                $stackedMyIssueStr .= "{ y: '".$value['perday']."', a: ".$value['close'].", b: ".$value['able']." },";
            }
            $stackedMyIssueStr .= "]";
        }
        $data['stackedMyIssueStr'] = $stackedMyIssueStr;

        //我的按天统计提测量
        $stackedMyTest = $this->test->stacked($id);
        if ($stackedMyTest) {
            $stackedMyTestStr = "[";
            foreach ($stackedMyTest as $key => $value) {
                $stackedMyTestStr .= "{ y: '".$value['perday']."', a: ".$value['other'].", b: ".$value['no']." },";
            }
            $stackedMyTestStr .= "]";
        }
        $data['stackedMyTestStr'] = $stackedMyTestStr;

        $this->load->view('conf_users_profile', $data);
    }
}