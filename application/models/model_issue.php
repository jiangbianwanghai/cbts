<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_issue extends CI_Model {

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
        $sql = "SELECT `id` FROM `choc_issue` WHERE `issue_name` = '".$data['issue_name']."'";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return $feedback;
        }
        $res = $this->db->insert('issue', $data);
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
        $sql = "SELECT * FROM `choc_issue` ORDER BY `id` DESC";
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
        return $this->db->update('issue', array('last_time' => time(), 'last_user' => $this->input->cookie('uid'), 'status' => '-1'), array('id' => $id));
    }

    /**
     * 关闭
     */
    public function close($id) {
        return $this->db->update('issue', array('last_time' => time(), 'last_user' => $this->input->cookie('uid'), 'status' => '0'), array('id' => $id));
    }

    /**
     * 已解决
     */
    public function resolve($id) {
        return $this->db->update('issue', array('last_time' => time(), 'last_user' => $this->input->cookie('uid'), 'status' => '0', 'resolve' => '1'), array('id' => $id));
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

    /**
     * 更新信息
     */
    public function update($data) {
        $id = $data['id'];
        unset($data['id']);
        $data['last_time'] = time();
        $data['last_user'] = $this->input->cookie('uid');
        return $this->db->update('issue', $data, array('id' => $id));
    }

    /**
     * 我的任务列表
     */
    public function my($offset = 0, $per_page = 20) {
        $rows = array(
            'total_rows' => 0,
            'data' => false
        );

        //获取总数
        $sql = "SELECT * FROM `choc_issue` WHERE `add_user` = '".$this->input->cookie('uid')."' AND `status` = '1'";
        $query = $this->db->query($sql);
        $rows['total_rows'] = $query->num_rows;

        //获取翻页数据
        $sql = "SELECT * FROM `choc_issue` WHERE `add_user` = '".$this->input->cookie('uid')."' AND `status` = '1' ORDER BY `id` DESC LIMIT ".$offset .", ".$per_page."";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows['data'][] = $row;
        }
        return $rows;
    }

    /**
     * 任务广场列表
     */
    public function plaza($offset = 0, $per_page = 20) {
        $rows = array(
            'total_rows' => 0,
            'data' => false
        );

        //获取总数
        $sql = "SELECT * FROM `choc_issue`";
        $query = $this->db->query($sql);
        $rows['total_rows'] = $query->num_rows;

        //获取翻页数据
        $sql = "SELECT * FROM `choc_issue` ORDER BY `id` DESC LIMIT ".$offset .", ".$per_page."";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows['data'][] = $row;
        }
        return $rows;
    }
}