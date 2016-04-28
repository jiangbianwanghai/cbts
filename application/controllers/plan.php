<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan extends CI_Controller {

    /**
     * 项目ID
     */
    private $_projectId = 0;

    /**
     * 项目缓存数组
     */
    private $_projectCache = array();

    public function __construct() {

        parent::__construct();

        //载入项目缓存文件
        if (file_exists(FCPATH.'/cache/project.conf.php')) {
            require FCPATH.'/cache/project.conf.php';
            $this->_projectCache = $project;
        } else {
            show_error('项目缓存文件载入失败，请联系<a href="mailto:webmaster@jiangbianwanghai.com">江边望海</a>。', 500, '错误');
        }

        //验证Cookie中的项目ID是否合法
        $projectId = $this->input->cookie('projectId');
        if (isset($project[$projectId])) {
            $this->_projectId = $projectId;
        } else {
            show_error('无法获取项目信息，请 <a href="/">返回首页</a> 选择项目', 500, '错误');
        }
    }

    /**
     * 默认列表控制器
     */
    public function index() {

        //页面标题初始化
        $data['PAGE_TITLE'] = '计划列表';

        $this->load->model('Model_plan', 'plan', TRUE);
        $this->load->model('Model_project', 'project', TRUE);

        //获取ID
        $data['planId'] = $this->input->get('planId', TRUE);

        if ($data['planId']) {

            //验证ID是否合法
            $data['currPlan'] = $this->plan->fetchOne($data['planId']);
            if (!$data['currPlan']) {
                show_error('参数错误，无此数据！<a href="/">去首页</a>', 500, '错误');
            }

            //设定Cookie
            //根据project_id写md5值
            $currProject = $this->project->fetchOne($data['currPlan']['project_id']);
            if ($this->input->cookie('projectId') != $currProject['md5']) {
                $this->input->set_cookie('projectId', $currProject['md5'], 86400*15);
            }

            //获取项目ID
            $projectId = $data['currPlan']['project_id'];

            //获取计划列表
            $data['planFolder'] = $this->plan->planFolder($projectId);

        } else {

            //获取项目ID
            $projectId = $this->_projectCache[$this->_projectId]['id'];

            //获取计划列表和默认计划ID
            $data['planFolder'] = $this->plan->planFolder($projectId);
            if ($data['planFolder']) {
                foreach ($data['planFolder'] as $key => $value) {
                    $data['planId'] = $value['id'];
                    break;
                }
            }

            //读取默认计划
            $data['currPlan'] = $this->plan->fetchOne($data['planId']);

        }

        //读取任务
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['rows'] = $this->issue->listByPlan($data['planId'], $projectId);

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $data['workflow'] = $this->config->item('workflow', 'extension');

        //载入助手
        $this->load->helper('timediff');

        $this->load->view('plan_index', $data);
    }

    /**
     * 新增计划接口
     */
    public function add_ajax() {
        $this->load->library('form_validation');
        if ($this->form_validation->run() == FALSE) {
            $callBack = array(
                'status' => false,
                'message' => validation_errors(),
                'url' => '/plan'
            );
            echo json_encode($callBack);
            exit();
        }
    	$this->load->model('Model_plan', 'plan', TRUE);
    	if (file_exists('./cache/project.conf.php'))
    		require './cache/project.conf.php';
        $post = array(
        	'project_id' => $this->_projectCache[$this->_projectId]['id'],
            'plan_name' => $this->input->post('plan_name'),
            'plan_discription' => $this->input->post('plan_discription'),
            'startime' => strtotime($this->input->post('startime')),
            'endtime' => strtotime($this->input->post('endtime')),
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