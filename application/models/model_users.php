<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_users extends CI_Model {

    private $_table = 'users';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 登录验证
     */
    public function checkUser($username)
    {
        $query = $this->db->get_where('users', array('username' => $username), 1);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 添加数据
     */
    public function add($data) {
        $feedback = array(
            'status' => false,
            'message' => ''
        );
        $data['add_time'] = $data['last_login_time'] = time();
        $sql = "SELECT `uid` FROM `choc_users` WHERE `username` = '".$data['username']."'";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return $feedback;
        }
        $res = $this->db->insert('users', $data);
        if ($res) {
            $feedback['uid'] = $this->db->insert_id();
            $feedback['status'] = true;
            $feedback['message'] = 'success';
        } else {
            $feedback['message'] = $this->db->error();
        }
        return $feedback;
    }

    /**
     * 更新登录时间
     */
    public function updateLoginTime($id)
    {
        return $this->db->update('users', array('last_login_time' => time()), array('uid' => $id));
    }

    public function unsubscribe() {
        return $this->db->update('users', array('unsubscribe' => 1), array('uid' => $this->input->cookie('uids')));
    }

    /**
     * 刷新缓存
     */
    public function cacheRefresh()
    {
        $data = $this->rows();
        foreach ($data as $val) {
            unset($val['password']);
            $rows[$val['uid']] = $val;
        }
        $this->load->helper('file');
        $file = "<?php\n//用户信息\n\$users = ".var_export($rows, true).";";
        return write_file('./cache/users.conf.php', $file);
    }

    /**
     * 列表
     */
    public function rows() {
        $rows = false;
        $sql = "SELECT * FROM `choc_users` ORDER BY `uid` DESC";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows[] = $row;
        }
        return $rows;
    }

    public function checkEmail($email) {
        $query = $this->db->get_where($this->_table, array('email' => $email), 1);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {
            return false;
        }
    }

    public function checkUsername($username) {
        $query = $this->db->get_where($this->_table, array('username' => $username), 1);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {
            return false;
        }
    }
}