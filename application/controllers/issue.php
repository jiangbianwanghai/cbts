<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class issue extends CI_Controller {

    /**
     * 添加表单
     */
    public function form() {
    	$data['PAGE_TITLE'] = '申请提测';
        $this->load->view('issue_form', $data);
    } 

    /**
     * 任务详情
     */
    public function view() {
    	$this->load->helper('url');
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->issue->fetchOne($id);
        $data['PAGE_TITLE'] = 'ISSUE-'.$data['row']['id'].' - '.$data['row']['issue_name'].' - 任务详情';
        $this->load->view('issue_view', $data);
    }
}