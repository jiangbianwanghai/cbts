<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 提测广场 <span>提测列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/test/plaza">提测广场</a></li>
          <li class="active">提测列表</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
      <div class="row">
        <div class="col-sm-4 col-md-3">
          <h4 class="subtitle mb5">按申请角色筛选</h4>
          <div class="btn-group">
            <a href="/test/plaza/dev/wait/all" class="btn btn-sm btn-<?php if ($add_user == 'all') { echo 'primary'; } else { echo 'default'; }?>">全体人员</a>
            <a href="/test/plaza/dev/wait/my" type="button" class="btn btn-sm btn-<?php if ($add_user == 'my') { echo 'primary'; } else { echo 'default'; }?>">我的</a>
          </div>

          <div class="mb20"></div>

          <h4 class="subtitle mb5">按受理角色筛选</h4>
          <div class="btn-group">
            <a href="/test/plaza/dev/wait/all/all" class="btn btn-sm btn-<?php if ($accept_user == 'all') { echo 'primary'; } else { echo 'default'; }?>">全体人员</a>
            <a href="/test/plaza/dev/wait/all/my" type="button" class="btn btn-sm btn-<?php if ($accept_user == 'my') { echo 'primary'; } else { echo 'default'; }?>">我的</a>
          </div>

          <div class="mb20"></div>

          <h4 class="subtitle mb5">按受理进度筛选</h4>
          <div class="btn-group">
            <a href="/test/plaza/dev/<?php echo $state;?>/<?php echo $add_user;?>/<?php echo $accept_user;?>" class="btn btn-sm btn-<?php if ($rank == 'dev') { echo 'primary'; } else { echo 'default'; }?>">开发环境</a>
            <a href="/test/plaza/test/doing/<?php echo $add_user;?>/<?php echo $accept_user;?>" type="button" class="btn btn-sm btn-<?php if ($rank == 'test') { echo 'primary'; } else { echo 'default'; }?>">测试环境</a>
            <a href="/test/plaza/product/yes/<?php echo $add_user;?>/<?php echo $accept_user;?>" type="button" class="btn btn-sm btn-<?php if ($rank == 'product') { echo 'primary'; } else { echo 'default'; }?>">生产环境</a>
          </div>
          
          <div class="mb20"></div>
          
          <h4 class="subtitle mb5">按状态筛选</h4>
          <div class="btn-group">
            <a href="/test/plaza/<?php echo $rank;?>/wait/<?php echo $add_user;?>/<?php echo $accept_user;?>" class="btn btn-sm btn-<?php if ($state == 'wait') { echo 'primary'; } else { echo 'default'; }?>">待测</a>
            <a href="/test/plaza/test/doing/<?php echo $add_user;?>/<?php echo $accept_user;?>" type="button" class="btn btn-sm btn-<?php if ($state == 'doing') { echo 'primary'; } else { echo 'default'; }?>">测试中</a>
            <a href="/test/plaza/test/no/<?php echo $add_user;?>/<?php echo $accept_user;?>" type="button" class="btn btn-sm btn-<?php if ($state == 'no') { echo 'primary'; } else { echo 'default'; }?>">不通过</a>
            <a href="/test/plaza/test/cover/<?php echo $add_user;?>/<?php echo $accept_user;?>" type="button" class="btn btn-sm btn-<?php if ($state == 'cover') { echo 'primary'; } else { echo 'default'; }?>">已覆盖</a>
            <a href="/test/plaza/test/yes/<?php echo $add_user;?>/<?php echo $accept_user;?>" type="button" class="btn btn-sm btn-<?php if ($state == 'yes') { echo 'primary'; } else { echo 'default'; }?>">通过</a>
          </div>
          
          <div class="mb20"></div>
        </div><!-- col-sm-4 -->
        <div class="col-sm-8 col-md-9">
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
                        <tr>
                          <th width="50px">#</th>
                          <th width="200px">版本库</th>
                          <th>版本标识</th>
                          <th width="140px">相关任务</th>
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
                          <td><?php if ($value['status'] == '-1') { echo '<s><a href="/test/repos/'.$value['repos_id'].'">'.$repos[$value['repos_id']]['repos_name'].'</a></s>'; } else { echo '<a href="/test/repos/'.$value['repos_id'].'">'.$repos[$value['repos_id']]['repos_name'].'</a>'; }?> <?php if ($value['test_summary']) {?><a href="javascript:;" class="view label label-warning" testid="<?php echo $value['id'];?>" data-toggle="modal" data-target=".bs-example-modal">有说明</a><?php } ?></td>
                          <td>#<?php echo $value['test_flag'];?></td>
                          <td><a href="/issue/view/<?php echo $value['issue_id'];?>">ISSUE-<?php echo $value['issue_id'];?></a></td>
                          <td><?php echo $value['add_user'] ? '<a href="/conf/profile/'.$value['add_user'].'">'.$users[$value['add_user']]['realname'].'</a>' : '-';?></td>
                          <td><?php echo $value['last_user'] ? '<a href="/conf/profile/'.$value['last_user'].'">'.$users[$value['last_user']]['realname'].'</a>' : '-';?></td>
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
        </div><!-- col-sm-8 -->
      </div><!-- row -->
      
    </div><!-- contentpanel -->
    
  </div><!-- mainpanel -->
  
</section>

<div class="modal fade bs-example-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title">提测详情</h4>
        </div>
        <div class="modal-body"><div class="modal-body-inner">...</div></div>
    </div>
  </div>
</div>

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

    $(".view").click(function(){
      id = $(this).attr("testid");
        $.ajax({
          type: "GET",
          url: "/test/view/"+id,
          success: function(data){
            $(".modal-title").text('提测说明');
            $(".modal-body").html(data);
          }
        });
    });
    
  });
</script>

</body>
</html>
