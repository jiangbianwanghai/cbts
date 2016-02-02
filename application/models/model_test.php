<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_test extends CI_Model {

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
        $data['add_time'] = time();
        $data['add_user'] = $this->input->cookie('uid');
        $sql = "SELECT `id` FROM `choc_test` WHERE `repos_id` = '".$data['repos_id']."' AND `test_flag` = '".$data['test_flag']."'";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return $feedback;
        }
        $res = $this->db->insert('test', $data);
        if ($res) {
            $feedback['status'] = true;
            $feedback['message'] = 'success';
        } else {
            $feedback['message'] = $this->db->error();
        }
        return $feedback;
    }

    /**
     * 列表
     */
    public function rows() {
        $rows = false;
        $sql = "SELECT * FROM `choc_test` ORDER BY `id` DESC";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * 列表
     */
    public function listByIssue($id) {
        $rows = false;
        $sql = "SELECT * FROM `choc_test` WHERE `issue_id` = '".$id."' AND `status` = 1 ORDER BY `id` DESC";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * 删除
     */
    public function del($id) {
        return $this->db->update('test', array('last_time' => time(), 'last_user' => $this->input->cookie('uid'), 'status' => '-1'), array('id' => $id));
    }

    /**
     * 获取指定单条信息
     */
    public function fetchOne($id) {
        $query = $this->db->get_where('test', array('id' => $id), 1);
        if ($query->num_rows()) {
            $row = $query->row_array();
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 更新信息
     */
    public function update($data) {
        $id = $data['id'];
        unset($data['id']);
        $data['last_time'] = time();
        $data['last_user'] = $this->input->cookie('uid');
        return $this->db->update('test', $data, array('id' => $id));
    }

    /**
     * 我的任务列表
     */
    public function my() {
        $rows = false;
        $sql = "SELECT * FROM `choc_test` WHERE `add_user` = '".$this->input->cookie('uid')."' AND `status` = '1' ORDER BY `id` DESC";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows[] = $row;
        }
        return $rows;
    }
}