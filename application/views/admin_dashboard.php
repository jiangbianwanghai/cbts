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
        <h5 class="subtitle">快捷方式</h5>
        <ul class="nav nav-pills nav-stacked nav-email mb20">
          <li<?php if ($this->uri->segment(3, 'to_me') == 'to_me') {?> class="active"<?php } ?>><a href="/admin/index/to_me"><i class="glyphicon glyphicon-folder-<?php if ($this->uri->segment(3, 'to_me') == 'to_me') { echo 'open';} else { echo 'close';}?>"></i> 我负责的</a></li>
          <li<?php if ($this->uri->segment(3, '') == 'from_me') {?> class="active"<?php } ?>><a href="/admin/index/from_me"><i class="glyphicon glyphicon-folder-<?php if ($this->uri->segment(3, '') == 'from_me') { echo 'open';} else { echo 'close';}?>"></i> 我创建的</a></li>
          <li<?php if ($this->uri->segment(3, '') == 'over') {?> class="active"<?php } ?>><a href="/admin/index/over"><i class="glyphicon glyphicon-folder-<?php if ($this->uri->segment(3, '') == 'over') { echo 'open';} else { echo 'close';}?>"></i> 已完成的</a></li>
        </ul>
      </div><!-- col-sm-3 -->
      <div class="col-sm-9 col-lg-10">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="pull-right">
              <div class="btn-group mr10">
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
                    <li><a href="/admin/index/<?php echo $this->uri->segment(3, 'to_me');?>/0/0/<?php echo $taskType;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部</a></li>
                    <?php } ?>
                    <?php 
                    foreach ($projectListByIssue as $key => $value) {
                      if ($projectMd5 != $value['md5'] || !$projectMd5) {
                    ?>
                    <li><a href="/admin/index/<?php echo $this->uri->segment(3, 'to_me');?>/<?php echo $value['md5'];?>/<?php echo $planId;?>/<?php echo $taskType;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> <?php echo $value['project_name'];?></a></li>
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
                    <li><a href="/admin/index/<?php echo $this->uri->segment(3, 'to_me');?>/<?php echo $projectMd5;?>/0/<?php echo $taskType;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部</a></li>
                    <?php } ?>
                    <?php 
                    foreach ($planListByIssue as $key => $value) {
                      if ($planId != $value['id'] || !$planId) {
                    ?>
                    <li><a href="/admin/index/<?php echo $this->uri->segment(3, 'to_me');?>/<?php echo $projectMd5;?>/<?php echo $value['id'];?>/<?php echo $taskType;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> <?php echo $value['plan_name'];?></a></li>
                    <?php
                      }
                    } 
                    ?>
                  </ul>
                  <?php } ?>
                </div>
                <div class="btn-group nomargin">
                  <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="根据绩效圈筛选" style="text-transform:uppercase;">
                    <i class="glyphicon glyphicon-folder-<?php if ($taskType) { echo 'open'; } else { echo 'close'; }?> mr5"></i> <?php if ($taskType) { echo $taskType; } else { echo '类型筛选'; }?>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <?php if ($taskType) {?>
                    <li><a href="/admin/index/<?php echo $this->uri->segment(3, 'to_me');?>/<?php echo $projectMd5;?>/<?php echo $planId;?>"><i class="glyphicon glyphicon-folder-open mr5"></i> 查看全部</a></li>
                    <?php } ?>
                    <?php if ($taskType != 'task' || !$taskType) {?><li><a href="/admin/index/<?php echo $this->uri->segment(3, 'to_me');?>/<?php echo $projectMd5;?>/<?php echo $planId;?>/task"><i class="glyphicon glyphicon-folder-open mr5"></i> TASK</a></li><?php } ?>
                    <?php if ($taskType != 'bug' || !$taskType) {?><li><a href="/admin/index/<?php echo $this->uri->segment(3, 'to_me');?>/<?php echo $projectMd5;?>/<?php echo $planId;?>/bug"><i class="glyphicon glyphicon-folder-open mr5"></i> BUG</a></li><?php } ?>
                  </ul>
                </div>
              </div>
            </div><!-- pull-right -->
            <h5 class="subtitle mb5"><?php if ($this->uri->segment(3, 'to_me') == 'to_me') { echo '我负责的'; }?><?php if ($this->uri->segment(3, '') == 'from_me') { echo '我创建的'; }?><?php if ($this->uri->segment(3, '') == 'over') { echo '已完成的'; }?> <span class="badge badge-info"><?php echo $total;?></span></h5>
            <?php if (($total-$offset) < $per_page) { $per_page_end = $total-$offset; } else { $per_page_end = $per_page; }?>
            <p class="text-muted">查询结果：<?php echo ($offset+1).' - '.($per_page_end+$offset).' of '.$total;?></p>
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
                      <a href="/conf/profile/<?php echo $value['add_user'];?>" class="pull-left" target="_blank">
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
                      处理进度
                    </td>
                    <td align="center" width="30px">
                      <?php if ($value['type'] == 2) {?><i class="fa fa-bug tooltips" data-toggle="tooltip" title="BUG"></i><?php } ?><?php if ($value['type'] == 1) {?><i class="fa fa-magic tooltips" data-toggle="tooltip" title="TASK"></i><?php } ?>
                    </td>
                    <td><?php if ($value['level']) {?><?php echo "<strong style='color:#ff0000;' title='".$level[$value['level']]['alt']."'>".$level[$value['level']]['name']."</strong> ";?><?php } ?> <a href="/issue/view/<?php echo $value['id'];?>" target="_blank"><?php echo $value['issue_name'];?></a></span></td>
                    
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
