<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 提测广场 <span>提测列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/issue/plaza">提测广场</a></li>
          <li class="active">提测列表</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">

      <div class="row">
        <div class="col-md-12">
            
            <div class="panel panel-dark panel-alt">
                <div class="panel-heading">
                    <div class="panel-btns">
                        <a href="" class="panel-close">&times;</a>
                        <a href="" class="minimize">&minus;</a>
                    </div><!-- panel-btns -->
                    <h5 class="panel-title"><?php echo $PAGE_TITLE;?></h5>
                </div><!-- panel-heading -->
                <div class="panel-body panel-table">
                  <div class="table-responsive">
                    <table class="table">
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
                          if ($rows) {
                            foreach ($rows as $value) {
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
                <?php echo $pages;?>
            </div><!-- panel -->
            
        </div><!-- col-md-6 -->
                        
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
        <div class="modal-body">...</div>
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
            $(".modal-body").html(data);
          }
        });
    });
    
  });
</script>

</body>
</html>
