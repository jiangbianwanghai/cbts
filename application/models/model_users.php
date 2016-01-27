<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_users extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 登录验证
     */
    public function checkUser($username, $password)
    {
        $password = md5($password);
        $query = $this->db->get_where('users', array('username' => $username), 1);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            if ($row['password'] == $password) {
                return $row;
            } else {
                return false;
            }
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
}

/* End of file model_users.php */
/* Location: ./application/models/model_users.php */