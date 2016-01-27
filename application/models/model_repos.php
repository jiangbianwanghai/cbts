<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_repos extends CI_Model {

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
        $data['add_user'] = $this->input->cookie('username');
        $sql = "SELECT `id` FROM `choc_repos` WHERE `repos_name` = '".$data['repos_name']."'";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            return $feedback;
        }
        $res = $this->db->insert('repos', $data);
        if ($res) {
            $feedback['status'] = true;
            $feedback['message'] = 'success';
        } else {
            $feedback['message'] = $this->db->error();
        }
        return $feedback;
    }

    /**
     * 生产缓存
     */
    public function cacheRefresh()
    {
        $data = $this->rows();
        $this->load->helper('file');
        $file = "<?php\n//代码库\n\$repos = ".var_export($data, true).";";
        return write_file('./cache/repos.conf.php', $file);
    }

    /**
     * 列表
     */
    public function rows() {
        $rows = false;
        $sql = "SELECT * FROM `choc_repos` WHERE `status` = '1' ORDER BY `id` DESC";
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
        return $this->db->update('repos', array('last_time' => time(), 'last_user' => $this->input->cookie('username'), 'status' => '-1'), array('id' => $id));
    }

    /**
     * 获取指定单条信息
     */
    public function fetchOne($id) {
        $query = $this->db->get_where('repos', array('id' => $id), 1);
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
        $data['last_user'] = $this->input->cookie('username');
        return $this->db->update('repos', $data, array('id' => $id));
    }
}