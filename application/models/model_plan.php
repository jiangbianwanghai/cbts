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
}