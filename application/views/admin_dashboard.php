<?php include('common_header.php');?>

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> 我的面板 <span>显示关于你的所有任务</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">我的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">CITS</a></li>
          <li class="active">我的面板</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">
      <div class="col-sm-3 col-lg-2">
        <h5 class="subtitle">我的任务</h5>
        <ul class="nav nav-pills nav-stacked nav-email mb20">
          <li<?php if ($folder == 'to_me') {?> class="active"<?php } ?>><a href="/admin/index/to_me"><i class="glyphicon glyphicon-folder-<?php if ($folder == 'to_me') { echo 'open';} else { echo 'close';}?>"></i> 我负责的</a></li>
          <li<?php if ($folder == 'from_me') {?> class="active"<?php } ?>><a href="/admin/index/from_me"><i class="glyphicon glyphicon-folder-<?php if ($folder == 'from_me') { echo 'open';} else { echo 'close';}?>"></i> 我创建的</a></li>
          <li<?php if ($folder == 'partin') {?> class="active"<?php } ?>><a href="/admin/index/partin"><i class="glyphicon glyphicon-folder-<?php if ($folder == 'partin') { echo 'open';} else { echo 'close';}?>"></i> 我参与的</a></li>
        </ul>
        <div class="mb10"></div>
        <ul class="nav nav-pills nav-stacked nav-email">
          <li<?php if ($this->uri->segment(2, '') == 'star') {?> class="active"<?php } ?>><a href="/admin/star"><i class="glyphicon glyphicon-star"></i> 星标</a></li>
        </ul>
      </div><!-- col-sm-3 -->
      <div class="col-sm-9 col-lg-10">
        <div class="alert alert-warning">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <strong>说明：</strong>5月4号后增加了对任务过程参与人员信息记录的功能。之前的数据由于没有记录信息。所以，在没有补全老数据之前，"我参与的"信息要比“我负责的”信息少属于正常现象。
        </div>
        <div class="panel panel-default">
          <div class="panel-body">
            <?php if ($this->uri->segment(2, 'index') == 'index') { ?>
            <div class="pull-right">
              <div class="btn-group">
                <?php if ($projectListByIssue) {
                  if (file_exists('./cache/project.conf.php'))
                    require './cache/project.conf.php';
                ?>
                <div class="btn-group nomargin">
                  <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="根据绩效圈筛选">
                    <i class="glyphicon glyphicon-folder-<?php if ($projectMd5) { echo 'open'; } else { echo 'close'; }?> mr5"></i> <?php if ($projectMd5) { echo $project[$projectMd5]['project_name']; } else { echo '绩效圈筛选'; }?>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <?php if ($projectMd5) {?>
                    <li><a href="/admin/index/<?php echo $folder;?>/0/0/<?php echo $flow;?>/<?php echo $taskType;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部</a></li>
                    <?php } ?>
                    <?php 
                    foreach ($projectListByIssue as $key => $value) {
                      if ($projectMd5 != $value['md5'] || !$projectMd5) {
                    ?>
                    <li><a href="/admin/index/<?php echo $folder;?>/<?php echo $value['md5'];?>/<?php echo $planId;?>/<?php echo $flow;?>/<?php echo $taskType;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> <?php echo $value['project_name'];?></a></li>
                    <?php
                      }
                    } 
                    ?>
                  </ul>
                </div>
                <?php } ?>
                <div class="btn-group nomargin">
                  <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="请先选择绩效圈">
                    <i class="glyphicon glyphicon-folder-<?php if ($planId) { echo 'open'; } else { echo 'close'; }?> mr5"></i> <?php if ($planId) { echo $planArr[$planId]['plan_name']; } else { echo '计划筛选'; }?>
                    <span class="caret"></span>
                  </button>
                  <?php if ($projectMd5) {?>
                  <ul class="dropdown-menu">
                    <?php if ($planId) {?>
                    <li><a href="/admin/index/<?php echo $folder;?>/<?php echo $projectMd5;?>/0/<?php echo $flow;?>/<?php echo $taskType;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部</a></li>
                    <?php } ?>
                    <?php 
                    foreach ($planListByIssue as $key => $value) {
                      if ($planId != $value['id'] || !$planId) {
                    ?>
                    <li><a href="/admin/index/<?php echo $folder;?>/<?php echo $projectMd5;?>/<?php echo $value['id'];?>/<?php echo $flow;?>/<?php echo $taskType;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> <?php echo $value['plan_name'];?></a></li>
                    <?php
                      }
                    } 
                    ?>
                  </ul>
                  <?php } ?>
                </div>
                <div class="btn-group nomargin">
                    <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="根据工作流筛选" style="text-transform:uppercase;">
                      <i class="glyphicon glyphicon-folder-<?php if ($flow) { echo 'open'; } else { echo 'close'; }?> mr5"></i> <?php if ($flow) { echo $workflowfilter[$flow]['name']; } else { echo '工作流筛选'; }?>
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                      <?php if ($flow) {?>
                      <li><a href="/admin/index/<?php echo $folder;?>/<?php echo $projectMd5;?>/<?php echo $planId;?>/0/<?php echo $taskType;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部</a></li>
                      <?php } ?>
                      <?php foreach ($workflow as $key => $value) {?>
                      <?php if ($flow != $value['en_name'] || !$flow) {?><li><a href="/admin/index/<?php echo $folder;?>/<?php echo $projectMd5;?>/<?php echo $planId;?>/<?php echo $value['en_name'];?>/<?php echo $taskType;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> <?php echo $value['name'];?></a></li><?php } ?>
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
                    <li><a href="/admin/index/<?php echo $folder;?>/<?php echo $projectMd5;?>/<?php echo $planId;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部</a></li>
                    <?php } ?>
                    <?php if ($taskType != 'task' || !$taskType) {?><li><a href="/admin/index/<?php echo $folder;?>/<?php echo $projectMd5;?>/<?php echo $planId;?>/<?php echo $flow;?>/task"><i class="glyphicon glyphicon-folder-open mr5"></i> TASK</a></li><?php } ?>
                    <?php if ($taskType != 'bug' || !$taskType) {?><li><a href="/admin/index/<?php echo $folder;?>/<?php echo $projectMd5;?>/<?php echo $planId;?>/<?php echo $flow;?>/bug"><i class="glyphicon glyphicon-folder-open mr5"></i> BUG</a></li><?php } ?>
                  </ul>
                </div>
              </div>
            </div><!-- pull-right -->
            <?php } ?>
            <h5 class="subtitle mb5"><?php if ($folder == 'to_me') { echo '我负责的'; }?><?php if ($folder == 'from_me') { echo '我创建的'; }?><?php if ($folder == 'over') { echo '已完成的'; }?><?php if ($this->uri->segment(2, '') == 'star') { echo '星标'; }?> <span class="badge badge-info"><?php echo $total;?></span></h5>
            <?php if (($total-$offset) < $per_page) { $per_page_end = $total-$offset; } else { $per_page_end = $per_page; }?>
            <p class="text-muted">查询结果：<?php echo ($offset+1).' - '.($per_page_end+$offset).' of '.$total;?></p>
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
                          echo '<tr><td colspan="7"><span class="fa fa-calendar"></span> 创建时间：'.$day.'</td></tr>';
                        }
                        $timeGroup[$timeDay] = 1;
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
                    <?php if ($this->uri->segment(3, 'to_me') == 'to_me') {?>
                    <td align="center" width="40px">
                      <a href="/conf/profile/<?php echo $value['add_user'];?>" class="pull-left" target="_blank">
                        <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['add_user']]['username']?>.jpg" align="absmiddle" title="添加人：<?php echo $users[$value['add_user']]['realname'];?>"></div>
                      </a>
                    </td>
                    <?php } ?>
                    <?php if ($this->uri->segment(3, 'to_me') == 'from_me') {?>
                    <td align="center" width="40px">
                      <?php if ($value['accept_user']) {?>
                      <a href="/conf/profile/<?php echo $value['accept_user'];?>" class="pull-left" target="_blank">
                        <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['accept_user']]['username']?>.jpg" align="absmiddle" title="当前受理人：<?php echo $users[$value['accept_user']]['realname'];?>"></div>
                      </a>
                      <?php } else { echo '-'; } ?>
                    </td>
                    <?php } ?>
                    <td width="80px">
                      <?php echo '<span class="label label-'.$workflow[$value['workflow']]['span_color'].'">'.$workflow[$value['workflow']]['name'].'</span>'; ?>
                    </td>
                    <td align="center" width="30px">
                      <?php if ($value['type'] == 2) {?><i class="fa fa-bug tooltips" data-toggle="tooltip" title="BUG"></i><?php } ?><?php if ($value['type'] == 1) {?><i class="fa fa-magic tooltips" data-toggle="tooltip" title="TASK"></i><?php } ?>
                    </td>
                    <td><?php if ($value['level']) {?><?php echo "<strong style='color:#ff0000;' title='".$level[$value['level']]['alt']."'>".$level[$value['level']]['name']."</strong> ";?><?php } ?> <a href="/issue/view/<?php echo $value['id'];?>"><?php if ($value['status'] == '-1') { echo '<del>'.$value['issue_name'].'</del>'; } else { echo $value['issue_name']; } ?></a><?php if ($value['status'] == '-1') echo ' <span class="label label-default">已删除</span>'; ?></td>
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
              <?php echo $pages;?>
            </div><!-- table-responsive -->
          </div><!-- panel-body -->
        </div><!-- panel -->
      </div><!-- col-sm-9 -->
      <p class="text-right"><small>页面执行时间 <em>{elapsed_time}</em> 秒 使用内存 {memory_usage}</small></p>
    </div><!-- contentpanel -->
  </div><!-- mainpanel -->
  <?php include('common_users.php');?>
</section>

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/jquery-ui-1.10.3.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.validate.min.js"></script>
<script src="/static/js/jquery.form.js"></script>
<script src="/static/js/jquery.gritter.min.js"></script>

<script src="/static/js/masonry.pkgd.min.js"></script>

<script src="/static/js/custom.js"></script>
<script src="/static/js/cits.js"></script>

<script>
jQuery(document).ready(function(){
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
