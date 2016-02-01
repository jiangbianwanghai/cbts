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
            'repos_summary' => $this->input->post('repos_summary')
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
        $page = trim($this->uri->segment(3, 1));
        $this->load->model('Model_repos', 'repos', TRUE);
        $rows = $this->repos->rows($page, $config['per_page']);
        $data['rows'] = $rows['data'];
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $page;
        $config['base_url'] = '/conf/repos_list';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();

        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
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
            'repos_summary' => $this->input->post('repos_summary')
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
}