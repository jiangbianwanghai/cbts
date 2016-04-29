<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_accept extends CI_Model {

    private $_table = 'accept';

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

    /**
     * 读取
     */
    public function users($id) {
        $users = array();
        $this->db->select('*');
        $this->db->where('issue_id', $id);
        $query = $this->db->get($this->_table);
        $rows = $query->result_array();
        if ($rows) {
            foreach ($rows as $key => $value) {
                $users[$value['flow']] = $value; 
            }
        }
        return $users;
    }

    /**
     * 获取指定单条信息
     */
    public function fetchOne($id) {
        $query = $this->db->get_where('issue', array('id' => $id), 1);
        if ($query->num_rows()) {
            $row = $query->row_array();
            return $row;
        } else {
            return false;
        }
    }

    public function changeUser($uid, $id) {
        return $this->db->update($this->_table, array('accept_user' => $uid), array('id' => $id));
    }
}