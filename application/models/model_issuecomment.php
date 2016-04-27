<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_issuecomment extends CI_Model {

    private $_table = 'issue_comment';

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
            $feedback['status'] = true;
            $feedback['message'] = 'success';
        } else {
            $feedback['message'] = $this->db->error();
        }
        return $feedback;
    }

    public function rows($id, $limit = 100, $offset = 0) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('id, issue_id, content, add_user, add_time');
        $this->db->where('issue_id', $id);
        $this->db->where('status', 1);
        $db = clone($this->db);
        $rows['total'] = $this->db->count_all_results($this->_table);
        $this->db = $db;
        $this->db->order_by('id', 'asc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table);
        $rows['data'] = $query->result_array();
        return $rows;
    }

    public function del($id) {
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'status' => '-1'), array('id' => $id));
    }
}