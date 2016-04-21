<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_project extends CI_Model {

    private $_table = 'project';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 生产缓存
     */
    public function cacheRefresh()
    {
        $rows = $this->rows(0,20,1);
        foreach ($rows['data'] as $val) {
            $projectRows[$val['id']] = $val;
        }
        $this->load->helper('file');
        $file = "<?php\n//项目信息\n\$project = ".var_export($projectRows, true).";";
        return write_file('./cache/project.conf.php', $file);
    }

    /**
     * 列表
     */
    public function rows($offset = 0, $limit = 20, $all = 0) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('*');
        $db = clone($this->db);
        $rows['total'] = $this->db->count_all_results($this->_table);
        $this->db = $db;
        $this->db->order_by('id', 'desc');
        !empty($all) && $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table);
        $rows['data'] = $query->result_array();
        return $rows;
    }
}