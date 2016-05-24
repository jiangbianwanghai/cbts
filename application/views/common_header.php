<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title><?php echo $PAGE_TITLE;?> - CITS</title>

  <link href="/static/css/style.default.css" rel="stylesheet">
  <link href="/static/css/jquery.gritter.css" rel="stylesheet">
  <link href="/static/css/morris.css" rel="stylesheet">
  <link href="/static/css/bootstrap-editable.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="/static/simditor-2.3.6/styles/simditor.css" />

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script>alert("您的浏览器版本过低，建议您使用Chrome,Firefox,IE9及以上版本")</script>
  <script src="/static/js/html5shiv.js"></script>
  <script src="/static/js/respond.min.js"></script>
  <![endif]-->
    
</head>

<body class="leftpanel-collapsed">
  <?php if ($_SERVER['SERVER_ADDR'] == '192.168.8.91') { ?><div style="display:none;"><script language="javascript" type="text/javascript" src="http://js.users.51.la/18869959.js"></script></div><?php } ?>
<section>

  <div class="leftpanel">

    <div class="logopanel" align="center">
        <strong style="font-family: Arial, Helvetica; ">CITS-巧克力任务跟踪系统</strong> <sup><a target="_blank" href="https://github.com/jiangbianwanghai/cbts/releases" title="点击查看版本更新日志"><span class="badge badge-info">0.2.1</span></a></sup>
    </div><!-- logopanel -->

    <div class="leftpanelinner">

      <h5 class="sidebartitle">快捷导航</h5>
      <ul class="nav nav-pills nav-stacked nav-bracket">
        <li<?php if (($this->uri->segment(1, 'admin') == 'admin') || $this->uri->segment(2, '') == 'profile') echo ' class="active"';?>><a href="/"><i class="fa fa-home"></i> <span>我的面板</span></a></li>
        <li<?php if ($this->uri->segment(1, '') == 'plan') echo ' class="active"';?>><a href="/plan"><i class="fa fa-thumb-tack"></i> <span>计划管理</span></a></li>
        <li<?php if ($this->uri->segment(1, '') == 'bug') echo ' class="active"';?>><a href="/bug"><i class="fa fa-bug"></i> <span>Bug管理</span></a></li>
        <li<?php if ($this->uri->segment(1, '') == 'issue') echo ' class="active"';?>><a href="/issue"><i class="fa fa-tasks"></i> <span>任务管理</span></a>
        </li>
        <li<?php if ($this->uri->segment(1, '') == 'test') echo ' class="active"';?>><a href="/test/plaza"><i class="fa fa-cloud-upload"></i> <span>提测管理</span></a>
        </li>
        <li<?php if ($this->uri->segment(1, '') == 'analytics') echo ' class="active"';?>><a href="/analytics"><i class="fa fa-medkit"></i> <span>数据分析</span></a>
        </li>
        <li<?php if ($this->uri->segment(1, '') == 'conf' && $this->uri->segment(2, '') != 'profile') echo ' class="active"';?>><a href="/conf/repos_list"><i class="fa fa-suitcase"></i> <span>代码库管理</span></a>
        </li>
      </ul>

    </div><!-- leftpanelinner -->
  </div><!-- leftpanel -->

  <div class="mainpanel">

    <div class="headerbar">

      <a class="menutoggle"><i class="fa fa-bars"></i></a>

      <div class="topnav">
        <ul class="nav nav-horizontal">
          <li class="active"><a href="/"><i class="fa fa-home"></i> <span>我的面板</span></a></li>
          <li class="nav-parent"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-list"></i> <span id="curr-project">
            <?php
            require './cache/project.conf.php';
            if ($this->input->cookie('projectId')&& isset($project[$this->input->cookie('projectId')])) {
              echo $project[$this->input->cookie('projectId')]['project_name']; 
            } else { echo '请选择项目团队'; }
            ?></span> <span class="caret"></span></a>
            <ul class="dropdown-menu children">
              <?php
              if (file_exists('./cache/project.conf.php')) {
                  require './cache/project.conf.php';
                  foreach ($project as $key => $value) {
                    echo "<li><a href=\"javascript:;\" class=\"set-project\" md5=\"".$value['md5']."\" project=\"".$value['project_name']."\">".$value['project_name']."</a></li>";
                  }
                  echo "<li class=\"divider\"></li>";
              }
              ?>
              <li><a href="javascript:;" data-toggle="modal" data-target="#myModal-project">添加项目团队</a></li>
            </ul>
          </li>
          <li>
            <?php
            $weekarray=array("日","一","二","三","四","五","六");
            echo "<a href=\"javascript:;\">今天是：".date("Y-m-d", time())." 星期".$weekarray[date("w",time())]." （".date("Y", time())."年的第 ".intval(date("W", time()))." 周）</a>";
            ?>
          </li>
        </ul>
      </div><!-- topnav -->

      <div class="header-right">
        <ul class="headermenu">
          <li>
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <img src="/static/avatar/<?php echo $this->input->cookie('username');?>.jpg" alt="" />
                <?php echo $this->input->cookie('realname');?>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <li><a href="http://192.168.8.91/markdown/"><i class="glyphicon glyphicon-question-sign"></i> 更新日志</a></li>
                <li><a href="/admin/logout"><i class="glyphicon glyphicon-log-out"></i> 退出</a></li>
              </ul>
            </div>
          </li>
          <li>
            <button id="chatview" class="btn btn-default tp-icon chat-icon">
                <i class="glyphicon glyphicon-comment"></i>
            </button>
          </li>
        </ul>
      </div><!-- header-right -->

    </div><!-- headerbar -->

    <!-- Modal -->
    <div class="modal fade" id="myModal-project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <form method="POST" id="addProject" action="/project/add_ajax" class="form-horizontal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">创建项目团队</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">名称 <span class="asterisk">*</span></label>
              <div class="col-sm-9">
                <input type="text" name="project_name" id="project_name" class="form-control project_name" required />
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-3 control-label">简介 <span class="asterisk">*</span></label>
              <div class="col-sm-9">
                <textarea rows="5" class="form-control" id="project_discription" name="project_discription" required></textarea>
              </div>
            </div>
          </div>
          <?php
          $csrf = array(
              'name' => $this->security->get_csrf_token_name(),
              'hash' => $this->security->get_csrf_hash()
          );
          ?>
          <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <button class="btn btn-primary" id="btnSubmit-project">提交</button>
          </div>
        </div><!-- modal-content -->
      </div><!-- modal-dialog -->
      </form>
    </div><!-- modal -->
    <a href="http://form.mikecrm.com/yrR8O7" target="_blank" style="position:fixed;z-index:999;right:5px;bottom: 20px;display: inline-block;width: 30px;border-radius: 5px;color:white;font-size:14px;line-height:17px;background: #2476CE;box-shadow: 0 0 5px #666;word-wrap: break-word;padding:7px;border: 1px solid white;">使用反馈</a>
