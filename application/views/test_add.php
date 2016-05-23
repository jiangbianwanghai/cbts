<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 提测管理 <span>提交代码</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/test/my">提测管理</a></li>
          <li class="active">提交代码</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
      <div class="row">
        <div class="col-sm-3 col-lg-2">
          <h5 class="subtitle">快捷方式</h5>
          <ul class="nav nav-pills nav-stacked nav-email">
            <li><a href="/test"><i class="glyphicon glyphicon-folder-close"></i> 任务列表</a></li>
            <li><a href="/test/index/to_me"><i class="glyphicon glyphicon-folder-close"></i> 我负责的</a></li>
            <li><a href="/test/index/from_me"><i class="glyphicon glyphicon-folder-close"></i> 我创建的</a></li>
          </ul>
        </div><!-- col-sm-3 -->
        <div class="col-sm-9 col-lg-10">
          <form method="POST" id="basicForm" enctype="multipart/form-data" action="/test/add_ajax" class="form-horizontal">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">提交代码</h4>
              <p>请认真填写下面的选项。如果没有分支可以不填写，分支名字相对与branches下的目录名，比如branches/abc，只需要输入abc即可。</p>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <label class="col-sm-2 control-label">相关任务 <span class="asterisk">*</span></label>
                <div class="col-sm-10">
                  <input type="text" id="issue_name" name="issue_name" class="form-control" value="<?php echo $row['issue_name']?>" disabled="" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">代码库 <span class="asterisk">*</span></label>
                <div class="col-sm-10">
                  <select id="repos_id" name="repos_id" class="select2-2" data-placeholder="请选择代码库" required>
                    <option value=""></option>
                    <?php
                    if (isset($repos) && $repos) {
                      foreach ($repos as $value) {
                    ?>
                    <option value="<?php echo $value['id'];?>"><?php echo $value['repos_name_other'];?></option>
                    <?php
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">分支名称</label>
                <div class="col-sm-10" id="br-w">
                  <select id="br" name="br" class="select3" data-placeholder="请选择分支" required>
                    <option value=""></option>
                  </select><div id="br-loading"></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">版本号 <span class="asterisk">*</span></label>
                <div class="col-sm-10">
                  <input type="text" id="test_flag" name="test_flag" class="form-control" placeholder="请输入版本号" required />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">说明 </label>
                <div class="col-sm-10">
                  <textarea id="test_summary" name="test_summary" rows="5" class="form-control"><?php if ($this->uri->segment(4, 0)) {echo '修复QA反馈BUG#'.$this->uri->segment(4, 0);}?></textarea>
                </div>
              </div>
            </div><!-- panel-body -->
            <input type="hidden" value="<?php echo $row['id'];?>" id="issue_id" name="issue_id">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" />
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
      url: "/test/add_ajax",
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

  jQuery(".select2-2").select2({
      width: '100%'
  });

  jQuery(".select2").select2({
      width: '100%'
  });

  jQuery(".select3").select2({
      width: '100%'
  });

  $("#repos_id").change(function(){
    reposId = $(this).val();
    $("#br-loading").html('<small><img src="/static/images/loaders/loader3.gif" />分支信息加载中...</small>');
    $.ajax({
      type: "GET",
      url: "/test/getbr/"+reposId,
      dataType: "JSON",
      success: function(data){
        if (data.status) {
          $("#br").html(data.output);
          $("#br-loading").text('');
        } else {
          $("#br-loading").html("<small>error:"+data.error+" code:"+data.code+"</small>");
        }
      }
    });
  });

});

</script>
<script type="text/javascript">
   $(function(){
  toolbar = [ 'title', 'bold', 'italic', 'underline', 'strikethrough',
      'color', '|', 'ol', 'ul', 'blockquote', 'code', 'table', '|',
      'link', 'image', 'hr', '|', 'indent', 'outdent' ];
  var editor = new Simditor( {
    textarea : $('#test_summary'),
    placeholder : '这里输入内容...',
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
