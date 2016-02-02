<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>巧克力(Choc)提测系统-Beta</title>

  <link href="/static/css/style.default.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="signin">

<section>
  
    <div class="signinpanel">
        
        <div class="row">
            
            <div class="col-md-7">
                
                <div class="signin-info">
                    <div class="logopanel">
                        <h1><span>[</span> Choc <span>]</span></h1>
                    </div><!-- logopanel -->
                
                    <div class="mb20"></div>
                
                    <h5><strong>欢迎使用巧克力（Choc）提测系统</strong></h5>
                    <ul>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> 跟踪提测代码进度</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> 提测状态Rtx提醒</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> 一键合并代码</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> 一键提测到测试环境</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> 全面了解每个产品线的健康状况</li>
                    </ul>
                    <div class="mb20"></div>
                    <strong>不需要注册，可以直接使用Rtx帐号登录本系统</strong>
                </div><!-- signin0-info -->
            
            </div><!-- col-sm-7 -->
            
            <div class="col-md-5">
                
                <form method="post" action="/admin/login">
                    <h4 class="nomargin">登录</h4>
                    <p class="mt5 mb20">使用RTX帐号登录本系统</p>
                
                    <input name="username" id="username" type="text" class="form-control uname" placeholder="用户名" />
                    <input name="password" id="password" type="password" class="form-control pword" placeholder="密码" />
                    <button name="button" id="button" type="button" class="btn btn-success btn-block">登入</button>
                    
                </form>
            </div><!-- col-sm-5 -->
            
        </div><!-- row -->
        
        <div class="signup-footer">
            <div class="pull-left">
                &copy; 2016. All Rights Reserved. Gongchang.com
            </div>
            <div class="pull-right">
                有问题请联系: <a href="mailto:liqiming@gongchang.com" target="_blank">江边望海</a>
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
      data: "username="+username+"&password="+password,
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