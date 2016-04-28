<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class bug extends CI_Controller {

    /**
     * 项目ID
     */
    private $_projectId = 0;

    /**
     * 项目缓存数组
     */
    private $_projectCache = array();

    public function __construct() {

        parent::__construct();

        //载入项目缓存文件
        if (file_exists(FCPATH.'/cache/project.conf.php')) {
            require FCPATH.'/cache/project.conf.php';
            $this->_projectCache = $project;
        } else {
            show_error('项目缓存文件载入失败，请联系<a href="mailto:webmaster@jiangbianwanghai.com">江边望海</a>。', 500, '错误');
        }

        //验证Cookie中的项目ID是否合法
        $projectId = $this->input->cookie('projectId');
        if (isset($project[$projectId])) {
            $this->_projectId = $projectId;
        } else {
            show_error('无法获取项目信息，请 <a href="/">返回首页</a> 选择项目', 500, '错误');
        }
    }

    /**
     * 默认列表控制器
     */
    public function index() {
        $data['PAGE_TITLE'] = 'BUG列表';
        $data['star'] = array();
        $folder = $data['folder'] = $this->uri->segment(3, 'all');
        $state = $data['state'] = $this->uri->segment(4, 'all');
        $status = $data['status'] = $this->uri->segment(5, 'all');
        $offset = $this->uri->segment(6, 0);
        $this->load->model('Model_bug', 'bug', TRUE);
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $config = $this->config->item('pages', 'extension');
        $rows = $this->bug->searchByMysql($folder, $state, $config['per_page'], $offset, $status);
        $data['rows'] = $rows['data'];
        if ($rows['data']) {
            $ids = array();
            foreach ($rows['data'] as $key => $value) {
                $ids[]= $value['id'];
            }
            $star = $this->bug->starByBugId($ids);
            if ($star) {
                foreach ($star as $key => $value) {
                    $data['star'][$value['star_id']] = $value['star_id'];
                }
            }
        }
        $data['total'] = $rows['total'];
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->helper('friendlydate');
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/bug/index/'.$folder.'/'.$state.'/'.$status;
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['per_page'] = $config['per_page'];
        $this->load->view('bug_index', $data);
    }

    /**
     * 垃圾箱列表控制器
     */
    public function trash() {
        $data['PAGE_TITLE'] = '垃圾箱';
        $folder = $data['folder'] = $this->uri->segment(3, 'all');
        $state = $data['state'] = $this->uri->segment(4, 'all');
        $offset = $this->uri->segment(5, 0);
        $this->load->model('Model_bug', 'bug', TRUE);
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $config = $this->config->item('pages', 'extension');
        $rows = $this->bug->searchByMysql($folder, $state, $config['per_page'], $offset, 'del');
        $data['rows'] = $rows['data'];
        $data['total'] = $rows['total'];
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->helper('friendlydate');
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/bug/index/'.$folder.'/'.$state;
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['per_page'] = $config['per_page'];
        $this->load->view('bug_index', $data);
    }

    /**
     * 星标列表控制器
     */
    public function star() {
        $data['PAGE_TITLE'] = '星标记录';
        $this->load->model('Model_bug', 'bug', TRUE);
        $folder = $data['folder'] = $this->uri->segment(3, 'all');
        $state = $this->uri->segment(4, 'all');
        $offset = $this->uri->segment(5, 0);
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $config = $this->config->item('pages', 'extension');
        $rows = $this->bug->starList();
        $data['rows'] = $rows['data'];
        $data['total'] = $rows['total'];
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->helper('friendlydate');
        $this->load->library('pagination');
        $config['total_rows'] = $rows['total'];
        $config['cur_page'] = $offset;
        $config['base_url'] = '/bug/star/'.$state;
        $this->pagination->initialize($config);
        $data['pages'] = $this->pagination->create_links();
        $data['offset'] = $offset;
        $data['per_page'] = $config['per_page'];
        $this->load->view('bug_index', $data);
    }

    public function view() {
        $data['PAGE_TITLE'] = 'Bug详情';
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_bug', 'bug', TRUE);
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->bug->fetchOne($id);
        $data['issue'] = $this->issue->fetchOne($data['row']['issue_id']);
        $data['pager'] = $this->bug->getPrevNext($id);
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
        $this->load->helper('friendlydate');
        $this->load->model('Model_bugcomment', 'bugcomment', TRUE);
        $rows = $this->bugcomment->rows($id);
        $data['rows'] = $rows['data'];
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $this->load->view('bug_view', $data);
    }

    /**
     * 添加表单
     */
    public function add() {

        //页面标题初始化
    	$data['PAGE_TITLE'] = '新增BUG反馈';

        //验证ID是否合法
        $issueId = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->issue->fetchOne($issueId);
        if (!$data['row'])
            show_error('参数错误，无此数据！<a href="/">去首页</a>', 500, '错误');

        //载入配置信息
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');

        $this->load->view('bug_add', $data);
    }

    /**
     * 添加入库
     */
    public function add_ajax() {
    	$this->load->model('Model_bug', 'bug', TRUE);
        $this->load->model('Model_issue', 'issue', TRUE);
        $row = $this->issue->fetchOne($this->input->post('issue_id'));
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '无此数据',
                'url' => '/'
            );
            exit();
        }
        $post = array(
            'level' => $this->input->post('level'),
            'issue_id' => $this->input->post('issue_id'),
            'subject' => $this->input->post('subject'),
            'content' => $this->input->post('content'),
            'add_user' => $this->input->cookie('uids'),
            'add_time' => time(),
            'accept_user' => $row['add_user'],
            'accept_time' => time(),
            'state' => 0,
            'status' => 1
        );
        $feedback = $this->bug->add($post);
        if ($feedback['status']) {
            //发RTX消息提醒受理人
            if (file_exists('./cache/users.conf.php')) {
                require './cache/users.conf.php';
            }
            $this->config->load('extension', TRUE);
            $home = $this->config->item('home', 'extension');
            $home = $home."/issue/view/".$this->input->post('issue_id');

            $subject = $users[$this->input->cookie('uids')]['realname']."给你反馈了一个BUG";
            $this->rtx($users[$row['add_user']]['username'],$home,$subject);

            $callBack = array(
                'status' => true,
                'message' => '提交成功',
                'url' => '/issue/view/'.$this->input->post('issue_id')
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '提交失败',
                'url' => '/test/add/'.$this->input->post('issue_id')
            );
        }
        echo json_encode($callBack);
    }

    public function coment_add_ajax() {
        $this->load->model('Model_bug', 'bug', TRUE);
        $this->load->model('Model_bugcomment', 'bugcomment', TRUE);
        $row = $this->bug->fetchOne($this->input->post('bug_id'));
        if (!$row) {
            $callBack = array(
                'status' => false,
                'message' => '无此数据',
                'url' => '/'
            );
            exit();
        }
        $post = array(
            'bug_id' => $this->input->post('bug_id'),
            'content' => $this->input->post('content'),
            'add_user' => $this->input->cookie('uids'),
            'add_time' => time(),
        );
        $feedback = $this->bugcomment->add($post);
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
        }
        $this->load->helper('friendlydate');
        if ($feedback['status']) {
            $callBack = array(
                'status' => true,
                'message' => array(
                    'content'=>$this->input->post('content'),
                    'username'=>$users[$this->input->cookie('uids')]['username'],
                    'realname'=>$users[$this->input->cookie('uids')]['realname'],
                    'addtime' => friendlydate(time())
                )
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '提交失败'
            );
        }
        echo json_encode($callBack);
    }

    public function del_comment() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_bugcomment', 'bugcomment', TRUE);
        $flag = $this->bugcomment->del($id);
        if ($flag) {
            $callBack = array(
                'status' => true,
                'message' => '删除成功'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '删除失败'
            );
        }
        echo json_encode($callBack);
    }

    public function checkin() {
        $bugId = $this->uri->segment(3, 0);
        $level = $this->uri->segment(4, 0);
        $this->load->model('Model_bug', 'bug', TRUE);
        $flag = $this->bug->checkin($bugId, $level);
        if ($flag) {
            $callBack = array(
                'status' => true,
                'message' => '确认成功'
            );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '确认失败'
            );
        }
        echo json_encode($callBack);
    }

    public function checkout() {
        $this->load->model('Model_bug', 'bug', TRUE);
        $this->load->model('Model_bugcomment', 'bugcomment', TRUE);
        $flag = $this->bug->checkout($this->input->post('bug_id'));
        if ($flag) {
            $post = array(
                'bug_id' => $this->input->post('bug_id'),
                'content' => $this->input->post('content'),
                'add_user' => $this->input->cookie('uids'),
                'add_time' => time(),
            );
            $feedback = $this->bugcomment->add($post);
            if ($feedback['status']) {
                $callBack = array(
                    'status' => true,
                    'message' => '操作成功'
                );
            } else {
                $callBack = array(
                    'status' => false,
                    'message' => '操作失败'
                );
            }
        } else {
            $callBack = array(
                'status' => false,
                'message' => '操作失败'
            );
        }
        echo json_encode($callBack);
    }

    public function del() {
        $bugId = $this->uri->segment(3, 0);
        $this->load->model('Model_bug', 'bug', TRUE);
        $flag = $this->bug->del($bugId);
        if ($flag) {
            $callBack = array(
                    'status' => true,
                    'message' => '删除成功'
                );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '删除失败'
            );
        }

        echo json_encode($callBack);
    }

    public function star_ajax() {
        $bugId = $this->uri->segment(3, 0);
        $this->load->model('Model_bug', 'bug', TRUE);
        $data = array('add_user' => $this->input->cookie('uids'), 'add_time' => time(), 'star_id' => $bugId, 'star_type' => 3);
        $flag = $this->bug->starAdd($data);
        if ($flag) {
            $callBack = array(
                    'status' => true,
                    'message' => '标记成功'
                );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '标记失败'
            );
        }
        echo json_encode($callBack);
    }

    public function star_del() {
        $bugId = $this->uri->segment(3, 0);
        $this->load->model('Model_bug', 'bug', TRUE);
        $flag = $this->bug->starDel($bugId);
        if ($flag) {
            $callBack = array(
                    'status' => true,
                    'message' => '取消标记成功'
                );
        } else {
            $callBack = array(
                'status' => false,
                'message' => '取消标记失败'
            );
        }
        echo json_encode($callBack);
    }

    private function rtx($toList,$url,$subject)
    {
        $subject = str_replace(array('#', '&', ' '), '', $subject);
        $pushInfo = array(
            'to' => $toList,
            'title' => 'CBTS提醒你：',     
            'msg' => $subject . $url,
            'delaytime' => '',                                                                                                                                                               
        );
        $receiver        = iconv("utf-8","gbk//IGNORE", $pushInfo['to']);
        $this->config->load('extension', TRUE);
        $rtx = $this->config->item('rtx', 'extension');
        $url = $rtx['url'].'/sendtortx.php?receiver=' . $receiver . '&notifytitle=' .$pushInfo['title']. '&notifymsg=' . $pushInfo['msg'] . '&delaytime=' . $pushInfo['delaytime'];           
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $str = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    }
}