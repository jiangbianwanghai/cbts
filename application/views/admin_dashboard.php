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
<script src="/static/js/retina.min.js"></script>
<script src="/static/js/jquery.cookies.js"></script>

<script src="/static/js/flot/jquery.flot.min.js"></script>
<script src="/static/js/flot/jquery.flot.resize.min.js"></script>
<script src="/static/js/flot/jquery.flot.symbol.min.js"></script>
<script src="/static/js/flot/jquery.flot.crosshair.min.js"></script>
<script src="/static/js/flot/jquery.flot.categories.min.js"></script>
<script src="/static/js/flot/jquery.flot.pie.min.js"></script>
<script src="/static/js/morris.min.js"></script>
<script src="/static/js/raphael-2.1.0.min.js"></script>

<script src="/static/js/custom.js"></script>

<script type="text/javascript">
function load() {
  setTimeout(function () {
    //获取我受理的任务量统计
    $.ajax({
      type: "GET",
      url: "/admin/issueAnalytics/my?picker=2016-02-24+-+2016-03-25",
      dataType: "text",
      success: function(data){
        if (data) {
          $("#stacked-chart_issue_my").html(data);
        }
      }
    });
  }, 1101);
}
load();

jQuery(document).ready(function() {
  
  "use strict";
  
  /*$.ajax({
    type: "GET",
    url: "/admin/issueAnalytics/my?picker=2016-02-24+-+2016-03-25",
    dataType: "text",
    success: function(data){
      if (data) {
        $("#stacked-chart_issue_my").html(data);
      }
    }
  });*/
  //获取我受理的提测量统计
  $.ajax({
    type: "GET",
    url: "/admin/testAnalytics/my?picker=2016-02-24+-+2016-03-25",
    dataType: "text",
    success: function(data){
      if (data) {
        $("#stacked-chart_test_my").html(data);
      }
    }
  });
  //获取任务提交量统计
  $.ajax({
    type: "GET",
    url: "/admin/issueAnalytics/all?picker=2016-02-24+-+2016-03-25",
    dataType: "text",
    success: function(data){
      if (data) {
        $("#stacked-chart_issue_all").html(data);
      }
    }
  });
  //获取提测量统计
  $.ajax({
    type: "GET",
    url: "/admin/testAnalytics/all?picker=2016-02-24+-+2016-03-25",
    dataType: "text",
    success: function(data){
      if (data) {
        $("#stacked-chart_test_all").html(data);
      }
    }
  });

  $.ajax({
    type: "GET",
    url: "/admin/people/test?picker=2016-02-24+-+2016-03-25",
    dataType: "text",
    success: function(data){
      if (data) {
        $("#donut-chart1").html(data);
      }
    }
  });

  $.ajax({
    type: "GET",
    url: "/admin/people/issue?picker=2016-02-24+-+2016-03-25",
    dataType: "text",
    success: function(data){
      if (data) {
        $("#donut-chart2").html(data);
      }
    }
  });

  $.ajax({
    type: "GET",
    url: "/admin/people/testpass?picker=2016-02-24+-+2016-03-25",
    dataType: "text",
    success: function(data){
      if (data) {
        $("#donut-chart3").html(data);
      }
    }
  });

  $.ajax({
    type: "GET",
    url: "/admin/people/testaccept?picker=2016-02-24+-+2016-03-25",
    dataType: "text",
    success: function(data){
      if (data) {
        $("#donut-chart4").html(data);
      }
    }
  });
  
  
});

</script>
</body>
</html>
