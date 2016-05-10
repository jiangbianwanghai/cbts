<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_plan extends CI_Model {

    private $_table = 'plan';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 插入数据库
     */
    public function add($data) {
        $feedback = array(
            'status' => false,
            'message' => ''
        );
        $res = $this->db->insert($this->_table, $data);
        if ($res) {
            $feedback['status'] = true;
            $feedback['message'] = 'success';
        } else {
            $feedback['message'] = $this->db->error();
        }
        return $feedback;
    }

    public function planFolder($projectId) {
        $row = array();
        $this->db->select('id, plan_name');
        $this->db->where('project_id', $projectId);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->_table);
        $row = $query->result_array();
        return $row;
    }

    public function fetchOne($id, $string = NULL) {
        if (!$string)
            $string = '*';
        $this->db->select($string);
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

    /**
     * 列表
     */
    public function rows($pageFlag = 0, $offset = 0, $limit = 20) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('*');
        $db = clone($this->db);
        $rows['total'] = $this->db->count_all_results($this->_table);
        $this->db = $db;
        $this->db->order_by('id', 'desc');
        $pageFlag && $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table);
        $rows['data'] = $query->result_array();
        return $rows;
    }
}