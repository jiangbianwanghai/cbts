<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 提测管理 <span>我的提测列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/tice/my">提测管理</a></li>
          <li class="active">我的提测列表</li>
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
                <h5 class="panel-title">任务列表</h5>
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
                      <th>添加时间</th>
                      <th>修改人</th>
                      <th>最后修改时间</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if ($rows) {
                        foreach ($rows as $value) {
                    ?>
                    <tr id="tr-<?php echo $value['id'];?>">
                      <td><?php echo $value['id'];?></td>
                      <td><a href="javascript:;" class="view" testid="<?php echo $value['id'];?>" data-toggle="modal" data-target=".bs-example-modal"><?php echo $repos[$value['repos_id']]['repos_name'];?></a></td>
                      <td><?php echo $value['test_flag'];?></td>
                      <td><a href="/issue/view/<?php echo $value['issue_id'];?>">ISSUE-<?php echo $value['issue_id'];?></a></td>
                      <td><?php echo $value['add_time'] ? date("Y-m-d H:i:s", $value['add_time']) : '-';?></td>
                      <td><?php echo $value['last_user'] ? '@'.$users[$value['last_user']]['realname'] : '-';?></td>
                      <td><?php echo $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : '-';?></td>
                    </tr>
                    <?php
                        }
                      } else {
                    ?>
                      <tr><td colspan="7" align="center">提测列表为空~</td></tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div><!-- table-responsive -->
            </div><!-- panel-body -->
            <?php echo $pages;?>
          </div><!-- panel -->
        </div><!-- col-md-6 -->
        
      </div><!--row -->
      
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
          url: "/conf/task_del/"+id,
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
