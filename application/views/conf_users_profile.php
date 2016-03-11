<?php include('common_header.php');?>

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> TA的记录 <span><?php echo $users[$id]['realname'];?> 的记录</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">我的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">巧克力提测系统</a></li>
          <li class="active"><?php echo $users[$id]['realname'];?> 的记录</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">
      
      <div class="row">
        <div class="col-sm-12">
          
          <div class="profile-header">
            <h2 class="profile-name"><?php echo $users[$id]['realname'];?> 的记录</h2>
            
          </div><!-- profile-header -->

          <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="panel panel-default">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-6">
                      <h5 class="subtitle mb5"><?php if ($role == 1) {?>TA受理的任务量统计<?php } ?><?php if ($role == 2) {?>TA提交的任务量统计<?php } ?></h5>
                      <p class="mb15"><?php if ($role == 1) {?>最近30天受理的任务量统计(正常量/关闭量)<?php } ?><?php if ($role == 2) {?>最近30天提交的任务量(正常量/关闭量)<?php } ?></p>
                      <div id="stacked-chart_issue_my" class="body-chart">暂无数据</div>
                    </div><!-- col-md-6 -->
                    <div class="col-md-6">
                      <h5 class="subtitle mb5"><?php if ($role == 1) {?>TA受理的提测量统计<?php } ?><?php if ($role == 2) {?>TA申请的提交量统计<?php } ?></h5>
                      <p class="mb15"><?php if ($role == 1) {?>最近30天受理的提测量统计(待测+测试中/其他状态[不通过,通过,已覆盖])<?php } ?><?php if ($role == 2) {?>最近30天申请的提测量(不通过量/其他状态[待测,在测试,通过,已覆盖])<?php } ?></p>
                      <div id="stacked-chart_test_my" class="body-chart">暂无数据</div>
                    </div><!-- col-md-6 -->
                  </div><!-- row -->
                </div><!-- panel-body -->
              </div><!-- panel -->
            </div><!-- col-sm-12 -->
          </div><!-- row -->
          
          <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified nav-profile">
          <li class="active"><a href="#activities" data-toggle="tab"><strong><?php if ($role == 1) {?>TA受理的任务记录<?php } ?><?php if ($role == 2) {?>TA提交的任务记录<?php } ?>(<?php echo $issue_total;?>)</strong></a></li>
          <li><a href="#followers" data-toggle="tab"><strong><?php if ($role == 1) {?>TA受理的提测记录<?php } ?><?php if ($role == 2) {?>TA提交的提测记录<?php } ?>(<?php echo $test_total;?>)</strong></a></li>
        </ul>
        
        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane active" id="activities">
            <div class="row">
        <div class="col-md-12"> 
          <div class="panel panel-dark panel-alt">
              <div class="panel-body panel-table">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr class="table-head-alt">
                        <th>#</th>
                        <th>名称</th>
                        <th>受理进度</th>
                        <th>状态</th>
                        <th>添加人</th>
                        <th>最后修改</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        if ($issue) {
                          foreach ($issue as $value) {
                      ?>
                      <tr id="tr-<?php echo $value['id'];?>">
                        <td><?php echo $value['id'];?></td>
                        <td><?php if ($value['status'] == '-1') { echo '<s><a href="/issue/view/'.$value['id'].'">'.$value['issue_name'].'</a></s>'; } else { echo '<a href="/issue/view/'.$value['id'].'">'.$value['issue_name'].'</a>'; }?></td>
                        <td><?php if ($value['resolve']) { ?> <span class="label label-success">已解决</span><?php } else {?> <span class="label label-info">未解决</span><?php } ?></td>
                        <td>
                          <?php if ($value['status'] == 1) {?> <span class="label label-primary">正常</span><?php }?>
                          <?php if ($value['status'] == 0) {?> <span class="label label-default">已关闭</span><?php }?>
                          <?php if ($value['status'] == -1) {?> <span class="label label-warning">已删除</span><?php }?>
                        </td>
                        <td><?php echo $value['add_user'] ? $users[$value['add_user']]['realname'] : '-';?></td>
                        <td><?php echo $value['last_user'] ? $users[$value['last_user']]['realname'] : '-';?></td>
                        <td class="table-action">
                          <?php if ($value['status'] == 1 && $value['resolve'] == 0) { ?>
                          <a href="/test/add/<?php echo $value['id'];?>"><i class="fa fa-slack"></i> 提交代码</a>
                          <a href="/issue/edit/<?php echo $value['id'];?>"><i class="fa fa-pencil"></i> 编辑</a>
                          <a href="javascript:;" class="delete-row" reposid="<?php echo $value['id'];?>"><i class="fa fa-trash-o"></i> 删除</a>
                          <?php } else { echo "已完成并归档";}?>
                        </td>
                      </tr>
                      <?php
                          }
                        } else {
                      ?>
                        <tr><td colspan="7" align="center">任务列表为空~</td></tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                </div><!-- table-responsive -->
              </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-md-6 -->                 
      </div><!-- row -->

          </div>
          <div class="tab-pane" id="followers">
            
            <div class="row">
        
        <div class="col-md-12">
          <div class="panel panel-dark panel-alt">
            <div class="panel-body panel-table">
                <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>版本库</th>
                      <th>版本标识</th>
                      <th>相关任务</th>
                      <th>所处阶段</th>
                      <th>提测状态</th>
                      <th>添加人</th>
                      <th>最后修改人</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if ($test) {
                        foreach ($test as $value) {
                    ?>
                    <tr id="tr-<?php echo $value['id'];?>">
                      <td><?php echo $value['id'];?></td>
                      <td><a href="/test/repos/<?php echo $value['repos_id'];?>"><?php echo $repos[$value['repos_id']]['repos_name'];?></a></td>
                      <td><?php echo $value['test_flag'];?></td>
                      <td><a href="/issue/view/<?php echo $value['issue_id'];?>">ISSUE-<?php echo $value['issue_id'];?></a></td>
                      <td>
                        <?php if ($value['rank'] == 0) {?>
                        <button class="btn btn-default btn-xs"><i class="fa fa-coffee"></i> 开发环境</button>
                        <?php } ?>
                        <?php if ($value['rank'] == 1) {?>
                        <button class="btn btn-primary btn-xs"><?php if ($value['state'] == 5) { ?><i class="fa fa-exclamation-circle"></i> <s>测试环境</s><?php } else {?><i class="fa fa-check-circle"></i> 测试环境<?php } ?></button>
                        <?php } ?>
                        <?php if ($value['rank'] == 2) {?>
                        <button class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i> 生产环境</button>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if ($value['state'] == 0) {?>
                        <button class="btn btn-default btn-xs"><i class="fa fa-coffee"></i> 待测</button>
                        <?php } ?>
                        <?php if ($value['state'] == 1) {?>
                        <button class="btn btn-primary btn-xs"><i class="fa fa-clock-o"></i> 测试中……</button>
                        <?php } ?>
                        <?php if ($value['state'] == -3) {?>
                        <button class="btn btn-danger btn-xs"><i class="fa fa-exclamation-circle"></i> 不通过</button>
                        <?php } ?>
                        <?php if ($value['state'] == 3) {?>
                        <button class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i> 通过</button>
                        <?php } ?>
                        <?php if ($value['state'] == 5) {?>
                        <button class="btn btn-success btn-xs"><i class="fa fa-exclamation-circle"></i> 已被后续版本覆盖</button>
                        <?php } ?>
                      </td>
                      <td><?php echo $value['add_user'] ? $users[$value['add_user']]['realname'] : '-';?></td>
                      <td><?php echo $value['last_user'] ? $users[$value['last_user']]['realname'] : '-';?></td>
                      <td><?php if ($value['test_summary']) {?><a href="javascript:;" class="view" testid="<?php echo $value['id'];?>" data-toggle="modal" data-target=".bs-example-modal">有说明</a><?php } ?></td>
                    </tr>
                    <?php
                        }
                      }
                    ?>
                  </tbody>
                </table>
              </div><!-- table-responsive -->
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-md-6 -->
        
      </div><!--row -->
            
          </div>
          
        </div><!-- tab-content -->
          
        </div><!-- col-sm-9 -->
      </div><!-- row -->
            
    </div><!-- contentpanel -->

  </div><!-- mainpanel -->

</section>

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/retina.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/flot/jquery.flot.min.js"></script>
<script src="/static/js/flot/jquery.flot.resize.min.js"></script>
<script src="/static/js/flot/jquery.flot.symbol.min.js"></script>
<script src="/static/js/flot/jquery.flot.crosshair.min.js"></script>
<script src="/static/js/flot/jquery.flot.categories.min.js"></script>
<script src="/static/js/flot/jquery.flot.pie.min.js"></script>
<script src="/static/js/morris.min.js"></script>
<script src="/static/js/raphael-2.1.0.min.js"></script>

<script src="/static/js/custom.js"></script>

<script type="text/javascript">
jQuery(document).ready(function() {
  
  "use strict";

  <?php if ($stackedMyTestStr) { ?>
  $("#stacked-chart_test_my").text('');
  var m3 = new Morris.Bar({
        element: 'stacked-chart_test_my',
        data: <?php echo $stackedMyTestStr;?>,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['其他状态', '不通过'],
        barColors: ['#F0AD4E', '#D9534F'],
        lineWidth: '1px',
        fillOpacity: 0.8,
        smooth: false,
        stacked: true,
        hideHover: true
  });
  <?php } ?>

  <?php if ($stackedMyIssueStr) { ?>
  $("#stacked-chart_issue_my").text('');
  var m4 = new Morris.Bar({
        element: 'stacked-chart_issue_my',
        data: <?php echo $stackedMyIssueStr;?>,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['关闭', '正常'],
        barColors: ['#428BCA', '#1CAF9A'],
        lineWidth: '1px',
        fillOpacity: 0.8,
        smooth: false,
        stacked: true,
        hideHover: true
  });
  <?php } ?>

  jQuery(window).resize(function() {
    delay(function() {
      <?php if ($stackedMyTestStr) { ?>m3.redraw();<?php } ?>
      <?php if ($stackedMyIssueStr) { ?>m4.redraw();<?php } ?>
    }, 200);
  }).trigger('resize');
  
});

</script>

</body>
</html>
