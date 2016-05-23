<?php include('common_header.php');?>
  <div class="pageheader">
    <h2><i class="fa fa-pencil"></i> BUG管理 <span>提交BUG</span></h2>
    <div class="breadcrumb-wrapper">
      <span class="label">你的位置:</span>
      <ol class="breadcrumb">
        <li><a href="/">我的控制台</a></li>
        <li><a href="/bug/my">BUG管理</a></li>
        <li class="active">提交BUG</li>
      </ol>
    </div>
  </div>
    
  <div class="contentpanel">
    <div class="row">
      <div class="col-sm-3 col-lg-2">
        <ul class="nav nav-pills nav-stacked nav-email">
            <li class="active"><a href="/bug"><i class="glyphicon glyphicon-inbox"></i> Bug列表</a></li>
            <li><a href="/bug/star"><i class="glyphicon glyphicon-star"></i> 星标</a></li>
            <li><a href="/bug/trash"><i class="glyphicon glyphicon-trash"></i> 已删除</a></li>
        </ul>
        <div class="mb30"></div>
        
        <h5 class="subtitle">快捷方式</h5>
        <ul class="nav nav-pills nav-stacked nav-email mb20">
          <li><a href="/bug/index/to_me"><i class="glyphicon glyphicon-folder-close"></i> 我负责的</a></li>
          <li><a href="/bug/index/from_me"><i class="glyphicon glyphicon-folder-close"></i> 我创建的</a></li>
        </ul>
      </div><!-- col-sm-3 -->
      <div class="col-sm-9 col-lg-10">
        <form method="POST" id="basicForm" enctype="multipart/form-data" action="/bug/add_ajax" class="form-horizontal">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">新增BUG反馈</h4>
              <p>请认真填写下面的选项</p>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">相关任务 <span class="asterisk">*</span></label>
                <div class="col-sm-10">
                  <input type="text" id="issue_id" name="issue_id" class="form-control" value="<?php echo $row['issue_name']?>#<?php echo $row['id']?>" disabled="" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">请选择优先级 <span class="asterisk">*</span></label>
                <div class="col-sm-10">
                  <select id="level" name="level" class="select2" data-placeholder="请选择优先级" required>
                    <option value=""></option>
                    <?php
                    foreach ($level as $key => $value) {
                      echo '<option value="'.$key.'">'.$value['name'].' - '.$value['alt'].'</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">指派给谁 <span class="asterisk">*</span></label>
                <div class="col-sm-10">
                  <select id="accept_user" name="accept_user" class="select3" data-placeholder="请选择受理人" required>
                    <?php if ($devUser) { ?>
                    <option value="<?php echo $devUser; ?>"><?php echo $users[$devUser]['realname']; ?></option>
                    <?php } else { ?>
                    <option value=""></option>
                    <?php } ?>
                    <?php
                    if (isset($users) && $users) {
                      foreach ($users as $value) {
                    ?>
                    <option value="<?php echo $value['uid'];?>"><?php echo $value['realname'];?></option>
                    <?php
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">BUG标题 <span class="asterisk">*</span></label>
                <div class="col-sm-10">
                  <input type="text" id="subject" name="subject" class="form-control"  placeholder="请填写BUG标题" required/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">描述 <span class="asterisk">*</span></label>
                <div class="col-sm-10">
                  <textarea id="content" name="content" rows="3" class="form-control">[相关帐号]<br /><br />[描述]<br />[截图]<br /></textarea>
                </div>
              </div>
            </div><!-- panel-body -->
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" />
            <input type="hidden" value="<?php echo $row['id'];?>" id="issue_id" name="issue_id">
            <div class="panel-footer">
              <div class="row">
                <div class="col-sm-10 col-sm-offset-2">
                  <button class="btn btn-primary" id="btnSubmit">提交</button>
                </div>
              </div>
            </div>
          </div><!-- panel -->
          </form>
        </div><!-- col-md-6 -->
      </div><!--row -->
    </div><!-- contentpanel -->
  </div><!-- mainpanel -->
</section>

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="/static/simditor-2.3.6/scripts/module.js"></script>
<script type="text/javascript" src="/static/simditor-2.3.6/scripts/uploader.js"></script>
<script type="text/javascript" src="/static/simditor-2.3.6/scripts/hotkeys.js"></script>
<script type="text/javascript" src="/static/simditor-2.3.6/scripts/simditor.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.validate.min.js"></script>
<script src="/static/js/jquery.form.js"></script>
<script src="/static/js/jquery.gritter.min.js"></script>

<script src="/static/js/simple-pinyin.js"></script>
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
    }, 1000);
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

  $("#basicForm").submit(function(){
    $(this).ajaxSubmit({
      type:"post",
      url: "/bug/add_ajax",
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
  jQuery(".select2").select2({
      width: '150',
      minimumResultsForSearch: -1
  });
  jQuery(".select3").select2({
      width: '150'
  });

});

</script>
<script type="text/javascript">
   $(function(){
  toolbar = [ 'title', 'bold', 'italic', 'underline', 'strikethrough',
      'color', '|', 'ol', 'ul', 'blockquote', 'code', 'table', '|',
      'link', 'image', 'hr', '|', 'indent', 'outdent' ];
  var editor = new Simditor( {
    textarea : $('#content'),
    toolbar : toolbar,  //工具栏
    defaultImage : '/static/simditor-2.3.6/images/image.png', //编辑器插入图片时使用的默认图片
    pasteImage: true,
    upload: {
        url: '/admin/upload',
        params: {'<?php echo $this->security->get_csrf_token_name();?>':'<?php echo $this->security->get_csrf_hash();?>'}, //键值对,指定文件上传接口的额外参数,上传的时候随文件一起提交  
        fileKey: 'upload_file', //服务器端获取文件数据的参数名  
        connectionCount: 3,  
        leaveConfirm: '正在上传文件'
      }
  });
   })
</script>

</body>
</html>
