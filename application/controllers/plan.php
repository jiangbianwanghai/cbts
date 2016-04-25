<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan extends CI_Controller {

	private $projectId = '';
	private $project = '';

	public function __construct() {
		parent::__construct();
		$this->_projectId = $this->input->cookie('projectId');
		if (!$this->_projectId) {
			exit('无法获取项目信息，请 <a href="/">返回首页</a> 选择项目');
		}
		if (file_exists('./cache/project.conf.php')) {
			require './cache/project.conf.php';
			$this->_project = $project;
		}
	}

    /**
     * 默认列表控制器
     */
    public function index() {
        $data['PAGE_TITLE'] = '计划列表';
        $this->load->model('Model_plan', 'plan', TRUE);
        $row = $this->plan->planFolder($this->_project[$this->_projectId]['id']);
        $data['planFolder'] = $row;
        $data['planId'] = $this->input->get('planId', TRUE);
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['rows'] = $this->issue->listByPlan($data['planId'], $this->_project[$this->_projectId]['id']);
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $this->load->view('plan_index', $data);
    }

    /**
     * 新增计划接口
     */
    public function add_ajax() {
    	$this->load->model('Model_plan', 'plan', TRUE);
    	if (file_exists('./cache/project.conf.php'))
    		require './cache/project.conf.php';
        $post = array(
        	'project_id' => $this->_project[$this->_projectId]['id'],
            'plan_name' => $this->input->post('plan_name'),
            'plan_discription' => $this->input->post('plan_discription'),
            'add_user' => $this->input->cookie('uids'),
            'add_time' => time()
        );
        $flag = $this->plan->add($post);
        if ($flag['status']) {
            $callBack = array(
                'status' => true,
                'message' => '提交成功',
                'url' => '/plan'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '提交失败'.$feedback['message'],
                'url' => '/plan'
            );
        }
        echo json_encode($callBack);
    }
}