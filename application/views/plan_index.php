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
            
            <li class="ellipsis <?php if ($planId == $value['id']) { echo 'active'; } ?>"><a href="/plan?planId=<?php echo $value['id'];?>" title="<?php echo $value['plan_name'];?>"><span style="white-space:nowrap; display:block; overflow:hidden;text-overflow:ellipsis;"><i class="glyphicon glyphicon-folder-<?php if ($planId == $value['id']) { echo 'open';} else { echo 'close';}?>"></i>&nbsp;&nbsp;<?php echo $value['plan_name'];?></span></a></li>
            <?php } ?>
          </ul>
          <?php } ?>
        </div><!-- col-sm-3 -->
        <div class="col-sm-9 col-lg-10">
          <?php if ($planFolder) {?><div class="mb10" align="right"><a href="/issue/add?planId=<?php echo $currPlan['id'];?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> 添加任务</a></div><?php } ?>
          <?php if ($planId) {?>
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="pull-right">
                <div class="btn-group move" style="display:none">
                  <div class="btn-group nomargin">
                    <button data-toggle="dropdown" class="btn btn-sm btn-info dropdown-toggle tooltips" title="只有新建的任务才可以移动" type="button" style="text-transform:uppercase;">
                      把选中的任务移动到 <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <?php foreach ($planFolder as $key => $value) {?>
                      <?php if ($value['id'] != $planId) {?>
                      <li><a href="javascript:;" class="move-issue" data-planid="<?php echo $value['id']; ?>"><?php echo $value['plan_name'];?></a></li>
                      <?php } ?>
                      <?php } ?>
                    </ul>
                  </div>
                </div>
                <div class="btn-group">
                  <div class="btn-group nomargin">
                    <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="根据工作流筛选" style="text-transform:uppercase;">
                      <i class="glyphicon glyphicon-folder-<?php if ($flow) { echo 'open'; } else { echo 'close'; }?> mr5"></i> <?php if ($flow) { echo $workflowfilter[$flow]['name']; } else { echo '工作流筛选'; }?>
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
              <h5 class="subtitle mb5"><?php echo $currPlan['plan_name']; ?> 计划的内容</h5>
              <p class="text-muted">查询结果：<?php echo $total;?></p>
              <div class="table-responsive">
                <table class="table table-email">
                  <tbody>
                    <?php
                      if ($rows) {
                        $weekarray=array("日","一","二","三","四","五","六");
                        if (file_exists('./cache/users.conf.php'))
                            require './cache/users.conf.php';
                        foreach ($rows as $value) {
                          $timeDay = date("Ymd", $value['add_time']);
                          if (!isset($timeGroup[$timeDay])) {
                            if ($timeDay == date("Ymd", time())) {
                              $day = '<span style="color:green">今天</span>';
                            } else {
                              $day = date('Y-m-d', $value['add_time']).' 星期'.$weekarray[date("w",$value['add_time'])];
                            }
                            echo '<tr><td colspan="8"><span class="fa fa-calendar"></span> 创建时间：'.$day.'</td></tr>';
                          }
                        $timeGroup[$timeDay] = 1;
                    ?>
                    <tr class="unread">
                      <td>
                        <div class="ckbox ckbox-success">
                          <input type="checkbox" class="chk" name="itemchk" id="checkbox<?php echo $value['id'];?>" value="<?php echo $value['id'];?>">
                          <label for="checkbox<?php echo $value['id'];?>"></label>
                        </div>
                      </td>
                      <td>
                        <a href="javascript:;" item-id="<?php echo $value['id'];?>" class="star<?php if ($this->uri->segment(2, '') == 'star') { echo ' star-checked'; } else { if (isset($star[$value['id']])) echo ' star-checked'; }?>"><i class="glyphicon glyphicon-star"></i></a>
                      </td>
                      <td align="center" width="40px">
                        <a href="/conf/profile/<?php echo $value['add_user'];?>" class="pull-left">
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
                      <td><?php if ($value['level']) {?><?php echo "<strong style='color:#ff0000;' title='".$level[$value['level']]['alt']."'>".$level[$value['level']]['name']."</strong> ";?><?php } ?> <a href="/issue/view/<?php echo $value['id'];?>" target="_blank"><?php if ($value['status'] == '-1') { echo '<del>'.$value['issue_name'].'</del>'; } else { echo $value['issue_name']; } ?></a><?php if ($value['status'] == '-1') echo ' <span class="label label-default">已删除</span>'; ?>
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
          <?php if ($planId && $currPlan) {?>
          <div class="panel panel-default">
            <div class="panel-body">
              <h5 class="subtitle subtitle-lined">计划进度</h5>
              <?php
              $timeline = unserialize($currPlan['timeline']);
              $endtime = $currPlan['endtime'];
              $timeline['online'] > $currPlan['endtime'] && $endtime = $timeline['online'];
              $total = $endtime - $currPlan['startime'];
              $devPer = sprintf("%.2f", ($timeline['test'] - $timeline['dev'])/$total)*100;
              $testPer = sprintf("%.2f", ($timeline['test'] - $timeline['dev'])/$total)*100;
              $onlinePer = sprintf("%.2f", ($timeline['online'] - $timeline['test'])/$total)*100;
              ?>
              <span class="sublabel">实际用时（需求创建：<?php echo date("Y/m/d H:i:s", $timeline['new']); ?>, 开发用时：<?php echo date("Y/m/d H:i:s", $timeline['dev']); ?>, 测试用时：<?php echo date("Y/m/d H:i:s", $timeline['test']); ?>, 上线用时：<?php echo date("Y/m/d H:i:s", $timeline['online']); ?>）</span>
              <div class="progress progress-sm">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $devPer; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $devPer; ?>%"></div>
                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $testPer; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $testPer; ?>%"></div>
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $onlinePer; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $onlinePer; ?>%"></div>
              </div>
              <h5 class="subtitle subtitle-lined">计划概览</h5>
              <div class="table-responsive">
                <table class="table table-striped">
                  <tbody>
                    <tr>
                      <td width="100px">计划全称：</td>
                      <td><?php echo $currPlan['plan_name']?><?php if ($this->input->cookie('uids') == $currPlan['add_user']) { ?> <a href="javascript:;" data-target="#myModal-plan" data-toggle="modal" id="plan-edit">编辑</a> <a href="javascript:;" id="plan-del">删除</a><?php } ?></td>
                      <td width="120px">提测成功率：</td>
                      <td><span class="label label-info" id="rate">计算中</span> <i class="glyphicon glyphicon-question-sign tooltips" title="提测成功率越低，代表质量越差。"></i></td>
                    </tr>
                    <tr>
                      <td width="100px">创建人：</td>
                      <td><a href="/conf/profile/<?php echo $currPlan['add_user'];?>" class="pull-left face"><img alt="" src="/static/avatar/<?php echo $users[$currPlan['add_user']]['username'];?>.jpg" align="absmiddle" title="创建人：<?php echo $users[$currPlan['add_user']]['realname'];?>"></a></td>
                      <td width="120px">参与人员：</td>
                      <td>
                        <?php 
                        if ($team) { 
                          foreach ($team as $key => $value) {
                        ?>
                        <a href="/conf/profile/<?php echo $value['accept_user'];?>" class="pull-left face"><img alt="" src="/static/avatar/<?php echo $users[$value['accept_user']]['username'];?>.jpg" align="absmiddle" title="<?php echo $users[$value['accept_user']]['realname'];?>"></a> 
                        <?php
                          }
                        } else {
                          echo 'N/A';
                        }
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td width="100px">创建时间：</td>
                      <td><?php echo date("Y/m/d H:i:s", $currPlan['add_time']);?></td>
                      <td width="120px">当前进度：</td>
                      <td><?php if ($currPlan['state'] == '1') { ?><span class="label label-default">新建</span><?php } ?><?php if ($currPlan['state'] == '2') { ?><span class="label label-primary">开发中</span><?php } ?><?php if ($currPlan['state'] == '3') { ?><span class="label label-warning">测试中</span><?php } ?><?php if ($currPlan['state'] == '4') { ?><span class="label label-success">已上线</span><?php } ?></td>
                    </tr>
                    <tr>
                      <td width="100px">开始时间：</td>
                      <td><?php echo date("Y/m/d H:i:s", $currPlan['startime']);?></td>
                      <td width="120px">截止时间：</td>
                      <td><?php echo date("Y/m/d H:i:s", $currPlan['endtime']);?></td>
                    </tr>
                    <tr>
                      <td width="100px">规划时长：</td>
                      <td><?php echo timediff($currPlan['startime'], $currPlan['endtime']);?></td>
                      <td width="120px">距离结束：</td>
                      <td>
                        <?php
                        if ($currPlan['timeline']) {
                          $timeline = unserialize($currPlan['timeline']);
                          if (isset($timeline['online'])) {
                            echo timediff($timeline['online'], $currPlan['endtime'], 0, 1);
                          } else {
                            echo '<div id="clock"></div>';
                          }
                        } else {
                          echo '<div id="clock"></div>';
                        }
                        ?>
                        </td>
                    </tr>
                    <tr>
                      <td width="100px">简介：</td>
                      <td colspan="3"><?php echo nl2br($currPlan['plan_discription']);?></td>
                    </tr>
                  </tbody>
                </table>
              </div><!-- table-responsive -->
              <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>说明：</strong>计划有四种状态：“新建”，“开发”，“测试”，“上线”。计划状态的改变有手动方式和自动方式（通过后台Worker监控每个计划是否达到状态变更标准），除非计划变更为上线状态，否则，<code>距离结束时间</code>会一直走。上线状态变更的时间减去计划规划的截止时间，就是该计划的误差时间。这个误差时间是一个非常重要的参考值。<br />功能正在优化中，预计5.22号之前上线。
              </div>
            </div>
          </div>
          <?php } ?>
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
      <input type="hidden" name="plan_id" id="plan_id" value="0" />
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
<script src="/static/js/jquery.countdown.min.js"></script>

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
      location.href = '/plan';
    }, 2000);
  } else {
    jQuery.gritter.add({
      title: '提醒',
      text: data.error,
        class_name: 'growl-danger',
        image: '/static/images/screen.png',
      sticky: false,
      time: ''
    });
    setTimeout(function(){
      location.href = '/plan';
    }, 3000);
  }
}

jQuery(document).ready(function(){
  
  "use strict"

  $("#basicForm").submit(function(){
    $(this).ajaxSubmit({
      type:"post",
      url: "/plan/add_ajax/"+<?php if ($currPlan['id']) { echo $currPlan['id']; } else { echo '0'; } ?>,
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

  $('#clock').countdown('<?php echo date("Y/m/d H:i:s", $currPlan['endtime']);?>', {elapse: true})
  .on('update.countdown', function(event) {
    var format = '%-H 小时 %M 分钟 %S 秒';
    if(event.offset.days > 0) {
      format = '%-d 天%!d ' + format;
    }
    if(event.offset.weeks > 0) {
      format = '%-w 周%!w ' + format;
    }
    var $this = $(this);
    if (event.elapsed) {
     $this.html(event.strftime('<span style="color:red">超出：'+format+'</span>'));
    } else {
     $this.html(event.strftime('<span style="color:green">仅剩：'+format+'</span>'));
    }
  });

  //获取提测成功率
  setTimeout(function () {
    //获取我受理的任务量统计
    $.ajax({
      type: "GET",
      url: "/plan/rate/<?php echo $planId; ?>",
      dataType: "text",
      success: function(data){
        if (data) {
          $("#rate").text(data);
        }
      }
    });
  }, 1000);

  //编辑计划
  $("#plan-edit").click(function(){
    $(".modal-title").text('编辑计划');
    $.ajax({
      type: "GET",
      url: "/plan/get_info/"+<?php if ($currPlan['id']) { echo $currPlan['id']; } else { echo '0'; } ?>,
      dataType: "JSON",
      success: function(data){
        if (data.status) {
          $("#plan_name").val(data.output.plan_name);
          $("#plan_discription").val(data.output.plan_discription);
          $("#startime").val(data.output.startime);
          $("#endtime").val(data.output.endtime);
          $("#plan_id").val(<?php echo $currPlan['id']; ?>);
        } else {
         $(".modal-body").html(data.message);
        }
      }
    });
  });

  $("#plan-del").click(function(){
    var c = confirm('任务已经移出完毕了吗？');
    if(c) {
      $.ajax({
        type: "GET",
        url: "/plan/del/"+<?php if ($currPlan['id']) { echo $currPlan['id']; } else { echo '0'; } ?>,
        dataType: "JSON",
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
            setTimeout(function(){
              location.href = '/plan';
            }, 2000);
          } else {
            jQuery.gritter.add({
              title: '提醒',
              text: data.error,
                class_name: 'growl-danger',
                image: '/static/images/screen.png',
              sticky: false,
              time: ''
            });
            setTimeout(function(){
              location.href = '/plan';
            }, 3000);
          }
        }
      });
    }
  });

  $(".chk").change(function() {
    var num = $(":input[name=itemchk]:checked").length;
    if (num) {
      $(".move").show();
    } else {
      $(".move").hide();
    }
  });

  //移动任务
  $(".move-issue").click(function() {
    var planId = $(this).attr('data-planid');
    var chk_value = [];
    $('input[name="itemchk"]:checked').each(function() {
      chk_value.push($(this).val());
    });
    $.ajax({
      type: "POST",
      url: "/plan/move_issue",
      data: "planId="+planId+"&issueId="+chk_value+"&<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>",
      dataType: "JSON",
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
          setTimeout(function(){
            location.href = '/plan?planId='+planId;
          }, 1000);
        } else {
          jQuery.gritter.add({
            title: '提醒',
            text: data.error,
              class_name: 'growl-danger',
              image: '/static/images/screen.png',
            sticky: false,
            time: ''
          });
        }
      }
    });
  });
  
});
</script>

</body>
</html>
