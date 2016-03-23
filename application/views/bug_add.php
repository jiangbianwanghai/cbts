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
        
        <div class="col-md-12">
          <form method="POST" id="basicForm" enctype="multipart/form-data" action="/bug/add_ajax" class="form-horizontal">
          <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-btns">
                  <a href="" class="panel-close">&times;</a>
                  <a href="" class="minimize">&minus;</a>
                </div>
                <h4 class="panel-title">反馈BUG</h4>
                <p>请认真填写下面的选项</p>
              </div>
              <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">相关任务 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <input type="text" id="issue_id" name="issue_id" class="form-control" value="<?php echo $row['issue_name']?>#<?php echo $row['issue_id']?>" disabled="" />
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label">代码库及版本号 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <input type="text" id="repos_id" name="repos_id" class="form-control" value="<?php echo $repos[$row['repos_id']]['repos_name']?>#<?php echo $row['test_flag'];?>" disabled="" />
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">BUG标题 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <input type="text" id="subject" name="subject" class="form-control"  placeholder="请填写BUG标题" required/>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">描述 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <textarea id="content" name="content" rows="3" class="form-control">[相关帐号]<br /><br />[描述]<br />[截图]<br /></textarea>
                  </div>
                </div>
              </div><!-- panel-body -->
              <input type="hidden" value="<?php echo $row['issue_id'];?>" id="issue_id" name="issue_id">
              <input type="hidden" value="<?php echo $row['id'];?>" id="test_id" name="test_id">
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-9 col-sm-offset-3">
                    <button class="btn btn-primary" id="btnSubmit">提交</button>
                    <button type="reset" class="btn btn-default">重置</button>
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
<script src="/static/js/retina.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/jquery.validate.min.js"></script>
<script src="/static/js/jquery.form.js"></script>
<script src="/static/js/jquery.gritter.min.js"></script>

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
    upload: {
        url: '/admin/upload',
        params: null, //键值对,指定文件上传接口的额外参数,上传的时候随文件一起提交  
        fileKey: 'upload_file', //服务器端获取文件数据的参数名  
        connectionCount: 3,  
        leaveConfirm: '正在上传文件'
      }
  });
   })
</script>

</body>
</html>
