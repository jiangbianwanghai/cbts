<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_bug extends CI_Model {

    private $_table = 'issue_bug';

    function __construct()
    {
        parent::__construct();
    }

    public function fetchOne($id) {
        $query = $this->db->get_where($this->_table, array('id' => $id), 1);
        if ($query->num_rows()) {
            $row = $query->row_array();
            return $row;
        } else {
            return false;
        }
    }

    public function searchByMysql($limit = 20, $offset = 0, $userType = false, $state = false) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('id, level, issue_id, subject, add_user, add_time, accept_user, accept_time, state');
        if ($userType == 1)
            $this->db->where('add_user', $this->input->cookie('uids'));
        if ($userType == 2)
            $this->db->where('accept_user', $this->input->cookie('uids'));
        if (is_numeric($state) && $state)
            $this->db->where('state', $state);
        $db = clone($this->db);
        $rows['total'] = $this->db->count_all_results($this->_table);
        $this->db = $db;
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table);
        $rows['data'] = $query->result_array();
        return $rows;
    }

    public function getPrevNext($id) {
        $output = array('prev' => 0, 'next' => 0);
        $this->db->select('id');
        $this->db->limit(1, 0);
        $db = clone($this->db);
        $this->db->order_by('id', 'desc');
        $this->db->where('id <', $id);
        $query = $this->db->get($this->_table);
        $row = $query->row_array();
        if ($row)
            $output['prev'] = $row['id'];
        $this->db = $db;
        $this->db->order_by('id', 'asc');
        $this->db->where('id >', $id);
        $query = $this->db->get($this->_table);
        $row = $query->row_array();
        if ($row)
            $output['next'] = $row['id'];
        return $output;
    }

    public function checkin($id, $level) {
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'level' => $level, 'state' => '1', 'check_time' => time()), array('id' => $id));
    }
    public function checkout($id) {
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'state' => '-1', 'check_time' => time()), array('id' => $id));
    }
}