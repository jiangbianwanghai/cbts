<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_bug extends CI_Model {

    private $_table = 'issue_bug';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加数据
     */
    public function add($data) {
        $feedback = array(
            'status' => false,
            'message' => ''
        );
        $res = $this->db->insert('issue_bug', $data);
        if ($res) {
            $feedback['status'] = true;
            $feedback['message'] = 'success';
        } else {
            $feedback['message'] = $this->db->error();
        }
        return $feedback;
    }

    public function listByIssueId($id) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('id, subject, state, level, add_user, add_time, accept_user, accept_time, status');
        $this->db->where('issue_id', $id);
        $db = clone($this->db);
        $rows['total'] = $this->db->count_all_results($this->_table);
        $this->db = $db;
        $this->db->order_by('status', 'desc');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->_table);
        $rows['data'] = $query->result_array();
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

    public function searchByMysql($projectId, $folder, $state, $limit = 20, $offset = 0, $status = 'all') {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('id, level, issue_id, subject, add_user, add_time, accept_user, accept_time, state, status');
        $this->db->where('project_id', $projectId);
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
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'state' => '-1', 'check_time' => time(), 'status' => '0'), array('id' => $id));
    }

    public function close($id) {
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'check_time' => time(), 'status' => '0'), array('id' => $id));
    }

    public function open($id) {
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'check_time' => time(), 'status' => '1', 'state' => '0'), array('id' => $id));
    }

    public function over($id) {
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'state' => '3'), array('id' => $id));
    }

    public function returnbug($id) {
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'state' => '5', 'status' => '0'), array('id' => $id));
    }

    public function change_accept($id, $uid) {
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'accept_user' => $uid), array('id' => $id));
    }

    public function starList($projectId = 0, $limit = 20, $offset = 0) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('*');
        $this->db->where('star.add_user', $this->input->cookie('uids'));
        $this->db->where('star.star_type', 3);
        if ($projectId)
            $this->db->where('issue_bug.project_id', $projectId);
        $this->db->join($this->_table, 'star.star_id = issue_bug.id', 'left');
        $rows['total'] = $this->db->count_all_results('star');
        $this->db->select('issue_bug.id,subject,issue_bug.add_user,issue_bug.add_time,accept_user,accept_time,state,level,status');
        $this->db->from('star');
        $this->db->where('star.add_user', $this->input->cookie('uids'));
        $this->db->where('star.star_type', 3);
        if ($projectId)
            $this->db->where('issue_bug.project_id', $projectId);
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

    public function starByBugId($array) {
        $row = array();
        $this->db->select('star_id');
        $this->db->where_in('star_id', $array);
        $this->db->where('star_type', 3);
        $this->db->where('add_user', $this->input->cookie('uids'));
        $query = $this->db->get('star');
        $row = $query->result_array();
        return $row;
    }

    public function getBugAct($id) {
        $row = array();
        $this->db->select('id');
        $this->db->where('issue_id', $id);
        $this->db->where('status', 1);
        $query = $this->db->get($this->_table);
        $row = $query->result_array();
        return $row;
    }
}