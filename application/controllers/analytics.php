<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class analytics extends CI_Controller {

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
    }

    /**
     *  默认首页
     */
    public function index() {
        
        //设置页面标题
        $data['PAGE_TITLE'] = '计划列表- 数据分析';

        $offset = $this->uri->segment(3, 0);

        //载入配置信息
        $this->config->load('extension', TRUE);
        $config = $this->config->item('pages', 'extension');
        $data['planflow'] = $this->config->item('planflow', 'extension');

        if (file_exists(FCPATH.'/cache/users.conf.php')) {
            require FCPATH.'/cache/users.conf.php';
            $data['users'] = $users;
        }

        //获取信息
        $this->load->model('Model_plan', 'plan', TRUE);
        $rows = $this->plan->rows(1, $offset, $config['per_page']);

        //获取项目数组
        if ($rows['data']) {
            $this->load->model('Model_project', 'project', TRUE);
            foreach ($rows['data'] as $key => $value) {
                $idArr[] = $value['project_id'];
            }
            $idArr = array_unique($idArr);
            if ($idArr) {
                $projectRows = $this->project->rowsByPlan($idArr, 'id, project_name');
                if ($projectRows) {
                    foreach ($projectRows as $key => $value) {
                        $data['projectArr'][$value['id']] = $value;
                    }
                }
            }
        }

        $data['rows'] = $rows['data'];
        $data['total'] = $rows['total'];

        //分页
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/analytics/index';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['per_page'] = $config['per_page'];

        $this->load->view('analytics_index', $data);
    }

    public function issue() {
        //设置页面标题
        $data['PAGE_TITLE'] = '计划列表- 数据分析';

        //获取传值
        $offset = $this->uri->segment(3, 0);

        //载入缓存文件
        if (file_exists(FCPATH.'/cache/users.conf.php')) {
            require FCPATH.'/cache/users.conf.php';
            $data['users'] = $users;
        }

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['planflow'] = $this->config->item('planflow', 'extension');
        $data['level'] = $this->config->item('level', 'extension');
        $data['workflow'] = $this->config->item('workflow', 'extension');
        $data['workflowfilter'] = $this->config->item('workflowfilter', 'extension');
        $data['tasktype'] = $this->config->item('tasktype', 'extension');
        $config = $this->config->item('pages', 'extension');

        //获取信息
        $this->load->model('Model_issue', 'issue', TRUE);
        $rows = $this->issue->rows(1, $offset, $config['per_page']);


        if ($rows['data']) {
            //获取项目数组
            $this->load->model('Model_project', 'project', TRUE);
            foreach ($rows['data'] as $key => $value) {
                $idArr[] = $value['project_id'];
            }
            $idArr = array_unique($idArr);
            if ($idArr) {
                $projectRows = $this->project->rowsByPlan($idArr, 'id, project_name');
                if ($projectRows) {
                    foreach ($projectRows as $key => $value) {
                        $data['projectArr'][$value['id']] = $value;
                    }
                }
            }
            //获取计划数组
            $this->load->model('Model_plan', 'plan', TRUE);
            foreach ($rows['data'] as $key => $value) {
                $idArr[] = $value['plan_id'];
            }
            $idArr = array_unique($idArr);
            if ($idArr) {
                $planRows = $this->plan->rowsByPlan($idArr, 'id, plan_name');
                if ($planRows) {
                    foreach ($planRows as $key => $value) {
                        $data['planArr'][$value['id']] = $value;
                    }
                }
            }
        }

        $data['rows'] = $rows['data'];
        $data['total'] = $rows['total'];

        //分页
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/analytics/issue';
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['per_page'] = $config['per_page'];

        $this->load->view('analytics_issue', $data);
    }
}
