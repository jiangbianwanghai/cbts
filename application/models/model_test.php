<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_test extends CI_Model {

    public $rankArr = array(
        'dev' => '0', 
        'test' => '1',
        'product' => '2'
    );
    
    public $stateArr = array(
        'wait' => '0',
        'doing' => '1',
        'yes' => '3',
        'no' => '-3',
        'cover' => '5'
    );

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取最新的10条记录
     */
    public function top10() {
        $rows = false;
        $sql = "SELECT * FROM `choc_test` WHERE `status` = 1 ORDER BY `id` DESC LIMIT 0,10";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows[] = $row;
        }
        return $rows;
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
        $data['add_user'] = $this->input->cookie('uids');
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
    public function listByIssueId($id) {
        $rows = false;

        //获取总数
        $sql = "SELECT * FROM `choc_test` WHERE `issue_id` = '".$id."'";
        $query = $this->db->query($sql);
        $rows['total_rows'] = $query->num_rows;

        $sql = "SELECT * FROM `choc_test` WHERE `issue_id` = '".$id."' ORDER BY `id` DESC";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows['data'][] = $row;
        }
        return $rows;
    }

    /**
     * 删除
     */
    public function del($id) {
        return $this->db->update('test', array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'status' => '-1'), array('id' => $id));
    }

    /**
     * 根据任务ID删除相关提测记录
     */
    public function delByIssueID($id) {
        $query = $this->db->get_where('test', array('issue_id' => $id));
        if ($query->num_rows()) {
            return $this->db->update('test', array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'status' => '-1'), array('issue_id' => $id));
        }
        return true;
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
     * 获取前面一个任务的信息
     */
    public function prev($testId, $reposId) {
        $sql = "SELECT * FROM `choc_test` WHERE `id` < '".$testId."' AND `repos_id` = '".$reposId."' AND `status` = 1 ORDER BY `id` DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $row = $query->row_array();
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 获取前面一个任务的信息
     */
    public function prev2($reposId, $testFlag) {
        $sql = "SELECT * FROM `choc_test` WHERE `repos_id` = '".$reposId."' AND `state` = 1 AND `status` = 1 ORDER BY `id` DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $row = $query->row_array();
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 验证后续的版本是否正在测试，如果后续版本已经发布，则当前版本状态改为已覆盖
     */
    public function checkOver($id, $repos_id, $test_flag) {
        $sql = "SELECT * FROM `choc_test` WHERE `repos_id` = '".$repos_id."' AND `test_flag` > '".$test_flag."' AND `rank` = 2 ORDER BY `id` ASC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            //更改为已覆盖
            $this->db->update('test', array('state' => '5'), array('id' => $id));

            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证版本号是否可以添加
     */
    public function checkFlag($repos_id, $br, $test_flag) {
        $sql = "SELECT * FROM `choc_test` WHERE `repos_id` = '".$repos_id."' AND `br` = '".$br."' ORDER BY `test_flag` DESC LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $row = $query->row_array();
            if ($row) {
                if ($row['test_flag'] < $test_flag) {
                    return true;
                }
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * 更新信息
     */
    public function update($data) {
        $id = $data['id'];
        unset($data['id']);
        $data['last_time'] = time();
        $data['last_user'] = $this->input->cookie('uids');
        return $this->db->update('test', $data, array('id' => $id));
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
        $sql = "SELECT * FROM `choc_test` WHERE `add_user` = '".$this->input->cookie('uids')."' AND `status` = '1'";
        $query = $this->db->query($sql);
        $rows['total_rows'] = $query->num_rows;

        //获取翻页数据
        $sql = "SELECT * FROM `choc_test` WHERE `add_user` = '".$this->input->cookie('uids')."' AND `status` = '1' ORDER BY `id` DESC LIMIT ".$offset .", ".$per_page."";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows['data'][] = $row;
        }
        return $rows;
    }

    /**
     * 我的任务列表
     */
    public function profile($id, $role, $leftTime, $rightTime, $offset = 0, $per_page = 20) {
        $rows = array(
            'total_rows' => 0,
            'data' => false
        );

        $user = 'add_user';
        $userTime = 'add_time';
        if ($role == 1) {
            $user = 'accept_user';
            $userTime = 'accept_time';
        }
        if ($role == 2) {
            $user = 'add_user';
            $userTime = 'add_time';
        }

        //获取总数
        $sql = "SELECT * FROM `choc_test` WHERE `".$user."` = '".$id."' AND `".$userTime."` >= '".$leftTime."' AND `".$userTime."` < '".$rightTime."'";
        $query = $this->db->query($sql);
        $rows['total_rows'] = $query->num_rows;

        //获取翻页数据
        $sql = "SELECT * FROM `choc_test` WHERE `".$user."` = '".$id."' AND `".$userTime."` >= '".$leftTime."' AND `".$userTime."` < '".$rightTime."' ORDER BY `id` DESC LIMIT ".$offset .", ".$per_page."";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows['data'][] = $row;
        }
        return $rows;
    }

    /**
     * 我的待测
     */
    public function todo($offset = 0, $per_page = 20) {
        $rows = array(
            'total_rows' => 0,
            'data' => false
        );

        //获取总数
        $sql = "SELECT * FROM `choc_test` WHERE `accept_user` = '".$this->input->cookie('uids')."' AND `state` IN (0,1) AND `status` = '1'";
        $query = $this->db->query($sql);
        $rows['total_rows'] = $query->num_rows;

        //获取翻页数据
        $sql = "SELECT * FROM `choc_test` WHERE `accept_user` = '".$this->input->cookie('uids')."' AND `state` IN (0,1) AND `status` = '1' ORDER BY `id` DESC LIMIT ".$offset .", ".$per_page."";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows['data'][] = $row;
        }
        return $rows;
    }

    /**
     * 提测广场列表
     */
    public function plaza($add_user, $accept_user, $rank, $state, $offset = 0, $per_page = 20) {
        $rows = array(
            'total_rows' => 0,
            'data' => false
        );

        $addUserStr = $acceptUserStr = "";

        if ($add_user == 'my') {
            $addUserStr = "`add_user` = '".$this->input->cookie('uids')."' AND ";
        }
        if ($accept_user == 'my') {
            $acceptUserStr = "`accept_user` = '".$this->input->cookie('uids')."' AND ";
        }

        //获取总数
        $sql = "SELECT * FROM `choc_test` WHERE ".$addUserStr.$acceptUserStr."`state` = '".$this->stateArr[$state]."' AND `rank` = '".$this->rankArr[$rank]."' AND `status` = 1";
        $query = $this->db->query($sql);
        $rows['total_rows'] = $query->num_rows;

        //获取翻页数据
        $sql = "SELECT * FROM `choc_test` WHERE ".$addUserStr.$acceptUserStr."`state` = '".$this->stateArr[$state]."' AND `rank` = '".$this->rankArr[$rank]."' AND `status` = 1 ORDER BY `id` DESC LIMIT ".$offset .", ".$per_page."";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows['data'][] = $row;
        }
        return $rows;
    }

    /**
     * 查看某个代码库的提测记录
     */
    public function repos($repos_id, $offset = 0, $per_page = 20) {
        $rows = array(
            'total_rows' => 0,
            'data' => false
        );

        //获取总数
        $sql = "SELECT * FROM `choc_test` WHERE `repos_id` = '".$repos_id."'";
        $query = $this->db->query($sql);
        $rows['total_rows'] = $query->num_rows;

        //获取翻页数据
        $sql = "SELECT * FROM `choc_test` WHERE `repos_id` = '".$repos_id."' ORDER BY `id` DESC LIMIT ".$offset .", ".$per_page."";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows['data'][] = $row;
        }
        return $rows;
    }

    /**
     * 受理
     */
    public function accept($id) {
        return $this->db->update('test', array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'accept_user' => $this->input->cookie('uids'), 'accept_time' => time(), 'tice' => 3, 'tice_time' => time()), array('id' => $id));
    }

    /**
     * 更改受理
     */
    public function update_accept($id, $uid) {
        return $this->db->update('test', array('last_time' => time(), 'last_user' => $uid, 'accept_user' => $uid, 'accept_time' => time()), array('id' => $id));
    }

    /**
     * 提测失败回执提测状态
     */
    public function returntice($id) {
        return $this->db->update('test', array('tice' => '-1'), array('id' => $id));
    }

    public function changeTice($id, $status) {
        if ($status == 'zhanyong') {
            return $this->db->update('test', array('tice' => 1,'state' => 1, 'rank' => 1, 'last_time' => time(), 'last_user' => $this->input->cookie('uids')), array('id' => $id));
        }
        if ($status == 'online') {
            return $this->db->update('test', array('tice' => 7,'state' => 3, 'rank' => 2, 'last_time' => time(), 'last_user' => $this->input->cookie('uids')), array('id' => $id));
        }
        if ($status == 'wait') {
            return $this->db->update('test', array('tice' => 0,'state' => 0, 'rank' => 0, 'last_time' => time(), 'last_user' => $this->input->cookie('uids')), array('id' => $id));
        }
        if ($status == 'pass') {
            return $this->db->update('test', array('tice' => 1,'state' => -3, 'rank' => 0, 'last_time' => time(), 'last_user' => $this->input->cookie('uids')), array('id' => $id));
        }
        if ($status == 'launch') {
            return $this->db->update('test', array('tice' => 1,'state' => 3, 'rank' => 1, 'last_time' => time(), 'last_user' => $this->input->cookie('uids')), array('id' => $id));
        }
    }

    /**
     * 更改提测状态为发布中，用于往线上发布
     */
    public function cap($id) {
        return $this->db->update('test', array('tice' => '5'), array('id' => $id));
    }

    /**
     * 更改状态
     */
    public function changestat($id, $state) {
        return $this->db->update('test', array('state' => $state), array('id' => $id));
    }
    /**
     * 贡献代码者
     */
    public function shareUsers($issue_id) {
        $rows = false;
        $sql = "SELECT `add_user` from `choc_test` WHERE `issue_id` = '".$issue_id."' group by `add_user`";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows[] = $row['add_user'];
        }
        return $rows;
    }

    /**
     * 统计开发人员提测量排行
     */
    public function rankByUsers($leftTime, $rightTime) {
        $rows = array();
        $sql = "SELECT COUNT(`id`) as `num`, `add_user` FROM `choc_test` WHERE `add_time` >= '".$leftTime."' AND `add_time` < '".$rightTime."' GROUP BY `add_user` ORDER BY `num` DESC";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * 统计代码库提测量排行
     */
    public function rankByRepos($leftTime, $rightTime) {
        $rows = array();
        $sql = "SELECT COUNT(`id`) as `num`, `repos_id` FROM `choc_test` WHERE `add_time` >= '".$leftTime."' AND `add_time` < '".$rightTime."' GROUP BY `repos_id` ORDER BY `num` DESC";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $rows[] = $row;
        }
        return $rows;
    }

    public function stacked($userId = 0, $leftTime, $rightTime) {
        $rows = false;
        $where = 'WHERE `status` >=0';
        if ($userId) {
            $where .= " AND `add_user` = '".$userId."' AND `add_time` >= '".$leftTime."' AND `add_time` < '".$rightTime."'";
        }
        //不通过量
        $sql = "SELECT FROM_UNIXTIME(`add_time`,'%Y-%m-%d') AS `perday`, SUM(`state` = -3)  AS `count` FROM `choc_test` ".$where." GROUP BY FROM_UNIXTIME(`add_time`,'%Y-%m-%d')";
        $query = $this->db->query($sql);
        $row1 = $query->result_array();
        //其他状态
        $sql = "SELECT FROM_UNIXTIME(`add_time`,'%Y-%m-%d') AS `perday`, SUM(`state` IN (0,1,3,5))  AS `count` FROM `choc_test` ".$where." GROUP BY FROM_UNIXTIME(`add_time`,'%Y-%m-%d')";
        $query = $this->db->query($sql);
        $row2 = $query->result_array();
        foreach ($row1 as $key=>$value)
        {
            $rows[$key]['perday'] = $value['perday'];
            $rows[$key]['no'] = $value['count'];
            $rows[$key]['other'] = $row2[$key]['count'];
        }
        return $rows;
    }

    public function stackedByQa($userId = 0, $leftTime, $rightTime) {
        $rows = false;
        $where = 'WHERE `status` >=0';
        if ($userId) {
            $where .= " AND `accept_user` = '".$userId."' AND `accept_time` >= '".$leftTime."' AND `accept_time` < '".$rightTime."'";
        }
        //不通过量
        $sql = "SELECT FROM_UNIXTIME(`accept_time`,'%Y-%m-%d') AS `perday`, SUM(`state` IN (0,1))  AS `count` FROM `choc_test` ".$where." GROUP BY FROM_UNIXTIME(`accept_time`,'%Y-%m-%d')";
        $query = $this->db->query($sql);
        $row1 = $query->result_array();
        //其他状态
        $sql = "SELECT FROM_UNIXTIME(`accept_time`,'%Y-%m-%d') AS `perday`, SUM(`state` IN (-3,3,5))  AS `count` FROM `choc_test` ".$where." GROUP BY FROM_UNIXTIME(`accept_time`,'%Y-%m-%d')";
        $query = $this->db->query($sql);
        $row2 = $query->result_array();
        foreach ($row1 as $key=>$value)
        {
            $rows[$key]['perday'] = $value['perday'];
            $rows[$key]['no'] = $value['count'];
            $rows[$key]['other'] = $row2[$key]['count'];
        }
        return $rows;
    }

    public function topUser() {
        $sql = "SELECT COUNT(1) AS `num`, `add_user` FROM `choc_test` WHERE `status` >=0 GROUP BY `add_user` ORDER BY `num` DESC LIMIT 5";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function topAcceptUser() {
        $sql = "SELECT COUNT(1) AS `num`, `accept_user` FROM `choc_test` WHERE `status` >=0 GROUP BY `accept_user` ORDER BY `num` DESC LIMIT 5";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function topPassUser() {
        $sql = "SELECT COUNT(1) AS `num`, `add_user` FROM `choc_test` WHERE `status` >=0 AND `state` = '-3' GROUP BY `add_user` ORDER BY `num` DESC LIMIT 5";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function tongji($uid) {
        $array = array('taskNumWithme' => 0, 'testNumByme' => 0, 'testNumWait' => 0, 'testNumPass' => 0);
        //统计3月份多少任务与他有关
        $sql = "SELECT count(1) as total FROM `choc_test` WHERE `add_time` >= '1456761600' AND `add_time` < '1459440000' AND `add_user`='".$uid."' AND `status` = 1 GROUP BY `issue_id`";
        $query = $this->db->query($sql);
        if ($query->result_array()) {
            $row = $query->result_array();
            $num = 0;
            foreach ($row as $key => $value) {
                $num ++;
            }
            $array['taskNumWithme'] = $num;
        }
        //统计提测量
        $sql = "SELECT count(1) as total FROM `choc_test` WHERE `add_time` >= '1456761600' AND `add_time` < '1459440000' AND `add_user`='".$uid."' AND `status` = 1";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $row = $query->row_array();
            $array['testNumByme'] = $row['total'];
        }
        //统计为测试
        $sql = "SELECT count(1) as total FROM `choc_test` WHERE `add_time` >= '1456761600' AND `add_time` < '1459440000' AND `add_user`='".$uid."' AND `status` = 1 AND `tice` = 0";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $row = $query->row_array();
            $array['testNumWait'] = $row['total'];
        }
        //不通过
        $sql = "SELECT count(1) as total FROM `choc_test` WHERE `add_time` >= '1456761600' AND `add_time` < '1459440000' AND `add_user`='".$uid."' AND `status` = 1 AND `state` = '-3'";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $row = $query->row_array();
            $array['testNumPass'] = $row['total'];
        }

        return $array;
    }
}