<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class test extends CI_Controller {

    /**
     * 添加表单
     */
    public function add() {
    	$data['PAGE_TITLE'] = '提交代码';
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->issue->fetchOne($id);
        if (!$data['row']) {
            exit("查询数据错误.");
        }
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        $this->load->view('test_add', $data);
    }

    /**
     * 添加入库
     */
    public function add_ajax() {
    	$this->load->model('Model_test', 'test', TRUE);
        $post = array(
            'issue_id' => $this->input->post('issue_id'),
            'repos_id' => $this->input->post('repos_id'),
            'test_flag' => $this->input->post('test_flag'),
            'test_summary' => $this->input->post('test_summary')
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
     * 代码库列表
     */
    public function task_list() {
        $data['PAGE_TITLE'] = '提测列表';
        $this->load->model('Model_task', 'task', TRUE);
        $data['rows'] = $this->task->rows();
        $this->load->view('tice_task_list', $data);
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
        echo json_encode($callBack);
    }

    /**
     * 提测详情
     */
    public function view() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_test', 'test', TRUE);
        $row = $this->test->fetchOne($id);
        echo '<p>'.$row['test_summary'].'</p>';
    }

    /**
     * 提测广场列表
     */
    public function plaza() {
        $data['PAGE_TITLE'] = '提测广场列表';
        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');
        $offset = trim($this->uri->segment(3, 0));
        $this->load->model('Model_test', 'test', TRUE);
        $rows = $this->test->plaza($offset, $config['per_page']);
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
        $config['base_url'] = '/test/plaza';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $this->load->view('test_plaza', $data);
    }

    /**
     * 分析
     */
    public function analytics() {
        $data['PAGE_TITLE'] = '测试统计';
        $this->load->view('issue_analytics', $data);
    }
}