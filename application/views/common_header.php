<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title><?php echo $PAGE_TITLE;?> - 巧克力(cbts)提测系统-Beta</title>

  <link href="/static/css/style.default.css" rel="stylesheet">
  <link href="/static/css/jquery.gritter.css" rel="stylesheet">
  <link href="/static/css/morris.css" rel="stylesheet">
  <link href="/static/css/bootstrap-editable.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="/static/simditor-2.3.6/styles/simditor.css" />

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="/static/js/html5shiv.js"></script>
  <script src="/static/js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="leftpanel-collapsed">

<section>

  <div class="leftpanel">

    <div class="logopanel" align="center">
        <strong style="font-family: Arial, Helvetica; ">CBTS-巧克力任务跟踪系统</strong> <sup><a target="_blank" href="http://192.168.8.91/markdown/" title="点击查看版本更新日志"><span class="badge badge-info">2.2.0</span></a></sup>
    </div><!-- logopanel -->

    <div class="leftpanelinner">

      <h5 class="sidebartitle">快捷导航</h5>
      <ul class="nav nav-pills nav-stacked nav-bracket">
        <li<?php if (($this->uri->segment(1, '') == '' && $this->uri->segment(2, '') == '') || $this->uri->segment(2, '') == 'profile') echo ' class="active"';?>><a href="/"><i class="fa fa-home"></i> <span>我的控制台</span></a></li>
        <li<?php if ($this->uri->segment(1, '') == 'bug') echo ' class="active"';?>><a href="/bug"><i class="fa fa-bug"></i> <span>Bug管理</span></a></li>
        <li class="nav-parent<?php if ($this->uri->segment(1, '') == 'issue') echo ' active';?>"><a href="javascript:;"><i class="fa fa-tasks"></i> <span>任务管理</span></a>
          <ul class="children"<?php if ($this->uri->segment(1, '') == 'issue') echo ' style="display: block"';?>>
            <li<?php if ($this->uri->segment(2, '') == 'add') echo ' class="active"';?>><a href="/issue/add"><i class="fa fa-caret-right"></i> 添加任务</a></li>
            <li<?php if ($this->uri->segment(1, '') == 'issue' && ($this->uri->segment(2, '') == 'my' || $this->uri->segment(2, '') == 'edit')) echo ' class="active"';?>><a href="/issue/my"><i class="fa fa-caret-right"></i> 我的任务</a></li>
            <li<?php if ($this->uri->segment(1, '') == 'issue' && $this->uri->segment(2, '') == 'todo') echo ' class="active"';?>><a href="/issue/todo"><i class="fa fa-caret-right"></i> 我的受理</a></li>
            <li<?php if ($this->uri->segment(1, '') == 'issue' && ($this->uri->segment(2, '') == 'plaza' || $this->uri->segment(2, '') == 'view')) echo ' class="active"';?>><a href="/issue/plaza"><i class="fa fa-caret-right"></i> 任务广场</a></li>
            <li<?php if ($this->uri->segment(1, '') == 'issue' && $this->uri->segment(2, '') == 'analytics') echo ' class="active"';?>><a href="/issue/analytics"><i class="fa fa-caret-right"></i> 任务统计</a></li>
          </ul>
        </li>
        <li class="nav-parent<?php if ($this->uri->segment(1, '') == 'test') echo ' active';?>"><a href="javascript:;"><i class="fa fa-medkit"></i> <span>提测管理</span></a>
          <ul class="children"<?php if ($this->uri->segment(1, '') == 'test') echo ' style="display: block"';?>>
            <li<?php if (($this->uri->segment(2, '') == 'my' || $this->uri->segment(2, '') == 'add') && $this->uri->segment(1, '') == 'test') echo ' class="active"';?>><a href="/test/my"><i class="fa fa-caret-right"></i> 我的提测</a></li>
            <li<?php if ($this->uri->segment(1, '') == 'test' && ($this->uri->segment(2, '') == 'plaza' || $this->uri->segment(2, '') == 'edit'  || $this->uri->segment(2, '') == 'repos')) echo ' class="active"';?>><a href="/test/plaza"><i class="fa fa-caret-right"></i> 提测广场</a></li>
            <li<?php if ($this->uri->segment(1, '') == 'test' && $this->uri->segment(2, '') == 'todo') echo ' class="active"';?>><a href="/test/todo"><i class="fa fa-caret-right"></i> 我的待测</a></li>
            <li<?php if ($this->uri->segment(1, '') == 'test' && $this->uri->segment(2, '') == 'analytics') echo ' class="active"';?>><a href="/test/analytics"><i class="fa fa-caret-right"></i> 提测统计</a></li>
          </ul>
        </li>
        <li class="nav-parent<?php if ($this->uri->segment(1, '') == 'conf' && $this->uri->segment(2, '') != 'profile') echo ' active';?>"><a href="javascript:;"><i class="fa fa-suitcase"></i> <span>代码库管理</span></a>
          <ul class="children"<?php if ($this->uri->segment(1, '') == 'conf' && $this->uri->segment(2, '') != 'profile') echo ' style="display: block"';?>>
            <li<?php if ($this->uri->segment(2, '') == 'repos') echo ' class="active"';?>><a href="/conf/repos"><i class="fa fa-caret-right"></i> 添加代码库</a></li>
            <li<?php if ($this->uri->segment(2, '') == 'repos_list' || $this->uri->segment(2, '') == 'repos_edit') echo ' class="active"';?>><a href="/conf/repos_list"><i class="fa fa-caret-right"></i> 代码库管理</a></li>
          </ul>
        </li>
      </ul>

    </div><!-- leftpanelinner -->
  </div><!-- leftpanel -->

  <div class="mainpanel">

    <div class="headerbar">

      <a class="menutoggle"><i class="fa fa-bars"></i></a>

      <div class="topnav">
        <ul class="nav nav-horizontal">
          <li class="active"><a href="/"><i class="fa fa-home"></i> <span>控制台</span></a></li>
          <li class="nav-parent"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-list"></i> 项目 <span class="caret"></span></a>
            <ul class="dropdown-menu children">
              <?php
              if (file_exists('./cache/project.conf.php')) {
                  require './cache/project.conf.php';
                  foreach ($project as $key => $value) {
                    echo "<li><a href=\"/?projectId=".$value['id']."\">".$value['project_name']."</a></li>";
                  }
                  echo "<li class=\"divider\"></li>";
              }
              ?>
              <li><a href="javascript:;">添加项目</a></li>
            </ul>
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
                <li><a href="<?php echo site_url("admin/logout");?>"><i class="glyphicon glyphicon-log-out"></i> 退出</a></li>
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