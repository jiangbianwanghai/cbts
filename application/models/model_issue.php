<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_issue extends CI_Model {

    private $_table = 'issue';

    public $resolveArr = array(
        'disable' => '1', 
        'able' => '0'
    );

    public $statusArr = array(
        'able' => '1',
        'close' => '0',
        'delete' => '-1'
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
        $sql = "SELECT * FROM `choc_issue` WHERE `status` = 1 ORDER BY `id` DESC LIMIT 0,10";
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
        $sql = "SELECT `id` FROM `choc_issue` WHERE `issue_name` = '".$data['issue_name']."'";
        $query = $this->db->query($sql);
        if ($query->num_rows()) {
            $feedback['message'] = "此任务已经存在";
            return $feedback;
        }
        $res = $this->db->insert('issue', $data);
        if ($res) {
            $feedback['id'] = $this->db->insert_id();
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
    public function rows($pageFlag = 0, $offset = 0, $limit = 20) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('*');
        $db = clone($this->db);
        $rows['total'] = $this->db->count_all_results($this->_table);
        $this->db = $db;
        $this->db->order_by('id', 'desc');
        $pageFlag && $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table);
        $rows['data'] = $query->result_array();
        return $rows;
    }

    /**
     * 删除
     */
    public function del($id) {
        return $this->db->update('issue', array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'status' => '-1'), array('id' => $id));
    }

    /**
     * 关闭
     */
    public function close($id) {
        return $this->db->update('issue', array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'status' => '0'), array('id' => $id));
    }

    /**
     * 关闭
     */
    public function open($id) {
        return $this->db->update('issue', array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'status' => '1', 'resolve' => '0'), array('id' => $id));
    }

    /**
     * 已解决
     */
    public function resolve($id) {
        return $this->db->update('issue', array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'status' => '0', 'resolve' => '1'), array('id' => $id));
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
        $data['last_user'] = $this->input->cookie('uids');
        return $this->db->update('issue', $data, array('id' => $id));
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
        $sql = "SELECT * FROM `choc_issue` WHERE `".$user."` = '".$id."' AND `".$userTime."` >= '".$leftTime."' AND `".$userTime."` < '".$rightTime."'";
        $query = $this->db->query($sql);
        $rows['total_rows'] = $query->num_rows;

        //获取翻页数据
        $sql = "SELECT * FROM `choc_issue` WHERE `".$user."` = '".$id."' AND `".$userTime."` >= '".$leftTime."' AND `".$userTime."` < '".$rightTime."' ORDER BY `id` DESC LIMIT ".$offset .", ".$per_page."";
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
        return $this->db->update('issue', array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'accept_user' => $this->input->cookie('uids'), 'accept_time' => time()), array('id' => $id));
    }

    /**
     * 更改受理
     */
    public function update_accept($id, $uid) {
        return $this->db->update('issue', array('last_time' => time(), 'last_user' => $uid, 'accept_user' => $uid, 'accept_time' => time()), array('id' => $id));
    }

    /**
     * 验证是否受理
     */
    public function checkAccept($id) {
        $row = $this->fetchOne($id);
        if ($row) {
            if ($row['accept_user']) {
                return $row['accept_user'];
            }
        }
        return false;
    }

    public function listByPlan($planId, $projectId, $flow = '-1', $taskType, $limit = 20, $offset = 0) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('*');
        $this->db->where('plan_id', $planId);
        $this->db->where('project_id', $projectId);
        if ($flow >= 0)
            $this->db->where('workflow', $flow);
        if ($taskType)
            $this->db->where('type', $taskType);
        $db = clone($this->db);
        $rows['total'] = $this->db->count_all_results($this->_table);
        $this->db = $db;
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table);
        $rows['data'] = $query->result_array();
        return $rows;
    }

    /**
     * 根据用户ID查询任务信息
     */

    public function listByUserId($uid = 0, $folder = 'to_me', $projectId = 0, $planId = 0, $flow = '-1', $taskType = 0, $limit = 20, $offset = 0) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('*');
        if ($folder == 'to_me')
            $this->db->where('accept_user', $uid);
        if ($folder == 'from_me')
            $this->db->where('add_user', $uid);
        if ($projectId)
            $this->db->where('project_id', $projectId);
        if ($planId)
            $this->db->where('plan_id', $planId);
        if ($flow >= 0)
            $this->db->where('workflow', $flow);
        if ($taskType)
            $this->db->where('type', $taskType);
        $db = clone($this->db);
        $rows['total'] = $this->db->count_all_results($this->_table);
        $this->db = $db;
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table);
        $rows['data'] = $query->result_array();
        return $rows;
    }

    /**
     * 根据用户参与数据链表查任务信息
     */
    public function partin($uid = 0, $folder = 'to_me', $projectId = 0, $planId = 0, $flow = '-1', $taskType = 0, $limit = 20, $offset = 0) {
        $rows = array('total' => 0, 'data' => false);

        //获取记录数
        $this->db->where('accept.accept_user', $uid);
        if ($projectId)
            $this->db->where('issue.project_id', $projectId);
        if ($planId)
            $this->db->where('issue.plan_id', $planId);
        if ($flow >= 0)
            $this->db->where('issue.workflow', $flow);
        if ($taskType)
            $this->db->where('issue.type', $taskType);
        $this->db->join($this->_table, 'accept.issue_id = issue.id', 'left');
        $this->db->group_by('accept.issue_id');
        $query = $this->db->get('accept');
        $rows['total'] = $query->num_rows();

        $this->db->select('issue.id,issue.issue_name,issue.add_user,issue.add_time,issue.accept_user,issue.accept_time,level,status,workflow,type');
        $this->db->from('accept');
        $this->db->where('accept.accept_user', $uid);
        if ($projectId)
            $this->db->where('issue.project_id', $projectId);
        if ($planId)
            $this->db->where('issue.plan_id', $planId);
        if ($flow >= 0)
            $this->db->where('issue.workflow', $flow);
        if ($taskType)
            $this->db->where('issue.type', $taskType);
        $this->db->join($this->_table, 'accept.issue_id = issue.id', 'left');
        $this->db->group_by('accept.issue_id');
        $this->db->order_by('issue.id', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $rows['data'] = $query->result_array();

        return $rows;
    }

    /**
     * 根据用户参数数据链表查项目信息
     */
    public function projectByAccept($uid) {
        $this->db->select('issue.project_id');
        $this->db->from('accept');
        $this->db->where('accept.accept_user', $uid);
        $this->db->where('issue.project_id > ', 0);
        $this->db->group_by('issue.project_id');
        $this->db->join($this->_table, 'accept.issue_id = issue.id', 'left');
        $query = $this->db->get();
        $rows = $query->result_array();
        if ($rows) {
            $idArr = '';
            foreach ($rows as $key => $value) {
                $idArr[] = $value['project_id'];
            }
            $this->db->select('id, md5, project_name');
            $this->db->where_in('id', $idArr);
            $query = $this->db->get('project');
            $rows = $query->result_array();
        }
        return $rows;
    }

    /**
     * 根据用户参数数据链表查计划信息
     */
    public function planByAccept($uid, $projectId) {
        $this->db->select('issue.plan_id');
        $this->db->from('accept');
        $this->db->where('accept.accept_user', $uid);
        $this->db->where('issue.plan_id > ', 0);
        $this->db->where('issue.project_id', $projectId);
        $this->db->group_by('issue.plan_id');
        $this->db->join($this->_table, 'accept.issue_id = issue.id', 'left');
        $query = $this->db->get();
        $rows = $query->result_array();
        if ($rows) {
            $idArr = '';
            foreach ($rows as $key => $value) {
                $idArr[] = $value['plan_id'];
            }
            $this->db->select('id, plan_name');
            $this->db->where_in('id', $idArr);
            $query = $this->db->get('plan');
            $rows = $query->result_array();
        }
        return $rows;
    }

    public function projectListByIssue($uid, $folder = '') {
        $this->db->select('project_id');
        $this->db->where('project_id > ', 0);
        if ($folder == 'to_me')
            $this->db->where('accept_user', $uid);
        if ($folder == 'from_me')
            $this->db->where('add_user', $uid);
        $this->db->group_by('project_id');
        $query = $this->db->get($this->_table);
        $rows = $query->result_array();
        if ($rows) {
            $idArr = '';
            foreach ($rows as $key => $value) {
                $idArr[] = $value['project_id'];
            }
            $this->db->select('id, md5, project_name');
            $this->db->where_in('id', $idArr);
            $query = $this->db->get('project');
            $rows = $query->result_array();
        }
        return $rows;
    }

    public function planListByIssue($uid, $projectId, $folder = '') {
        $this->db->select('plan_id');
        $this->db->where('plan_id > ', 0);
        $this->db->where('project_id', $projectId);
        if ($folder == 'to_me')
            $this->db->where('accept_user', $uid);
        if ($folder == 'from_me')
            $this->db->where('add_user', $uid);
        $this->db->group_by('plan_id');
        $query = $this->db->get($this->_table);
        $rows = $query->result_array();
        if ($rows) {
            $idArr = '';
            foreach ($rows as $key => $value) {
                $idArr[] = $value['plan_id'];
            }
            $this->db->select('id, plan_name');
            $this->db->where_in('id', $idArr);
            $query = $this->db->get('plan');
            $rows = $query->result_array();
        }
        return $rows;
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
        $this->db->where('star_type', 1);
        $this->db->where('add_user', $this->input->cookie('uids'));
        $query = $this->db->get('star');
        $row = $query->result_array();
        return $row;
    }

    public function changeFlow($id, $flow, $acceptUser) {
        return $this->db->update($this->_table, array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'accept_user' => $acceptUser, 'accept_time' => time(), 'workflow' => $flow), array('id' => $id));
    }

    public function starList($projectId = 0, $limit = 20, $offset = 0) {
        $rows = array('total' => 0, 'data' => false);
        $this->db->select('id');
        $this->db->where('star.add_user', $this->input->cookie('uids'));
        $this->db->where('star.star_type', 1);
        if ($projectId)
            $this->db->where('issue.project_id', $projectId);
        $this->db->join($this->_table, 'star.star_id = issue.id', 'left');
        $rows['total'] = $this->db->count_all_results('star');

        $this->db->select('issue.id,issue.issue_name,issue.add_user,issue.add_time,accept_user,accept_time,level,status,workflow,type');
        $this->db->from('star');
        $this->db->where('star.add_user', $this->input->cookie('uids'));
        $this->db->where('star.star_type', 1);
        if ($projectId)
            $this->db->where('issue.project_id', $projectId);
        $this->db->join($this->_table, 'star.star_id = issue.id', 'left');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $rows['data'] = $query->result_array();
        return $rows;
    }

    public function updateWatch($id, $name, $add = True){
        $issue = $this->fetchOne($id);
        if (!$issue) {
            return false;
        }
        $watch = unserialize($issue['watch']);
        if ($add) {
            //执行增加操作
            if (!in_array($name, $watch)) {
                $watch[] = $name;
            }
        }else {
            //执行删除操作
            if (($key = array_search($name, $watch)) !== false) {
                unset($watch[$key]);
            }
        }
        return $this->db->update('issue', array('watch' => serialize($watch)), array('id' => $id));
    }

    public function numByPlan($planId) {
        $this->db->select('id');
        $this->db->where('plan_id', $planId);
        $this->db->where('status >=', 0);
        $this->db->where('workflow', 7);
        $query = $this->db->get($this->_table);
        $num = $query->num_rows();
        return $num;
    }

    public function countByPlan($planId) {
        $this->db->select('id');
        $this->db->where('plan_id', $planId);
        $this->db->where('status >=', 0);
        $query = $this->db->get($this->_table);
        $num = $query->num_rows();
        return $num;
    }

    /**
     * 查询
     * @param string $where 查询条件。$where = array(array('sKey' => 'id', 'sValue' => '12,23'),array('sKey' => 'status', 'sValue' => '1'));
     * @param string $field 查询的字段。$field = 'id, workflow';
     *
     * @return fix
     */
    public function search($where, $field = false) {

        //查询字段
        if ($field)
            $this->db->select($field);
        else
            $this->db->select('*');

        //查询条件
        foreach ($where as $key => $value) {
            if (strpos($value['sValue'], ',')) {
                $val = explode(',', $value['sValue']);
                $this->db->where_in($value['sKey'], $val);
            } else {
                $this->db->where($value['sKey'], $value['sValue']);
            }
        }
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    /**
     * 更新
     * @param string $set 设置数组。$set = array('last_time' => time(), 'last_user' => $this->input->cookie('uids'), 'status' => '0');
     * @param string $where 查询条件。$where = array('id' => $id);
     *
     * @return fix
     */
    public function change($set, $where) {
        return $this->db->update($this->_table, $set, $where);
    }
}
