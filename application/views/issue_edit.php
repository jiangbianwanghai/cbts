<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 任务管理 <span>编辑任务</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">CITS</a></li>
          <li><a href="/issue">任务管理</a></li>
          <li class="active">编辑任务</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
      
      <div class="row">
        <div class="col-sm-3 col-lg-2">
          <h5 class="subtitle">快捷方式</h5>
          <ul class="nav nav-pills nav-stacked nav-email">
            <li><a href="/issue"><i class="glyphicon glyphicon-folder-close"></i> 任务列表</a></li>
            <li><a href="/issue/index/to_me"><i class="glyphicon glyphicon-folder-close"></i> 我负责的</a></li>
            <li><a href="/issue/index/from_me"><i class="glyphicon glyphicon-folder-close"></i> 我创建的</a></li>
          </ul>
        </div><!-- col-sm-3 -->
        <div class="col-sm-9 col-lg-10">
          <form method="POST" id="basicForm" action="/issue/edit_ajax" class="form-horizontal">
          <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">编辑任务</h4>
                <p>每个任务都应该包含在计划中</p>
              </div>
              <div class="panel-body">
                <div class="col-sm-8 col-lg-9">
                  <div class="form-group">
                    <label class="control-label">任务全称 <span class="asterisk">*</span></label>
                    <div>
                      <input type="text" id="issue_name" name="issue_name" value="<?php echo $row['issue_name'];?>" class="form-control" placeholder="请输入任务名称" required />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label">说明</label>
                    <div>
                      <textarea id="issue_summary" name="issue_summary" rows="5" class="form-control" placeholder="请输入任务描述"><?php echo $row['issue_summary'];?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label">任务地址</label>
                    <div>
                      <textarea id="issue_url" name="issue_url" rows="3" class="form-control" placeholder="每行一个链接，可以添加多个"><?php
                        if ($row['url']) {
                          foreach ($row['url'] as $key => $value) {
                            echo $value."\n";
                          }
                        }
                        ?></textarea>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4 col-lg-3" style="padding-left:50px;">
                  <div class="form-group">
                    <label class="control-label">请选择类型 <span class="asterisk">*</span></label>
                    <div>
                      <select id="type" name="type" class="select2" data-placeholder="请选择类型" required>
                        <option value=""></option>
                        <option value="2"<?php if ($row['type'] == 2) { echo " selected=\"selected\"";}?>>BUG</option>
                        <option value="1"<?php if ($row['type'] == 1) { echo " selected=\"selected\"";}?>>TASK</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label">请选择优先级 <span class="asterisk">*</span></label>
                    <div>
                      <select id="level" name="level" class="select2" data-placeholder="请选择优先级" required>
                        <option value=""></option>
                        <?php
                        foreach ($level as $key => $value) {
                          $selected = '';
                          $key == $row['level'] && $selected = " selected=\"selected\"";
                          echo '<option value="'.$key.'"'.$selected.'>'.$value['name'].' - '.$value['task'].'</option>';
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div><!-- panel-body -->
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" />
              <input type="hidden" value="<?php echo $row['id'];?>" id="id" name="id">
              <div class="panel-footer">
                <div class="row">
                  <button class="btn btn-primary" id="btnSubmit">提交</button>
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
      url: "/issue/edit_ajax",
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
      width: '100%',
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
