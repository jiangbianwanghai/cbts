<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_bug extends CI_Model {

    private $_table = 'issue_bug';

    function __construct()
    {
        parent::__construct();
    }

    public function listByIssueId($id) {
        $rows = false;

        //获取总数
        $sql = "SELECT * FROM `choc_issue_bug` WHERE `issue_id` = '".$id."'";
        $query = $this->db->query($sql);
        $rows['total_rows'] = $query->num_rows;

        $sql = "SELECT * FROM `choc_issue_bug` WHERE `issue_id` = '".$id."' ORDER BY `id` DESC";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows['data'][] = $row;
        }
        return $rows;
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

    public function searchByMysql($folder, $state, $limit = 20, $offset = 0, $status = 'all') {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('id, level, issue_id, subject, add_user, add_time, accept_user, accept_time, state, status');
        if ($folder == 'to_me')
            $this->db->where('accept_user', $this->input->cookie('uids'));
        if ($folder == 'from_me')
            $this->db->where('add_user', $this->input->cookie('uids'));
        if ($state == 'invalid')
            $this->db->where('state', '-1');
        if ($state == 'uncheck')
            $this->db->where('state', '0');
        if ($state == 'checkin')
            $this->db->where('state', '1');
        if ($state == 'doing')
            $this->db->where('state', '2');
        if ($state == 'over')
            $this->db->where('state', '3');
        if ($status != 'all') {
            $statusArr = array('normal' => 1, 'close' => 0, 'del' => -1);
            $this->db->where('status', $statusArr[$status]);
        }
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

    public function starList($limit = 20, $offset = 0) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('*');
        $this->db->where('star.add_user', $this->input->cookie('uids'));
        $this->db->where('star.star_type', 3);
        $rows['total'] = $this->db->count_all_results('star');
        $this->db->select('issue_bug.id,subject,issue_bug.add_user,issue_bug.add_time,accept_user,accept_time,state,level,status');
        $this->db->from('star');
        $this->db->where('star.add_user', $this->input->cookie('uids'));
        $this->db->where('star.star_type', 3);
        $this->db->join($this->_table, 'star.star_id = issue_bug.id', 'left');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $rows['data'] = $query->result_array();
        return $rows;
    }

    public function del($id) {
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'status' => '-1'), array('id' => $id));
    }

    public function starAdd($data) {
        return $this->db->insert('star', $data);
    }

    public function starDel($id) {
        return $this->db->delete('star', array('star_id' => $id));
    }
}