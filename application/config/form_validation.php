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

    //用户登录
    'admin/signin' => array(
        array(
            'field'   => 'username', 
            'label'   => '账号', 
            'rules'   => 'trim|required|min_length[5]|max_length[12]|xss_clean'
        ),
        array(
            'field'   => 'password', 
            'label'   => '密码', 
            'rules'   => 'trim|required|md5'
        )
    ),
    
    //添加分类
    'category/add' => array(
        array(
            'field'   => 'repos_ver', 
            'label'   => '属性名称', 
            'rules'   => 'trim|required|min_length[1]|max_length[12]|xss_clean'
        )
    ),
    
    //添加号码
    'number/add' => array(
        array(
            'field'   => 'number', 
            'label'   => '号码名称', 
            'rules'   => 'trim|required|min_length[11]|max_length[11]|xss_clean'
        )
    ),
);

/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */