<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class project extends CI_Controller {

    public function add_ajax() {
    	$this->load->model('Model_project', 'project', TRUE);
        $post = array(
            'project_name' => $this->input->post('project_name'),
            'project_discription' => $this->input->post('project_discription'),
            'add_user' => $this->input->cookie('uids'),
            'add_time' => time()
        );
        $flag = $this->project->add($post);
        if ($flag['status']) {
            $this->project->cacheRefresh();
            $callBack = array(
                'status' => true,
                'message' => '提交成功',
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '提交失败'.$feedback['message'],
            );
        }
        echo json_encode($callBack);
    }
}