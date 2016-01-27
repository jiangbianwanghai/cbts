<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 提测管理 <span>任务列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/tice/task_list">提测管理</a></li>
          <li class="active">我的任务列表</li>
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
                    <h5 class="panel-title">我的任务列表</h5>
                </div><!-- panel-heading -->
                <div class="panel-body panel-table">
                    <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="table-head-alt">
                                <th>名称</th>
                                <th>提交时间</th>
                                <th>受理进度</th>
                                <th>阶段/状态</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                              if ($rows) {
                                foreach ($rows as $value) {
                            ?>
                            <tr id="tr-<?php echo $value['id'];?>">
                                <td><?php if ($value['issue_name']) { echo '<a href="/issue/view/'.$value['id'].'" target="_blank">'.$value['issue_name'].'</a>'; } else { echo '<a href="'.$value['task_url'].'" target="_blank">标题为空</a>'; }?></td>
                                <td><?php echo date("Y-m-d H:i:s", $value['add_time']);?></td>
                                <td>
                                    <div class="progress">
                                        <div style="width: 2%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="2" role="progressbar" class="progress-bar progress-bar-danger">
                                          <span class="sr-only">2% Complete (success)</span>
                                        </div>
                                    </div>
                                </td>
                                <td>开发环境/未提测</td>
                                <td class="table-action-hide">
                                  <a href="/conf/issue_edit/<?php echo $value['id'];?>"><i class="fa fa-pencil"></i> 编辑</a>
                                  <a href="javascript:;" class="delete-row" reposid="<?php echo $value['id'];?>"><i class="fa fa-trash-o"></i> 删除</a>
                                </td>
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

    // Show aciton upon row hover
    jQuery('.table-responsive tbody tr').hover(function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 1});
    },function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 0});
    });

  });
</script>

</body>
</html>
