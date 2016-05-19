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
  <link href="/static/css/jquery.gritter.css" rel="stylesheet">

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
                        <li><i class="fa fa-arrow-circle-o-right"></i> 邮箱最好使用您的企业邮箱，方便提醒邮件的接收</li>
                        <li><i class="fa fa-arrow-circle-o-right"></i> 程序会根据你输入的邮箱自动获取邮箱名作为你的用户名</li>
                        <li><i class="fa fa-arrow-circle-o-right"></i> 邮箱名和用户名均是唯一的，不能与系统中的其他用户重复</li>
                        <li><i class="fa fa-arrow-circle-o-right"></i> CITS有一些保留字是不能注册为用户名的。比如：admin,webmaster,administrator,manage等。我们有权对你使用保留字的帐号进行修改。</li>
                        <li><i class="fa fa-arrow-circle-o-right"></i> 用户名只可以是英文或英文加数字或者纯数字（比如：手机号）</li>
                    </ul>
                </div><!-- signin0-info -->
            
            </div><!-- col-sm-7 -->
            
            <div class="col-md-5">
                
                <form method="post" action="/admin/login">
                    <h4 class="nomargin">注册</h4>
                    <p class="mt5 mb20">已有帐号，请移步 <a href="/admin/signin">登录</a></p>
                    <input name="email" id="email" type="text" class="form-control email" placeholder="建议使用工作邮箱" />
                    <input name="username" id="username" type="text" class="form-control uname" placeholder="用户名" />
                    <input name="password" id="password" type="password" class="form-control pword" placeholder="密码" />
                    <button name="button" id="button" type="button" class="btn btn-success btn-block">确认注册</button>
                    
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
<script src="/static/js/jquery.gritter.min.js"></script>

<script src="/static/js/custom.js"></script>

<script type="text/javascript">

  function reg() {
    username = $("#username").val();
    email = $("#email").val();
    password = $("#password").val();
    $.ajax({
      type: "POST",
      url: "/admin/reg",
      data: "username="+username+"&email="+email+"&password="+password+"&<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>",
      dataType: "JSON",
      success: function(data){
        if (data.status) {
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
            text: data.error,
              class_name: 'growl-danger',
              image: '/static/images/screen.png',
            sticky: false,
            time: ''
          });
          setTimeout(function(){
            location.href = '/admin/signup';
          }, 2000);
        }
      }
    });
  }

  $(document).ready(function(){
    $("#button").click(function(){
      reg();
    });

    $('input:text:first').focus();
    var $inp = $('input');
    $inp.keypress(function (e) {
      var key = e.which; //e.which是按键的值 
      if (key == 13) { 
        reg();
      } 
    }); 

    $("#username").focus(function() {
      var email = $("#email").val();
      email=email.substring(0,email.indexOf("@"));
      $(this).val(email);
    });

  });
</script>

</body>
</html>