<?php include('common_header.php');?>
<link rel="stylesheet" type="text/css" href="/static/css/jquery.datetimepicker.css"/>
    <div class="pageheader">
      <h2><i class="fa fa-thumb-tack"></i> 计划管理 <span>当前计划列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/plan">计划管理</a></li>
          <li class="active">当前计划列表</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel panel-email">
      <div class="row">
        <div class="col-sm-3 col-lg-2">
          <a href="javascript:;" class="btn btn-danger btn-block btn-compose-email" data-toggle="modal" data-target="#myModal-plan"><i class="fa fa-plus"></i> 新建计划</a>
          <div class="mb30"></div>
          <?php if ($planFolder) {?>
          <h5 class="subtitle">已有计划</h5>
          <ul class="nav nav-pills nav-stacked nav-email mb20">
            <?php foreach ($planFolder as $key => $value) {?>
            
            <li<?php if ($planId == $value['id']) {?> class="active"<?php } ?>><a href="/plan?planId=<?php echo $value['id'];?>"><i class="glyphicon glyphicon-folder-<?php if ($planId == $value['id']) { echo 'open';} else { echo 'close';}?>"></i> <?php echo $value['plan_name'];?></a></li>
            <?php } ?>
          </ul>
          <?php } ?>
        </div><!-- col-sm-3 -->
        <div class="col-sm-9 col-lg-10">
          <?php if ($planId && $currPlan) {?>
          <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            计划全称：<strong><?php echo $currPlan['plan_name']?></strong> / 起至时间：<?php echo date("Y-m-d H:i", $currPlan['startime']).' - '.date("Y-m-d H:i", $currPlan['endtime']);?> / 时长：<?php echo timediff($currPlan['startime'], $currPlan['endtime']);?> / 距离结束：<?php echo timediff(time(), $currPlan['endtime']);?> <a href="/issue/add?planId=<?php echo $currPlan['id'];?>"><i class="fa fa-plus"></i> 添加任务</a>
          </div>
          <?php } ?>
          <?php if ($planId) {?>
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="pull-right">
                <div class="btn-group mr10">
                  <div class="btn-group nomargin">
                    <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="根据工作流筛选" style="text-transform:uppercase;">
                      <i class="glyphicon glyphicon-folder-<?php if ($flow) { echo 'open'; } else { echo 'close'; }?> mr5"></i> <?php if ($flow) { echo $workflowfilter[$flow]['name']; } else { echo '类型筛选'; }?>
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                      <?php if ($flow) {?>
                      <li><a href="/plan/index/0/<?php echo $taskType;?><?php if ($planId) echo '?planId='.$planId;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部</a></li>
                      <?php } ?>
                      <?php foreach ($workflow as $key => $value) {?>
                      <?php if ($flow != $value['en_name'] || !$flow) {?><li><a href="/plan/index/<?php echo $value['en_name'];?>/<?php echo $taskType;?><?php if ($planId) echo '?planId='.$planId;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> <?php echo $value['name'];?></a></li><?php } ?>
                      <?php } ?>
                    </ul>
                  </div>
                  <div class="btn-group nomargin">
                    <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="根据类型筛选" style="text-transform:uppercase;">
                      <i class="glyphicon glyphicon-folder-<?php if ($taskType) { echo 'open'; } else { echo 'close'; }?> mr5"></i> <?php if ($taskType) { echo $taskType; } else { echo '类型筛选'; }?>
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                      <?php if ($taskType) {?>
                      <li><a href="/plan/index/<?php echo $flow;?>/0<?php if ($planId) echo '?planId='.$planId;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部</a></li>
                      <?php } ?>
                      <?php if ($taskType != 'task' || !$taskType) {?><li><a href="/plan/index/<?php echo $flow;?>/task<?php if ($planId) echo '?planId='.$planId;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> TASK</a></li><?php } ?>
                      <?php if ($taskType != 'bug' || !$taskType) {?><li><a href="/plan/index/<?php echo $flow;?>/bug<?php if ($planId) echo '?planId='.$planId;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> BUG</a></li><?php } ?>
                    </ul>
                  </div>
                </div>
              </div><!-- pull-right -->
              <h5 class="subtitle mb5">计划内容</h5>
              <p class="text-muted">查询结果：<?php echo $total;?></p>
              <div class="table-responsive">
                <table class="table table-email">
                  <tbody>
                    <?php
                      if ($rows) {
                        if (file_exists('./cache/users.conf.php'))
                            require './cache/users.conf.php';
                        foreach ($rows as $value) {
                    ?>
                    <tr class="unread">
                      <td>
                        <div class="ckbox ckbox-success">
                          <input type="checkbox" id="checkbox<?php echo $value['id'];?>">
                          <label for="checkbox<?php echo $value['id'];?>"></label>
                        </div>
                      </td>
                      <td>
                        <a href="javascript:;" item-id="<?php echo $value['id'];?>" class="star<?php if ($this->uri->segment(2, '') == 'star') { echo ' star-checked'; } else { if (isset($star[$value['id']])) echo ' star-checked'; }?>"><i class="glyphicon glyphicon-star"></i></a>
                      </td>
                      <td align="center" width="40px">
                        <a href="#" class="pull-left">
                          <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['add_user']]['username']?>.jpg" align="absmiddle" title="添加人：<?php echo $users[$value['add_user']]['realname'];?>"></div>
                        </a>
                      </td>
                      <td align="center" width="40px">
                        <?php if ($value['accept_user']) {?>
                        <a href="/conf/profile/<?php echo $value['accept_user'];?>" class="pull-left" target="_blank">
                          <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['accept_user']]['username']?>.jpg" align="absmiddle" title="当前受理人：<?php echo $users[$value['accept_user']]['realname'];?>"></div>
                        </a>
                        <?php } else { echo '-'; } ?>
                      </td>
                      <td width="80px">
                        <?php echo '<span class="label label-'.$workflow[$value['workflow']]['span_color'].'">'.$workflow[$value['workflow']]['name'].'</span>'; ?>
                      </td>
                      <td align="center" width="30px">
                        <?php if ($value['type'] == 2) {?><i class="fa fa-bug tooltips" data-toggle="tooltip" title="BUG"></i><?php } ?><?php if ($value['type'] == 1) {?><i class="fa fa-magic tooltips" data-toggle="tooltip" title="TASK"></i><?php } ?>
                      </td>
                      <td><?php if ($value['level']) {?><?php echo "<strong style='color:#ff0000;' title='".$level[$value['level']]['alt']."'>".$level[$value['level']]['name']."</strong> ";?><?php } ?> <a href="/issue/view/<?php echo $value['id'];?>" target="_blank"><?php echo $value['issue_name'];?></a></span>
                      </td>
                      <td><span class="media-meta pull-right"><?php echo date("Y/m/d H:i", $value['add_time'])?></span></td>
                    </tr>
                    <?php
                        }
                      } else {
                    ?>
                      <tr><td colspan="5" align="center">无数据~</td></tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div><!-- table-responsive -->
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-sm-9 -->
      </div><!-- row -->
      <p class="text-right"><small>页面执行时间 <em>{elapsed_time}</em> 秒 使用内存 {memory_usage}</small></p>
    </div><!-- contentpanel -->
    <?php } ?>
  </div><!-- mainpanel -->
  <?php include('common_users.php');?>
</section>

<!-- Modal -->
<div class="modal fade" id="myModal-plan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <form method="POST" id="basicForm" action="/plan/add_ajax" class="form-horizontal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">新建计划</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="col-sm-3 control-label">计划名称 <span class="asterisk">*</span></label>
          <div class="col-sm-9">
            <input type="text" name="plan_name" id="plan_name" class="form-control" placeholder="最少5个字符，最长40个字符" required />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">计划简介 <span class="asterisk">*</span></label>
          <div class="col-sm-9">
            <textarea rows="5" class="form-control" id="plan_discription" name="plan_discription" placeholder="最少5个字符，最长300个字符" required></textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">开始时间 <span class="asterisk">*</span></label>
          <div class="col-sm-9">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
              <input type="text" class="form-control" style="width:150px;" placeholder="yyyy/mm/dd 00:00" id="startime" name="startime" required>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">结束时间 <span class="asterisk">*</span></label>
          <div class="col-sm-9">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
              <input type="text" class="form-control" style="width:150px;" placeholder="yyyy/mm/dd 00:00" id="endtime" name="endtime" required>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" />
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" id="btnSubmit">提交</button>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
  </form>
</div><!-- modal -->

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.validate.min.js"></script>
<script src="/static/js/jquery.form.js"></script>
<script src="/static/js/jquery.gritter.min.js"></script>

<script src="/static/js/jquery.datatables.min.js"></script>
<script src="/static/js/select2.min.js"></script>

<script src="/static/js/jquery.datetimepicker.full.js"></script>

<script src="/static/js/custom.js"></script>
<script src="/static/js/cits.js"></script>
<script>
function validForm(formData,jqForm,options){
  return $("#basicForm").valid();
}
function callBack(data) {
  $("#btnSubmit").attr("disabled", true);
  if(data.status){
    jQuery.gritter.add({
      title: '提醒',
      text: data.message,
        class_name: 'growl-success',
        image: '/static/images/screen.png',
      sticky: false,
      time: ''
    });
    setTimeout(function(){
      location.href = data.url;
    }, 2000);
  } else {
    jQuery.gritter.add({
      title: '提醒',
      text: data.message,
        class_name: 'growl-danger',
        image: '/static/images/screen.png',
      sticky: false,
      time: ''
    });
    setTimeout(function(){
      location.href = data.url;
    }, 3000);
  }
}

jQuery(document).ready(function(){
  
  "use strict"

  $("#basicForm").submit(function(){
    $(this).ajaxSubmit({
      type:"post",
      url: "/plan/add_ajax",
      dataType: "JSON",
      beforeSubmit:validForm,
      success:callBack
    });
    return false;
  });

  $('#startime').datetimepicker({
    minDate:'<?php echo date("Y/m/d", time());?>',
  });
  $('#endtime').datetimepicker({
    minDate:'<?php echo date("Y/m/d", time()+86400);?>',
  });

  $('.star').click(function(){
      if(!jQuery(this).hasClass('star-checked')) {
          jQuery(this).addClass('star-checked');
          var id = jQuery(this).attr('item-id');
          $.ajax({
            type: "GET",
            dataType: "JSON",
            url: "/issue/star_ajax/"+id,
            success: function(data){
              if (data.status) {
                jQuery.gritter.add({
                  title: '提醒',
                  text: data.message,
                    class_name: 'growl-success',
                    image: '/static/images/screen.png',
                  sticky: false,
                  time: ''
                });
              } else {
                alert(data.message);
              } 
            }
          });
      } else {
        jQuery(this).removeClass('star-checked');
        var id = jQuery(this).attr('item-id');
        $.ajax({
          type: "GET",
          dataType: "JSON",
          url: "/issue/star_del/"+id,
          success: function(data){
            if (data.status) {
              jQuery.gritter.add({
                title: '提醒',
                text: data.message,
                  class_name: 'growl-success',
                  image: '/static/images/screen.png',
                sticky: false,
                time: ''
              });
            } else {
              alert(data.message);
            } 
          }
        });
      }
      return false;
  });
  
});
</script>

</body>
</html>
