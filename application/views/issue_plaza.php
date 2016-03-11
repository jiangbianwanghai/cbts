<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 任务广场 <span>任务列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/issue/plaza">任务广场</a></li>
          <li class="active">任务列表</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <h4 class="subtitle mb5">按申请角色筛选</h4>
          <div class="btn-group">
            <a href="/issue/plaza/able/able/all" class="btn btn-sm btn-<?php if ($add_user == 'all') { echo 'primary'; } else { echo 'default'; }?>">全体人员</a>
            <a href="/issue/plaza/able/able/my" type="button" class="btn btn-sm btn-<?php if ($add_user == 'my') { echo 'primary'; } else { echo 'default'; }?>">我的</a>
          </div>

          <div class="mb20"></div>

          <h4 class="subtitle mb5">按受理角色筛选</h4>
          <div class="btn-group">
            <a href="/issue/plaza/able/able/all/all" class="btn btn-sm btn-<?php if ($accept_user == 'all') { echo 'primary'; } else { echo 'default'; }?>">全体人员</a>
            <a href="/issue/plaza/able/able/all/my" type="button" class="btn btn-sm btn-<?php if ($accept_user == 'my') { echo 'primary'; } else { echo 'default'; }?>">我的</a>
          </div>

          <div class="mb20"></div>

          <h4 class="subtitle mb5">按受理进度筛选</h4>
          <div class="btn-group">
            <a href="/issue/plaza/able/<?php echo $status;?>/<?php echo $add_user;?>/<?php echo $accept_user;?>" class="btn btn-sm btn-<?php if ($resolve == 'able') { echo 'primary'; } else { echo 'default'; }?>">未解决</a>
            <a href="/issue/plaza/disable/close/<?php echo $add_user;?>/<?php echo $accept_user;?>" type="button" class="btn btn-sm btn-<?php if ($resolve == 'disable') { echo 'primary'; } else { echo 'default'; }?>">已解决</a>
          </div>
          
          <div class="mb20"></div>
          
          <h4 class="subtitle mb5">按状态筛选</h4>
          <div class="btn-group">
            <a href="/issue/plaza/<?php echo $resolve;?>/able/<?php echo $add_user;?>/<?php echo $accept_user;?>" class="btn btn-sm btn-<?php if ($status == 'able') { echo 'primary'; } else { echo 'default'; }?>">正常</a>
            <a href="/issue/plaza/<?php echo $resolve;?>/close/<?php echo $add_user;?>/<?php echo $accept_user;?>" type="button" class="btn btn-sm btn-<?php if ($status == 'close') { echo 'primary'; } else { echo 'default'; }?>">关闭</a>
            <a href="/issue/plaza/<?php echo $resolve;?>/delete/<?php echo $add_user;?>/<?php echo $accept_user;?>" type="button" class="btn btn-sm btn-<?php if ($status == 'delete') { echo 'primary'; } else { echo 'default'; }?>">已删除</a>
          </div>
          
          <div class="mb20"></div>
        </div><!-- col-sm-4 -->
        <div class="col-sm-9 col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $pages;?>
                    <h4 class="panel-title">筛选结果</h4>
                    <p>总计 <?php echo $total_rows;?> 条记录 </p>
                </div><!-- panel-heading -->
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr class="table-head-alt">
                          <th width="50px">#</th>
                          <th width="50px">类型</th>
                          <th>名称</th>
                          <th width="80px">添加人</th>
                          <th width="80px">最后修改</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          if ($rows) {
                            foreach ($rows as $value) {
                        ?>
                        <tr id="tr-<?php echo $value['id'];?>">
                          <td><?php echo $value['id'];?></td>
                          <td><?php if ($value['type'] == 2) {?><i class="fa fa-bug tooltips" data-toggle="tooltip" title="BUG"></i><?php } ?><?php if ($value['type'] == 1) {?><i class="fa fa-magic tooltips" data-toggle="tooltip" title="TASK"></i><?php } ?></td>
                          <td><?php if ($value['level']) { $level = array(1=>'!',2=>'!!',3=>'!!!',4=>'!!!!');?><?php echo "<strong style='color:#ff0000;'>".$level[$value['level']]."</strong> ";?><?php } ?><?php if ($value['status'] == '-1') { echo '<s><a href="/issue/view/'.$value['id'].'">'.$value['issue_name'].'</a></s>'; } else { echo '<a href="/issue/view/'.$value['id'].'">'.$value['issue_name'].'</a>'; }?>
                          </td>
                          <td><?php echo $value['add_user'] ? '<a href="/conf/profile/'.$value['add_user'].'">'.$users[$value['add_user']]['realname'].'</a>' : '-';?>
                          </td>
                          <td><?php echo $value['last_user'] ? '<a href="/conf/profile/'.$value['last_user'].'">'.$users[$value['last_user']]['realname'].'</a>' : '-';?>
                          </td>
                        </tr>
                        <?php
                            }
                          } else {
                        ?>
                          <tr><td colspan="7" align="center">筛选结果为空~</td></tr>
                        <?php
                          }
                        ?>
                      </tbody>
                    </table>
                  </div><!-- table-responsive -->
                </div><!-- panel-body -->
            </div><!-- panel -->
        </div><!-- col-sm-8 -->
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

<script src="/static/js/jquery.datatables.min.js"></script>
<script src="/static/js/select2.min.js"></script>

<script src="/static/js/custom.js"></script>
<script>
  $(document).ready(function(){
    $(".delete-row").click(function(){
      var c = confirm("确认要删除吗？");
      if(c) {
        id = $(this).attr("reposid");
        $.ajax({
          type: "GET",
          url: "/issue/del/"+id,
          dataType: "JSON",
          success: function(data){
            if (data.status) {
              $("#tr-"+id).fadeOut(function(){
                $("#tr-"+id).remove();
              });
              return false;
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
              }, 2000);
            };
          }
        });
      }
    });
  });
</script>

</body>
</html>
