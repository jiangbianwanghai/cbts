<?php include('common_header.php');?>
    <div class="pageheader">
      <h2><i class="fa fa-pencil"></i> 基础信息配置 <span>配置代码库信息</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">你的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">我的控制台</a></li>
          <li><a href="/">基础信息配置</a></li>
          <li class="active">配置代码库信息</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
      
      <div class="row">
        
        <div class="col-md-12">
          <form method="POST" id="basicForm" action="/conf/repos_update" class="form-horizontal">
          <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-btns">
                  <a href="" class="panel-close">&times;</a>
                  <a href="" class="minimize">&minus;</a>
                </div>
                <h4 class="panel-title">增加代码库</h4>
                <p>请认真填写下面的选项</p>
              </div>
              <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">代码库名称 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <input type="text" id="repos_name" name="repos_name" value="<?php echo $row['repos_name'];?>" class="form-control" placeholder="请输入代码库名称" required />
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">代码库别名</label>
                  <div class="col-sm-9">
                    <input type="text" id="repos_name_other" name="repos_name_other" value="<?php echo $row['repos_name_other'];?>" class="form-control" />
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 control-label">代码库地址 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <input type="text" id="repos_url" name="repos_url" value="<?php echo $row['repos_url'];?>" class="form-control" placeholder="请输入代码库地址" required />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">说明 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <textarea id="repos_summary" name="repos_summary" rows="5" class="form-control" placeholder="请简要说明代码库的作用" required><?php echo $row['repos_summary'];?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">提测前合并 <span class="asterisk">*</span></label>
                  <div class="col-sm-9">
                    <div class="rdio rdio-primary">
                      <input type="radio" id="you" value="1" name="merge" <?php if ($row['merge'] === '1') { echo "checked";}?> required  />
                      <label for="you">需要</label>
                    </div><!-- rdio -->
                    <div class="rdio rdio-primary">
                      <input type="radio" value="0" id="wu" name="merge" <?php if ($row['merge'] === '0') { echo "checked";}?>>
                      <label for="wu">不需要</label>
                    </div><!-- rdio -->
                    <label class="error" for="merge"></label>
                  </div>
                </div>
              </div><!-- panel-body -->
              <input type="hidden" value="<?php echo $row['id'];?>" id="id" name="id">
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-9 col-sm-offset-3">
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

<script src="/static/js/custom.js"></script>

<script type="text/javascript" src="/static/simditor-2.3.6/scripts/module.js"></script>
<script type="text/javascript" src="/static/simditor-2.3.6/scripts/hotkeys.js"></script>
<script type="text/javascript" src="/static/simditor-2.3.6/scripts/simditor.js"></script>

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
      url: "/conf/repos_update",
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

var editor = new Simditor({
  textarea: $('#repos_summary')
  //optional options
});
</script>

</body>
</html>
