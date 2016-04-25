<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-thumb-tack"></i> 计划管理 <span>当前计划列表</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/plan">计划管理</a></li>
          <li class="active">当前计划列表</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel panel-email">
      <div class="row">
        <div class="col-sm-3 col-lg-2">
          <a href="javascript:;" class="btn btn-danger btn-block btn-compose-email" data-toggle="modal" data-target="#myModal-plan"><i class="fa fa-plus"></i> 新建计划</a>
          <div class="mb30"></div>
          <?php if ($planFolder) {?>
          <h5 class="subtitle">已有计划</h5>
          <ul class="nav nav-pills nav-stacked nav-email mb20">
            <?php foreach ($planFolder as $key => $value) {?>
            <li<?php if ($planId == $value['id']) {?> class="active"<?php } ?>><a href="/plan?planId=<?php echo $value['id'];?>"><i class="glyphicon glyphicon-folder-<?php if ($planId == $value['id']) { echo 'open';} else { echo 'close';}?>"></i> <?php echo $value['plan_name'];?></a></li>
            <?php } ?>
          </ul>
          <?php } ?>
        </div><!-- col-sm-3 -->
        <div class="col-sm-9 col-lg-10">
          <div class="panel panel-default">
            <div class="panel-body">
              <h5 class="subtitle mb5">计划内容</h5>
              
              <div class="table-responsive">
                <table class="table table-email">
                  <thead>
                    <tr>
                      <th width="40px">#</th>
                      <th width="30px"><i class="glyphicon glyphicon-star"></i></th>
                      <th width="80px">处理进度</th>
                      <th>任务主题</th>
                      <th width="150px">添加时间</th>
                    </tr>
                  </thead>
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
                        
                      </td>
                      <td>
                        处理进度
                      </td>
                      <td><?php if ($value['level']) {?><?php echo "<strong style='color:#ff0000;' title='".$level[$value['level']]['alt']."'>".$level[$value['level']]['name']."</strong> ";?><?php } ?> <a href="/issue/view/<?php echo $value['id'];?>" target="_blank"><?php echo $value['issue_name'];?></a></span></td>
                      <td><?php echo date("Y/m/d H:i", $value['add_time']);?></td>
                    </tr>
                    <?php
                        }
                      } else {
                    ?>
                      <tr><td colspan="5" align="center">无数据~</td></tr>
                    <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div><!-- table-responsive -->
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-sm-9 -->
      </div><!-- row -->
    </div><!-- contentpanel -->
    
  </div><!-- mainpanel -->
  <?php include('common_users.php');?>
</section>

<!-- Modal -->
<div class="modal fade" id="myModal-plan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <form method="POST" id="basicForm" action="/plan/add_ajax" class="form-horizontal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">新建计划</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="col-sm-3 control-label">计划名称 <span class="asterisk">*</span></label>
          <div class="col-sm-9">
            <input type="text" name="plan_name" id="plan_name" class="form-control" required />
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-3 control-label">计划简介 <span class="asterisk">*</span></label>
          <div class="col-sm-9">
            <textarea rows="5" class="form-control" id="plan_discription" name="plan_discription" required></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" id="btnSubmit">提交</button>
      </div>
    </div><!-- modal-content -->
  </div><!-- modal-dialog -->
  </form>
</div><!-- modal -->

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.validate.min.js"></script>
<script src="/static/js/jquery.form.js"></script>
<script src="/static/js/jquery.gritter.min.js"></script>

<script src="/static/js/jquery.datatables.min.js"></script>
<script src="/static/js/select2.min.js"></script>

<script src="/static/js/custom.js"></script>
<script>
function validForm(formData,jqForm,options){
  return $("#basicForm").valid();
}
function callBack(data) {
  $("#btnSubmit").attr("disabled", true);
  if(data.status){
    jQuery.gritter.add({
      title: '提醒',
      text: data.message,
        class_name: 'growl-success',
        image: '/static/images/screen.png',
      sticky: false,
      time: ''
    });
    setTimeout(function(){
      location.href = data.url;
    }, 2000);
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
  }
}

jQuery(document).ready(function(){
  
  "use strict"

  $("#basicForm").submit(function(){
    $(this).ajaxSubmit({
      type:"post",
      url: "/plan/add_ajax",
      dataType: "JSON",
      beforeSubmit:validForm,
      success:callBack
    });
    return false;
  });

  $("#basicForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
  });
  
});
</script>

</body>
</html>
