<?php include('common_header.php');?>

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> 我的控制台 <span>了解一些统计数据，全面掌握提测状态</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">我的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">巧克力提测系统</a></li>
          <li class="active">我的控制台</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">
      <div id="bloglist" class="row">
        <?php
        if (file_exists('./cache/project.conf.php')) {
            require './cache/project.conf.php';
            require './cache/users.conf.php';
            foreach ($project as $key => $value) {
        ?>
        <div class="col-xs-6 col-sm-4 col-md-3">
          <div class="blog-item blog-quote">
            <div class="quote quote-primary">
                <a href="javascript:;">
                  <strong><?php echo $value['project_name'];?></strong>
                  <br />
                  <span style="font-size:13px;"><?php echo $value['project_discription'];?></span>
                  <small class="quote-author">- <?php echo $users[$value['add_user']]['realname'];?></small>
                </a>
              </div>
            <div class="blog-details">
              <ul class="blog-meta">
                <li>Create Time:<?php echo date("D M j G:i:s Y",$value['add_time']);?></li>
              </ul>
            </div><!-- blog-details -->
          </div><!-- blog-item -->
        </div><!-- col-xs-6 -->
        <?php
          }
        }
        ?>
        
      </div><!-- row -->
    </div><!-- contentpanel -->
  </div><!-- mainpanel -->
  <?php include('common_users.php');?>
</section>

<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/jquery-ui-1.10.3.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/masonry.pkgd.min.js"></script>

<script src="/static/js/custom.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
  $(".ajax-project").click(function(){
      project_name = $(".project_name").val();
      project_discription = $("#project_discription").val();
      if (!project_name) {
         alert('请填写绩效圈名称');
         $("#project_name").focus();
         return false;
      }
      if (!project_discription) {
         alert('请填写绩效圈简介');
         $("#project_discription").focus();
         return false;
      }
      $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "/project/add_ajax",
        data: "project_name="+project_name+"&project_discription="+project_discription,
        success: function(data){
          if (data.status) {
            location.href = '/';
          } else {
            alert('fail');
          } 
        }
      });
   });
});
</script>
</body>
</html>
