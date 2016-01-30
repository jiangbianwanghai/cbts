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
        $this->load->model('Model_task', 'task', TRUE);
        $data['rows'] = $this->task->my();
        $this->load->view('tice_task_my', $data);
    }
}