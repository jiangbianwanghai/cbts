<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_project extends CI_Model {

    private $_table = 'project';

    function __construct()
    {
        parent::__construct();
    }

    public function add($data) {
        $feedback = array(
            'status' => false,
            'message' => ''
        );
        $res = $this->db->insert($this->_table, $data);
        if ($res) {
            $id = $this->db->insert_id();
            $salt = substr(uniqid(rand()), -6);
            $md5 = md5(md5($id).$salt);
            $this->db->update($this->_table, array('md5' => $md5, 'salt' => $salt), array('id' => $id));
            $feedback['status'] = true;
            $feedback['message'] = 'success';
        } else {
            $feedback['message'] = $this->db->error();
        }
        return $feedback;
    }

    /**
     * 生产缓存
     */
    public function cacheRefresh()
    {
        $rows = $this->rows(0,20,1);
        foreach ($rows['data'] as $val) {
            $projectRows[$val['md5']] = $val;
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

    public function fetchOne($id) {
        $row = array();
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->limit(1, 0);
        $query = $this->db->get($this->_table);
        if ($query->num_rows()) {
            $row = $query->row_array();
            return $row;
        } else {
            return false;
        }
    }

    public function rowsByPlan($idArr, $string = 0) {
        $row = array();
        if (!$string)
            $string = '*';
        $this->db->select($string);
        $this->db->where_in('id', $idArr);
        $query = $this->db->get($this->_table);
        if ($query->num_rows()) {
            $row = $query->result_array();
            return $row;
        } else {
            return false;
        }
    }
}