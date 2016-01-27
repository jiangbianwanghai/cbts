<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tice extends CI_Controller {

    /**
     * 添加表单
     */
    public function task() {
    	$data['PAGE_TITLE'] = '申请提测';
        $this->load->view('tice_task_form', $data);
    }

    /**
     * 添加入库
     */
    public function task_add() {
    	$this->load->model('Model_task', 'task', TRUE);
        $post = array(
            'task_name' => $this->input->post('task_name'),
            'task_url' => $this->input->post('task_url'),
            'task_summary' => $this->input->post('task_summary')
        );
        $feedback = $this->task->add($post);
        if ($feedback['status']) {
            $callBack = array(
                'status' => true,
                'message' => '提交成功',
                'url' => '/tice/task_list'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '提交失败',
                'url' => '/tice/task'
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