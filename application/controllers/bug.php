<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class bug extends CI_Controller {

    public function index() {
        $data['PAGE_TITLE'] = '提交BUG';
        $folder = $data['folder'] = $this->uri->segment(3, 'all');
        $state = $this->uri->segment(4, 'all');
        $offset = $this->uri->segment(5, 0);
        $this->load->model('Model_bug', 'bug', TRUE);
        $this->config->load('extension', TRUE);
        $data['level'] = $this->config->item('level', 'extension');
        $config = $this->config->item('pages', 'extension');
        $rows = $this->bug->searchByMysql($folder, $state, $config['per_page'], $offset);
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

    public function view() {
        $data['PAGE_TITLE'] = 'Bug详情';
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_bug', 'bug', TRUE);
        $data['row'] = $this->bug->fetchOne($id);
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
    	$data['PAGE_TITLE'] = '提交BUG';
        $issueId = $this->uri->segment(3, 0);
        $this->load->model('Model_issue', 'issue', TRUE);
        $data['row'] = $this->issue->fetchOne($issueId);
        if (!$data['row'])
            exit("无此数据.");
        if (file_exists('./cache/repos.conf.php')) {
            require './cache/repos.conf.php';
            $data['repos'] = $repos;
        }
        if (file_exists('./cache/users.conf.php')) {
            require './cache/users.conf.php';
            $data['users'] = $users;
        }
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