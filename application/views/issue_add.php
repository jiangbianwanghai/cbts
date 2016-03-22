<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 我的任务 <span>添加任务</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/issue/my">我的任务</a></li>
          <li class="active">添加任务</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
      
      <div class="row">
        
        <div class="col-md-12">
          <form method="POST" id="basicForm" action="/issue/add_ajax" class="form-horizontal">
          <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-btns">
                  <a href="" class="panel-close">&times;</a>
                  <a href="" class="minimize">&minus;</a>
                </div>
                <h4 class="panel-title">添加任务</h4>
                <p>提测分两步：1.添加任务信息，2.添加版本库信息；</p>
              </div>
              <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">请选择类型 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <select id="type" name="type" class="select2" data-placeholder="请选择类型" required>
                      <option value=""></option>
                      <option value="1">TASK</option>
                      <option value="2">BUG</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">请选择优先级 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <select id="level" name="level" class="select2" data-placeholder="请选择优先级" required>
                      <option value=""></option>
                      <option value="4">[!!!!]非常紧急</option>
                      <option value="3">[!!!]优先处理</option>
                      <option value="2">[!!]正常迭代</option>
                      <option value="1">[!]抽空处理</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">名称 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <input type="text" id="issue_name" name="issue_name" class="form-control" placeholder="请输入任务名称" required />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">说明</label>
                  <div class="col-sm-9">
                    <textarea id="issue_summary" name="issue_summary" rows="3" class="form-control" placeholder="请输入任务描述"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">相关任务地址</label>
                  <div class="col-sm-9">
                    <input type="text" id="issue_url" name="issue_url" class="form-control" placeholder="请输入任务地址" />
                  </div>
                </div>
              </div><!-- panel-body -->
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
      url: "/issue/add_ajax",
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

});
</script>
<script type="text/javascript">
   $(function(){
  toolbar = [ 'title', 'bold', 'italic', 'underline', 'strikethrough',
      'color', '|', 'ol', 'ul', 'blockquote', 'code', 'table', '|',
      'link', 'image', 'hr', '|', 'indent', 'outdent' ];
  var editor = new Simditor( {
    textarea : $('#issue_summary'),
    placeholder : '这里输入内容...',
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
