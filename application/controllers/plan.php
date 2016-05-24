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

        //如果没有项目ID的Cookie就默认一个项目ID
        $projectId = $this->input->cookie('projectId');
        if ($projectId) {
            if (isset($project[$projectId]))
                $this->_projectId = $projectId;
            else
                show_error('无法获取项目信息（计划，任务，BUG，提测四个模块操作前先在页面顶部选择项目），请 <a href="/">返回首页</a> 选择项目', 500, '错误');
        } else {
            $currProject = end($project);
            $this->_projectId = $currProject['md5'];
            $this->input->set_cookie('projectId', $currProject['md5'], 86400*15);
        }
    }

    /**
     * 默认列表控制器
     */
    public function index() {

        //页面标题初始化
        $data['PAGE_TITLE'] = '计划列表';

        $data['flow'] = $this->uri->segment(3, 0);
        $data['taskType'] = $this->uri->segment(4, 0);

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

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $data['workflow'] = $this->config->item('workflow', 'extension');
        $data['workflowfilter'] = $this->config->item('workflowfilter', 'extension');
        $data['tasktype'] = $this->config->item('tasktype', 'extension');

        //转移筛选值
        $flow = '-1';
        if (isset($data['workflowfilter'][$data['flow']])) {
            $flow = $data['workflowfilter'][$data['flow']]['id'];
        }
        $taskType = 0;
        if (isset($data['tasktype'][$data['taskType']])) {
            $taskType = $data['tasktype'][$data['taskType']];
        }

        //读取任务
        $this->load->model('Model_issue', 'issue', TRUE);
        $rows = $this->issue->listByPlan($data['planId'], $projectId, $flow, $taskType, 100);
        $data['total'] = $rows['total'];
        $data['rows'] = $rows['data'];

        //根据任务
        $data['team'] = array();
        if ($rows['data']) {
            $ids = array();
            foreach ($rows['data'] as $key => $value) {
                $ids[]= $value['id'];
            }
            $star = $this->issue->starByBugId($ids);
            if ($star) {
                foreach ($star as $key => $value) {
                    $data['star'][$value['star_id']] = $value['star_id'];
                }
            }

            //获取计划团队人员
            $this->load->model('Model_accept', 'accept', TRUE);
            $data['team'] = $this->accept->getTeamByIssue($ids);
        }

        //载入助手
        $this->load->helper('timediff');

        //载入缓存文件
        if (file_exists(FCPATH.'/cache/users.conf.php')) {
            require FCPATH.'/cache/users.conf.php';
            $data['users'] = $users;
        }

        $this->load->view('plan_index', $data);
    }

    /**
     * 新增计划接口
     */
    public function add_ajax() {

        //验证是否有编辑的权限
        $this->load->model('Model_plan', 'plan', TRUE);
        $planId = $this->input->post('plan_id');
        if ($planId) {
            $row = $this->plan->fetchOne($planId);
            if (!$row) {
                exit(json_encode(array('status' => false, 'error' => '只有创建人才可以编辑', 'code' => 3001)));
            }
        }

        //验证结束时间不能小于开始时间
        if (strtotime($this->input->post('endtime')) <= strtotime($this->input->post('startime')))
            exit(json_encode(array('status' => false, 'error' => '结束时间不能小于等于开始时间', 'code' => 3001)));
    	
        //准备数据
        $post = array(
        	'project_id' => $this->_projectCache[$this->_projectId]['id'],
            'plan_name' => $this->input->post('plan_name'),
            'plan_discription' => $this->input->post('plan_discription'),
            'startime' => strtotime($this->input->post('startime')),
            'endtime' => strtotime($this->input->post('endtime')),
            'add_user' => $this->input->cookie('uids'),
            'add_time' => time()
        );

        if ($planId) {
            $num = $this->plan->checkPlanName($this->input->post('plan_name'), $this->_projectCache[$this->_projectId]['id']);
            if ($num > 1)
                exit(json_encode(array('status' => false, 'error' => '计划名称不能与已有的计划重复', 'code' => 3001)));
            $flag = $this->plan->edit($planId, $post);
        } else {
            $num = $this->plan->checkPlanName($this->input->post('plan_name'), $this->_projectCache[$this->_projectId]['id']);
            if ($num)
                exit(json_encode(array('status' => false, 'error' => '计划已经存在，无需添加', 'code' => 3001)));
            $flag = $this->plan->add($post);
        }
        
        if ($flag)
            $callBack = array('status' => true, 'message' => '操作成功');
        else
            $callBack = array('status' => false, 'error' => '操作失败', 'code' => 3001);
        echo json_encode($callBack);
    }

    /**
     * 获取整个计划的提测成功率
     */
    public function rate() {

        //获取传入的参数
        $planId = $this->uri->segment(3, 0);

        //获取项目ID并验证输入参数的合法性
        $this->load->model('Model_plan', 'plan', TRUE);
        $currPlan = $this->plan->fetchOne($planId, 'project_id');

        if (!$currPlan) {
            exit('输入参数有误');
        }

        //获取计划中的任务
        $this->load->model('Model_issue', 'issue', TRUE);
        $issueRows = $this->issue->listByPlan($planId, $currPlan['project_id'], 7, 0, 100, 0);
        if (!$issueRows['total']) {
            exit('任务未完成，无法参与计算');
        }

        //循环计算每个任务的提测率
        $rateArr = array();
        $this->load->model('Model_test', 'test', TRUE);
        //组合issue ID
        foreach ($issueRows['data'] as $key => $val) {
            $issueIdArr[] = $val['id'];
        }
        $testRows = $this->test->rowsOfPlan($issueIdArr);
        $maxTest = 0;
        $testIdArr = array();
        //计算每个任务的提测成功率
        if ($testRows) {
            foreach ($testRows as $key => $value) {
                if (isset($testIdArr[$value['issue_id']][$value['repos_id']])) {
                    $testIdArr[$value['issue_id']][$value['repos_id']] += 1;
                } else {
                    $testIdArr[$value['issue_id']][$value['repos_id']] = 1;
                }
            }
            if ($testIdArr) {
                foreach ($testIdArr as $key => $value) {
                    $rateArr[$key] = 1/max($value);
                }
            }
        }

        //输出整个计划的提测率
        if ($rateArr) {
            $rateTotal = 0;
            foreach ($rateArr as $key => $value) {
                $rateTotal += $value;
            }
            echo sprintf("%.2f", $rateTotal/count($rateArr));
        } else {
            echo '无提测数据用于计算';
        }
    }

    public function get_info() {

        //获取传入的参数
        $planId = $this->uri->segment(3, 0);

        //获取项目ID并验证输入参数的合法性
        $this->load->model('Model_plan', 'plan', TRUE);
        $currPlan = $this->plan->fetchOne($planId);

        if (!$currPlan)
            exit(json_encode(array('status' => false, 'error' => '输入的参数有误', 'code' => 3001)));

        $currPlan['startime'] && $currPlan['startime'] = date("Y/m/d H:i", $currPlan['startime']);
        $currPlan['endtime'] && $currPlan['endtime'] = date("Y/m/d H:i", $currPlan['endtime']);
        $callBack = array('status' => true, 'output' => $currPlan);
        echo json_encode($callBack);
    }

    /**
     * 删除计划
     */
    public function del() {

        //获取传入的参数
        $planId = $this->uri->segment(3, 0);

        //获取项目ID并验证输入参数的合法性
        $this->load->model('Model_plan', 'plan', TRUE);
        $currPlan = $this->plan->fetchOne($planId);

        if (!$currPlan)
            exit(json_encode(array('status' => false, 'error' => '输入的参数有误', 'code' => 3001)));

        //只有计划创建人才能操作
        if ($currPlan['add_user'] != $this->input->cookie('uids'))
            exit(json_encode(array('status' => false, 'error' => '只有计划创建人才能操作', 'code' => 3001)));

        //计划下没有任务了才可以删除计划
        $this->load->model('Model_issue', 'issue', TRUE);
        $count = $this->issue->countByPlan($planId);

        if ($count)
            exit(json_encode(array('status' => false, 'error' => '请将所有任务移出计划，再删除', 'code' => 3001)));

        $flag = $this->plan->del($planId);

        if ($flag)
            $callBack = array('status' => true, 'message' => '操作成功');
        else
            $callBack = array('status' => false, 'error' => '操作失败', 'code' => 3001);
        echo json_encode($callBack);
    }

    /**
     * 在计划间移动任务。（只有未开发的任务可以移动）
     */
    public function move_issue() {

        //验证传入参数是否合法
        $this->load->library('form_validation');
        if ($this->form_validation->run() == FALSE)
            exit(json_encode(array('status' => false, 'error' => validation_errors(), 'code' => 3001)));

        //获取传入参数
        $planId = $this->input->post('planId');
        $issueId = $this->input->post('issueId');

        //验证传入的任务ID是否符合移动的条件
        $this->load->model('Model_issue', 'issue', TRUE);
        $filter = array(
            array('sKey' => 'id', 'sValue' => $issueId),
            array('sKey' => 'status', 'sValue' => '1')
        );
        $rows = $this->issue->search($filter, 'id, workflow', true);
        $fitId = array();
        if ($rows) {
            foreach ($rows as $key => $value) {
                if ($value['workflow'] == 0)
                    $fitId[] = $value['id'];
            }
        }

        //得到符合条件的Id
        if ($fitId) {
            foreach ($fitId as $key => $value) {
                $this->issue->change(array('plan_id' => $planId, 'last_user' => $this->input->cookie('uids'), 'last_time' => time()), array('id' => $value));
            }
            exit(json_encode(array('status' => true, 'message' => count($fitId).'个任务被成功移动')));
        } else {
            exit(json_encode(array('status' => false, 'error' => '没有符合条件的任务被移动', 'code' => 3001)));
        }
        
    }
}