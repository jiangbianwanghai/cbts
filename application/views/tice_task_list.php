<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 提测管理 <span>任务列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/tice/task_list">提测管理</a></li>
          <li class="active">任务列表</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
      
      <div class="row">
        
        <div class="col-md-12">
          <h5 class="subtitle mb5">提测广场列表</h5>
          <p class="mb20">在这里你可以看到大家的提测记录。</p>
          <div class="table-responsive">
          <table class="table table-hidaction table-hover mb30">
            <thead>
              <tr>
                <th>#</th>
                <th>名称</th>
                <th>说明</th>
                <th>添加时间</th>
                <th>添加人</th>
                <th>最后修改时间</th>
                <th>修改人</th>
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
                <td><?php if ($value['task_name']) { echo '<a href="'.$value['task_url'].'" target="_blank">'.$value['task_name'].'</a>'; } else { echo '<a href="'.$value['task_url'].'" target="_blank">标题为空</a>'; }?></td>
                <td><input type="text" placeholder="<?php echo $value['task_summary'];?>" class="form-control popovers" data-toggle="popover" data-placement="top" data-original-title="说明" data-content="<?php echo $value['task_summary'];?>" data-trigger="click" /></td>
                <td><?php echo date("Y-m-d H:i:s", $value['add_time']);?></td>
                <td><?php echo $value['add_user'];?></td>
                <td><?php echo $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : '-';?></td>
                <td><?php echo $value['last_user'] ? $value['last_user'] : '-';?></td>
                <td class="table-action-hide">
                  <a href="/conf/task_edit/<?php echo $value['id'];?>"><i class="fa fa-pencil"></i></a>
                  <a href="javascript:;" class="delete-row" reposid="<?php echo $value['id'];?>"><i class="fa fa-trash-o"></i></a>
                </td>
              </tr>
              <?php
                  }
                }
              ?>
            </tbody>
          </table>
          </div><!-- table-responsive -->
        </div><!-- col-md-6 -->
        
      </div><!--row -->
      
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

    // Show aciton upon row hover
    jQuery('.table-hidaction tbody tr').hover(function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 1});
    },function(){
      jQuery(this).find('.table-action-hide a').animate({opacity: 0});
    });

  });
</script>

</body>
</html>