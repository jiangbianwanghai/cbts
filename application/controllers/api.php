<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class api extends CI_Controller {

    /**
     *  输出项目状态
     */
    public function check_status() {
        $repo = $this->uri->segment(4, 0);
        $env = (int)$this->uri->segment(6, 0);
	$this->load->database();
	$sql = "select ct.id, ct.issue_id from choc_test as ct join choc_repos as cr on ct.repos_id = cr.id where cr.repos_name = '" . $repo . "' and ct.rank = '" . $env . "' and ct.state =1 limit 1";
	$query = $this->db->query($sql);
	$rs = $query->row_array();
	if($rs){
	    echo 'inuse' . $rs['issue_id'];
	}else{
	    echo 'nouse';
	}
    }

}
