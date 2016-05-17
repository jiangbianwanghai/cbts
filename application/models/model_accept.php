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
        $query = $this->db->get_where($this->_table, array('id' => $id), 1);
        if ($query->num_rows()) {
            $row = $query->row_array();
            return $row;
        } else {
            return false;
        }
    }

    public function rowByIssue($issue_id, $flow) {
        $query = $this->db->get_where($this->_table, array('issue_id' => $issue_id, 'flow' => $flow), 1);
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

    public function update($uid, $id) {
        return $this->db->update($this->_table, array('accept_user' => $uid, 'accept_time' => time()), array('id' => $id));
    }

    /**
     * 计划参与的人员
     */
    public function getTeamByIssue($array) {
        if ($array) {
            $this->db->select('accept_user');
            $this->db->where_in('issue_id', $array);
            $this->db->group_by('accept_user');
            $query = $this->db->get($this->_table);
            $rows = $query->result_array();
            return $rows;
        } else {
            return false;
        }
    }
}