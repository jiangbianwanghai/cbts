<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Form Validation Configuration
|--------------------------------------------------------------------------
|
| All form validation configuration inside.
|
*/
$config = array(
    'plan/add_ajax' => array(
        array(
            'field'   => 'plan_name', 
            'label'   => '名称', 
            'rules'   => 'trim|required|min_length[5]|max_length[40]|xss_clean'
        ),
        array(
            'field'   => 'plan_discription', 
            'label'   => '简介', 
            'rules'   => 'trim|required|min_length[5]|max_length[300]|xss_clean'
        ),
        array(
            'field'   => 'startime', 
            'label'   => '开始时间', 
            'rules'   => 'trim|required|xss_clean'
        ),
        array(
            'field'   => 'endtime', 
            'label'   => '结束时间', 
            'rules'   => 'trim|required|xss_clean'
        )
    ),
    'project/add_ajax' => array(
        array(
            'field'   => 'project_name', 
            'label'   => '名称', 
            'rules'   => 'trim|required|min_length[5]|max_length[40]|xss_clean'
        ),
        array(
            'field'   => 'project_discription', 
            'label'   => '简介', 
            'rules'   => 'trim|required|min_length[5]|max_length[300]|xss_clean'
        )
    ),
    'issue/add_ajax' => array(
        array(
            'field'   => 'issue_name', 
            'label'   => '任务全称', 
            'rules'   => 'trim|required|min_length[5]|max_length[40]|xss_clean'
        ),
        array(
            'field'   => 'type', 
            'label'   => '任务类型', 
            'rules'   => 'trim|required|xss_clean'
        ),
        array(
            'field'   => 'type', 
            'label'   => '紧急程度', 
            'rules'   => 'trim|required|xss_clean'
        ),
        array(
            'field'   => 'issue_summary', 
            'label'   => '简介', 
            'rules'   => 'trim|xss_clean'
        ),
        array(
            'field'   => 'plan_id', 
            'label'   => '计划', 
            'rules'   => 'trim|required|xss_clean'
        )
    ),
    'admin/reg' => array(
        array(
            'field'   => 'email', 
            'label'   => '邮箱', 
            'rules'   => 'trim|required|valid_email|xss_clean'
        ),
        array(
            'field'   => 'username', 
            'label'   => '用户名', 
            'rules'   => 'trim|required|min_length[3]|max_length[40]|xss_clean'
        ),
        array(
            'field'   => 'password', 
            'label'   => '密码', 
            'rules'   => 'trim|required|min_length[6]|xss_clean'
        )
    ),
    'plan/move_issue' => array(
        array(
            'field'   => 'planId', 
            'label'   => '计划ID', 
            'rules'   => 'trim|required|is_natural_no_zero|xss_clean'
        ),
        array(
            'field'   => 'issueId', 
            'label'   => '任务ID', 
            'rules'   => 'trim|required|regex_match[/^\d+(,\d+)*$/]|xss_clean'
        ),
    )
);

/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */