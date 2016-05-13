<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 基础信息配置 <span>配置代码库信息</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/conf/repos_list">基础信息配置</a></li>
          <li class="active">代码库管理</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel panel-email">
      
      <div class="row">
        
         <div class="col-sm-3 col-lg-2">
          <ul class="nav nav-pills nav-stacked nav-email">
            <li class="active"><a href="/conf/repos_list"><i class="fa fa-list"></i>代码库列表</a></li>
            <li><a href="/conf/repos"><i class="fa fa-plus"></i>添加代码库</a></li>
          </ul>
        </div><!-- col-sm-3 -->
        <div class="col-sm-9 col-lg-10">
        <div class="panel panel-default">
            <div class="panel-body">
            <h5 class="subtitle mb5">代码库列表</h5>
          <div class="table-responsive">
          <table class="table table-hidaction table-hover">
            <thead>
              <tr>
                <th width="200">代码库名称</th>
                <th>代码库地址</th>
                <th width="80"></th>
                <th width="120"></th>
              </tr>
            </thead>
            <tbody>
              <?php
                if ($rows) {
                  foreach ($rows as $value) {
              ?>
              <tr id="tr-<?php echo $value['id'];?>">
              <td style="padding-top: 20px;"><a href="/test/repos/<?php echo $value['id'];?>"><?php echo $repos[$value['id']]['repos_name'];?></a></td>
              <td><input type="text" value="<?php echo $value['repos_url'];?>" id="readonlyinput" readonly="readonly" title="<?php echo $value['repos_url'];?>" data-toggle="tooltip" data-trigger="hover" class="form-control tooltips" /></td>
              <td style="padding-top: 20px"><a href="javascript:;" class="view label label-warning" data-toggle="modal" data-target="#code_detail" testid="<?php echo $value['id'];?>">查看详情</a>
              </td>
                <td class="table-action" style="padding-top: 20px;">
                  <a href="/conf/repos_edit/<?php echo $value['id'];?>"><i class="fa fa-pencil"></i> 编辑</a>
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
          <?php echo $pages;?>
          </div>
          </div>
        </div><!-- col-md-6 -->
        
      </div><!--row -->
      
    </div><!-- contentpanel -->
    
  </div><!-- mainpanel -->
  
</section>

<div class="modal fade" id="code_detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">代码库详情</h4>
      </div>
      <div class="modal-body">            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
</div><!-- modal -->

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
          url: "/conf/repos_del/"+id,
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

   $(".view").click(function(){
      id = $(this).attr("testid");
        $.ajax({
          type: "GET",
          url: "/conf/repos_view/"+id,
          dataType: "JSON",
          success: function(data){
            if (data.status) {
              $(".modal-body").html('<table class="table table-striped"><tbody><tr><td width="150px">名称：</td><td>'+data.message.repos_name+'</td></tr><tr><td width="150px">别称：</td><td>'+data.message.repos_name_other+'</td></tr><tr><td width="150px">代码库地址：</td><td>'+data.message.repos_url+'</td></tr><tr><td width="150px">代码库描述：</td><td>'+data.message.repos_summary+'</td></tr><tr><td width="150px">是否要合并：</td><td>'+data.message.merge+'</td></tr><tr><td width="150px">添加人：</td><td>'+data.message.add_user+'</td></tr><tr><td width="150px">添加时间：</td><td>'+data.message.add_time+'</td></tr><tr><td width="150px">修改人：</td><td>'+data.message.last_user+'</td></tr><tr><td width="150px">修改时间：</td><td>'+data.message.last_time+'</td></tr></tbody></table>');
            } else {
             $(".modal-body").html(data.message);
            }
            
          }
        });
    });

</script>

</body>
</html>
