<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class bug extends CI_Controller {

    /**
     * 添加表单
     */
    public function add() {
        //设置页面标题
    	$data['PAGE_TITLE'] = '提交BUG';

        //获取提测ID
        $testId = $this->uri->segment(3, 0);

        //验证ID合法性
        $this->load->model('Model_test', 'test', TRUE);
        $data['row'] = $this->test->fetchOne($testId);
        if (!$data['row']) {
            exit("查询数据错误.");
        }

        //获取所属任务信息
        $this->load->model('Model_issue', 'issue', TRUE);
        $issueRow = $this->issue->fetchOne($data['row']['issue_id']);
        $data['row']['issue_id'] = $issueRow['id'];
        $data['row']['issue_name'] = $issueRow['issue_name'];

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
        $this->load->model('Model_test', 'test', TRUE);
        $row = $this->test->fetchOne($this->input->post('test_id'));
        if (!$row) {
            exit("查询数据错误.");
        }
        $post = array(
            'issue_id' => $this->input->post('issue_id'),
            'test_id' => $this->input->post('test_id'),
            'subject' => $this->input->post('subject'),
            'content' => $this->input->post('content'),
            'add_user' => $this->input->cookie('uids'),
            'add_time' => time(),
            'accept_user' => $row['add_user'],
            'accept_time' => time(),
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

    /**
     * 提测详情
     */
    public function view() {
        $id = $this->uri->segment(3, 0);
        $this->load->model('Model_bug', 'bug', TRUE);
        $row = $this->bug->fetchOne($id);
        echo '<p>'.nl2br($row['content']).'</p>';
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