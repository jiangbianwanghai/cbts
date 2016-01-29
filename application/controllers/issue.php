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
                'message' => '提交失败',
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
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->issue->fetchOne($id);
        $data['PAGE_TITLE'] = 'ISSUE-'.$data['row']['id'].' - '.$data['row']['issue_name'].' - 任务详情';
        $this->load->view('issue_view', $data);
    }

    /**
     * 我的任务列表
     */
    public function my() {
        $data['PAGE_TITLE'] = '我的任务列表';
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['rows'] = $this->issue->my();
        $this->load->view('issue_my', $data);
    }

    /**
     * 任务删除
     */
    public function del() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $feedback = $this->issue->del($id);
        if ($feedback) {
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
}