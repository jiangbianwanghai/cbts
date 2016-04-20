<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-bug"></i> Bug管理 <span>我的Bug列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/bug">Bug管理</a></li>
          <li class="active">我的Bug列表</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel panel-email">
      <div class="row">
        <div class="col-sm-3 col-lg-2">
          <ul class="nav nav-pills nav-stacked nav-email">
              <li class="active"><a href="/bug"><i class="glyphicon glyphicon-inbox"></i> Bug列表</a></li>
              <li><a href="#"><i class="glyphicon glyphicon-star"></i> 星标</a></li>
              <li><a href="#"><i class="glyphicon glyphicon-trash"></i> 已删除</a></li>
          </ul>
          <div class="mb30"></div>
          
          <h5 class="subtitle">快捷方式</h5>
          <ul class="nav nav-pills nav-stacked nav-email mb20">
            <li><a href="#"><i class="glyphicon glyphicon-folder-open"></i> 我负责的</a></li>
            <li><a href="#"><i class="glyphicon glyphicon-folder-open"></i> 我创建的</a></li>
            <li><a href="#"><i class="glyphicon glyphicon-folder-open"></i> 已完成的</a></li>
          </ul>
        </div><!-- col-sm-3 -->
            
            <div class="col-sm-9 col-lg-10">
                
                <div class="panel panel-default">
                    <div class="panel-body">
                        
                        <div class="pull-right">
                          <div class="btn-group mr10">
                                <div class="btn-group nomargin">
                                    <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="根据状态筛选">
                                      <i class="glyphicon glyphicon-folder-close mr5"></i> 处理状态
                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                      <li><a href="#"><i class="glyphicon glyphicon-folder-open mr5"></i> 未确认</a></li>
                                      <li><a href="#"><i class="glyphicon glyphicon-folder-open mr5"></i> 处理中</a></li>
                                      <li><a href="#"><i class="glyphicon glyphicon-folder-open mr5"></i> 已处理</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group nomargin">
                                    <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle tooltips" type="button" title="根据项目筛选">
                                      <i class="glyphicon glyphicon-tag mr5"></i> 所属项目所属项目
                                      <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                      <li><a href="#"><i class="glyphicon glyphicon-tag mr5"></i> Web</a></li>
                                      <li><a href="#"><i class="glyphicon glyphicon-tag mr5"></i> Photo</a></li>
                                      <li><a href="#"><i class="glyphicon glyphicon-tag mr5"></i> Video</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- pull-right -->
                        
                        <h5 class="subtitle mb5">Bug列表</h5>
                        <p class="text-muted">查询结果：<?php echo ($offset+1).' - '.($offset+$per_page).' of '.$total;?></p>
                        
                        <div class="table-responsive">
                          <table class="table table-email">
                            <tbody>
                              <?php
                                if ($rows) {
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
                                  <a href="javascript:;" class="star"><i class="glyphicon glyphicon-star"></i></a>
                                </td>
                                <td width="70px;">
                                  <?php if ($value['state'] === '-1') {?>
                                  <span class="label label-default">反馈无效</span>
                                  <?php } ?>
                                  <?php if ($value['state'] === '0') {?>
                                  <span class="label label-default">未确认</span>
                                  <?php } ?>
                                  <?php if ($value['state'] === '1') {?>
                                  <span class="label label-primary">已确认</span>
                                  <?php } ?>
                                  <?php if ($value['state'] === '2') {?>
                                  <span class="label label-primary">处理中</span>
                                  <?php } ?>
                                  <?php if ($value['state'] === '3') {?>
                                  <span class="label label-success">已处理</span>
                                  <?php } ?>
                                </td>
                                <td>
                                  <div class="media">
                                      <a href="#" class="pull-left">
                                        <div class="face"><img alt="" src="/static/avatar/<?php echo $users[$value['accept_user']]['username']?>.jpg" align="absmiddle" title="处理人：<?php echo $users[$value['accept_user']]['realname'];?>"></div>
                                      </a>
                                      <div class="media-body">
                                          <span class="media-meta pull-right"><?php echo friendlydate($value['add_time']);?></span>
                                          <p class="email-summary"><?php if ($value['level']) {?><?php echo "<strong style='color:#ff0000;' title='".$level[$value['level']]['alt']."'>".$level[$value['level']]['name']."</strong> ";?><?php } ?> <a href="/bug/view/<?php echo $value['id'];?>"><?php echo $value['subject'];?></a> <span class="badge"><?php echo $users[$value['add_user']]['realname'];?></span></p>
                                      </div>
                                  </div>
                                </td>
                              </tr>
                              <?php
                                  }
                                } else {
                              ?>
                                <tr><td align="center">无数据~</td></tr>
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
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.datatables.min.js"></script>
<script src="/static/js/select2.min.js"></script>

<script src="/static/js/custom.js"></script>
<script>
jQuery(document).ready(function(){
  
  "use strict"

  //Check
  jQuery('.ckbox input').click(function(){
      var t = jQuery(this);
      if(t.is(':checked')){
          t.closest('tr').addClass('selected');
      } else {
          t.closest('tr').removeClass('selected');
      }
  });
  
  // Star
  jQuery('.star').click(function(){
      if(!jQuery(this).hasClass('star-checked')) {
          jQuery(this).addClass('star-checked');
      }
      else
          jQuery(this).removeClass('star-checked');
      return false;
  });

});
</script>

</body>
</html>
