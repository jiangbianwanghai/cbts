<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>CITS - Chocolate Issue Tracker System</title>

  <link href="/static/css/style.default.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script>alert("您的浏览器版本过低，建议您使用Chrome,Firefox,IE9及以上版本")</script>
  <script src="/static/js/html5shiv.js"></script>
  <script src="/static/js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="signin">

<section>
  
    <div class="signinpanel">
        
        <div class="row">
            
            <div class="col-md-7">
                
                <div class="signin-info">
                    <div class="logopanel">
                        <h1><span>//</span> 巧克力任务跟踪系统 <span>//</span></h1>
                    </div><!-- logopanel -->
                
                    <div class="mb20"></div>
                
                    <h5><strong>CITS - Chocolate Issue Tracker System</strong></h5>
                    <ul style="line-height:27px;">
                        <li><i class="fa fa-arrow-circle-o-right"></i> 开发计划轻松掌握</li>
                        <li><i class="fa fa-arrow-circle-o-right"></i> 任务执行情况跟踪</li>
                        <li><i class="fa fa-arrow-circle-o-right"></i> 代码提测一键部署</li>
                        <li><i class="fa fa-arrow-circle-o-right"></i> 过程数据跟踪分析</li>
                        <li><i class="fa fa-arrow-circle-o-right"></i> 静态分析持续集成</li>
                    </ul>
                    <div class="mb20"></div>
                    <strong>CITS不仅是一款任务管理工具，更是您工作改进的好帮手~</strong>
                </div><!-- signin0-info -->
            
            </div><!-- col-sm-7 -->
            
            <div class="col-md-5">
                
                <form method="post" action="/admin/login">
                    <h4 class="nomargin">登录</h4>
                    <p class="mt5 mb20">没有帐号，请移步 <a href="/admin/signup">注册</a></p>
                
                    <input name="username" id="username" type="text" class="form-control uname" placeholder="用户名" />
                    <input name="password" id="password" type="password" class="form-control pword" placeholder="密码" />
                    <button name="button" id="button" type="button" class="btn btn-success btn-block">登入</button>
                    
                </form>
            </div><!-- col-sm-5 -->
            
        </div><!-- row -->
        
        <div class="signup-footer">
            <div class="pull-left">
                &copy; 2016. All Rights Reserved.
            </div>
            <div class="pull-right">
                有问题请联系: <a href="mailto:webmaster@jiangbianwanghai.com" target="_blank">江边望海</a>
            </div>
        </div>
        
    </div><!-- signin -->
  
</section>


<script src="/static/js/jquery-1.11.1.min.js"></script>
<script src="/static/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/modernizr.min.js"></script>
<script src="/static/js/jquery.sparkline.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/toggles.min.js"></script>
<script src="/static/js/retina.min.js"></script>

<script src="/static/js/custom.js"></script>

<script type="text/javascript">

  function login() {
    username = $("#username").val();
    password = $("#password").val();
    $.ajax({
      type: "POST",
      url: "/admin/login",
      data: "username="+username+"&password="+password+"&<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>",
      dataType: "JSON",
      success: function(data){
        if (data.status) {
          location.href = data.url;
        } else {
          location.href = data.url;
        }
      }
    });
  }

  $(document).ready(function(){
    $("#button").click(function(){
      login();
    });

    $('input:text:first').focus();
    var $inp = $('input');
    $inp.keypress(function (e) {
      var key = e.which; //e.which是按键的值 
      if (key == 13) { 
        login();
      } 
    }); 

  });
</script>

</body>
</html>