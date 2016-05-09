<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 基础信息配置 <span>配置代码库信息</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/">基础信息配置</a></li>
          <li class="active">代码库管理</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel panel-email">
      
      <div class="row">
        
         <div class="col-sm-3 col-lg-2">
          <h5 class="subtitle">快捷方式</h5>
          <ul class="nav nav-pills nav-stacked nav-email">
            <li<?php if ($this->uri->segment(2, 'index') == 'index' && $folder == 'all') {?> class="active"<?php } ?>><a href="/issue/index"><i class="glyphicon glyphicon-folder-<?php if ($this->uri->segment(2, 'index') == 'index' && $folder == 'all') { echo 'open'; } else { echo 'close'; } ?>"></i> 任务列表</a></li>
            <li<?php if ($this->uri->segment(2, 'index') == 'index' && $folder == 'to_me') {?> class="active"<?php } ?>><a href="/issue/index/to_me"><i class="glyphicon glyphicon-folder-<?php if ($this->uri->segment(2, 'index') == 'index' && $folder == 'to_me') { echo 'open'; } else { echo 'close'; } ?>"></i> 我负责的</a></li>
            <li<?php if ($this->uri->segment(2, 'index') == 'index' && $folder == 'from_me') {?> class="active"<?php } ?>><a href="/issue/index/from_me"><i class="glyphicon glyphicon-folder-<?php if ($this->uri->segment(2, 'index') == 'index' && $folder == 'from_me') { echo 'open'; } else { echo 'close'; } ?>"></i> 我创建的</a></li>
          </ul>
          <div class="mb10"></div>
          <ul class="nav nav-pills nav-stacked nav-email">
            <li<?php if ($this->uri->segment(2, '') == 'star') {?> class="active"<?php } ?>><a href="/issue/star"><i class="glyphicon glyphicon-star"></i> 星标</a></li>
          </ul>
        </div><!-- col-sm-3 -->
        <div class="col-sm-9 col-lg-10">
          <div class="table-responsive">
          <table class="table table-hidaction table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>名称</th>
                <th>别称</th>
                <th>地址</th>
                <th>说明</th>
                <th>添加人</th>
                <th>添加时间</th>
                <th>修改人</th>
                <th>最后修改时间</th>
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
                <td><a href="/test/repos/<?php echo $value['id'];?>"><?php echo $repos[$value['id']]['repos_name'];?></a></td>
                <td><?php echo $value['repos_name_other'];?></td>
                <td><input type="text" value="<?php echo $value['repos_url'];?>" id="readonlyinput" readonly="readonly" title="<?php echo $value['repos_url'];?>" data-toggle="tooltip" data-trigger="hover" class="form-control tooltips" /></td>
                <td><input type="text" placeholder="<?php echo $value['repos_summary'];?>" class="form-control popovers" data-toggle="popover" data-placement="top" data-original-title="说明" data-content="<?php echo $value['repos_summary'];?>" data-trigger="click" /></td>
                <td><?php echo $value['add_user'] ? '<a href="/conf/profile/'.$value['add_user'].'">'.$users[$value['add_user']]['realname'].'</a>' : '-';?></td>
                <td><?php echo date("Y-m-d H:i:s", $value['add_time']);?></td>
                <td><?php echo $value['last_user'] ? '<a href="/conf/profile/'.$value['last_user'].'">'.$users[$value['last_user']]['realname'].'</a>' : '-';?></td>
                <td><?php echo $value['last_time'] ? date("Y-m-d H:i:s", $value['last_time']) : '-';?></td>
                <td class="table-action">
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
</script>

</body>
</html>
