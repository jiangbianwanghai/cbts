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
        $this->load->model('Model_test', 'test', TRUE);
        $data['test'] = $this->test->listByIssue($id);
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->view('issue_view', $data);
    }

    /**
     * 我的任务列表
     */
    public function my() {
        $data['PAGE_TITLE'] = '我的任务列表';
        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');
        $offset = trim($this->uri->segment(3, 0));
        $this->load->model('Model_issue', 'issue', TRUE);
        $rows = $this->issue->my($offset, $config['per_page']);
        $data['rows'] = $rows['data'];
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/issue/my';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $this->load->view('issue_my', $data);
    }

    /**
     * 任务广场列表
     */
    public function plaza() {
        $data['PAGE_TITLE'] = '任务广场列表';
        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');
        $offset = trim($this->uri->segment(3, 0));
        $this->load->model('Model_issue', 'issue', TRUE);
        $rows = $this->issue->plaza($offset, $config['per_page']);
        $data['rows'] = $rows['data'];
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total_rows'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/issue/plaza';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $this->load->view('issue_plaza', $data);
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
                'message' => '删除成功',
                'url' => '/issue/my'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '删除失败',
                'url' => '/issue/my'
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 任务关闭
     */
    public function close() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $feedback = $this->issue->close($id);
        if ($feedback) {
            $callBack = array(
                'status' => true,
                'message' => '关闭成功',
                'url' => '/issue/my'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '关闭失败',
                'url' => '/issue/my'
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 任务已解决
     */
    public function resolve() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $feedback = $this->issue->resolve($id);
        if ($feedback) {
            $callBack = array(
                'status' => true,
                'message' => '解决成功',
                'url' => '/issue/my'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '解决失败',
                'url' => '/issue/my'
            );
        }
        echo json_encode($callBack);
    }

    /**
     * 编辑任务
     */
    public function edit() {
        $data['PAGE_TITLE'] = '编辑任务';
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $row = $this->issue->fetchOne($id);
        if ($row) {
            $data['row'] = $row;
            $this->load->view('issue_edit', $data);
        } else {
            echo '你查找的数据不存在.';
        }
    }

    /**
     * 异步更新
     */
    public function edit_ajax() {
        $this->load->model('Model_issue', 'issue', TRUE);
        $post = array(
            'id' => $this->input->post('id'),
            'issue_name' => $this->input->post('issue_name'),
            'url' => $this->input->post('issue_url'),
            'issue_summary' => $this->input->post('issue_summary')
        );
        $feedback = $this->issue->update($post);
        if ($feedback) {
            $callBack = array(
                'status' => true,
                'message' => '更新成功',
                'url' => '/issue/my'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '更新失败',
                'url' => '/issue/edit/'.$this->input->post('id')
            );
        }
        echo json_encode($callBack);
    }


    /**
     * 分析
     */
    public function analytics() {
        $data['PAGE_TITLE'] = '任务统计';
        $this->load->view('issue_analytics', $data);
    }
}