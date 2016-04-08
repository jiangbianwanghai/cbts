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
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                  <h5 class="subtitle mb5"><?php if ($role == 1) {?>我受理的任务量统计<?php } ?><?php if ($role == 2) {?>我提交的任务量统计<?php } ?></h5>
                  <p class="mb15"><?php if ($role == 1) {?>受理的任务量统计(正常量/关闭量)<?php } ?><?php if ($role == 2) {?>提交的任务量(正常量/关闭量)<?php } ?></p>
                  <div id="stacked-chart_issue_my" class="body-chart"><img src="/static/images/loaders/loader3.gif" />载入中…</div>
                </div><!-- col-md-6 -->
                <div class="col-md-6">
                  <h5 class="subtitle mb5"><?php if ($role == 1) {?>我受理的提测量统计<?php } ?><?php if ($role == 2) {?>我申请的提交量统计<?php } ?></h5>
                  <p class="mb15"><?php if ($role == 1) {?>受理的提测量统计(待测+测试中/其他状态[不通过,通过,已覆盖])<?php } ?><?php if ($role == 2) {?>最近30天申请的提测量(不通过量/其他状态[待测,在测试,通过,已覆盖])<?php } ?></p>
                  <div id="stacked-chart_test_my" class="body-chart"><img src="/static/images/loaders/loader3.gif" />载入中…</div>
                </div><!-- col-md-6 -->
              </div><!-- row -->
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-sm-12 -->
      </div><!-- row -->
      <div class="row">

        <div class="col-sm-4 col-md-3">

          <div class="panel panel-default">
            <div class="panel-body">
            <h5 class="subtitle mb5">添加任务最多的TA们</h5>
            <p class="mb15">显示提测最多的前5个人</p>
            <div id="donut-chart2" class="ex-donut-chart"><img src="/static/images/loaders/loader3.gif" />载入中…</div>
            </div><!-- panel-body -->
          </div><!-- panel -->

        </div><!-- col-sm-3 -->
        
        <div class="col-sm-4 col-md-3">

          <div class="panel panel-default">
            <div class="panel-body">
            <h5 class="subtitle mb5">提测最多的TA们</h5>
            <p class="mb15">显示提测最多的前5个人</p>
            <div id="donut-chart1" class="ex-donut-chart"><img src="/static/images/loaders/loader3.gif" />载入中…</div>
            </div><!-- panel-body -->
          </div><!-- panel -->

        </div><!-- col-sm-3 -->

        <div class="col-sm-4 col-md-3">

          <div class="panel panel-default">
            <div class="panel-body">
            <h5 class="subtitle mb5">不通过最多的TA们</h5>
            <p class="mb15">显示提测最多的前5个人</p>
            <div id="donut-chart3" class="ex-donut-chart"><img src="/static/images/loaders/loader3.gif" />载入中…</div>
            </div><!-- panel-body -->
          </div><!-- panel -->

        </div><!-- col-sm-3 -->

        <div class="col-sm-4 col-md-3">

          <div class="panel panel-default">
            <div class="panel-body">
            <h5 class="subtitle mb5">受理测试最多的TA们</h5>
            <p class="mb15">显示提测最多的前5个人</p>
            <div id="donut-chart4" class="ex-donut-chart"><img src="/static/images/loaders/loader3.gif" />载入中…</div>
            </div><!-- panel-body -->
          </div><!-- panel -->

        </div><!-- col-sm-3 -->

      </div><!-- row -->
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                  <h5 class="subtitle mb5">任务提交量统计</h5>
                  <p class="mb15">统计最近30天的任务提交量(正常量/关闭量)</p>
                  <div id="stacked-chart_issue_all" class="body-chart"><img src="/static/images/loaders/loader3.gif" />载入中…</div>
                </div><!-- col-md-6 -->
                <div class="col-md-6">
                  <h5 class="subtitle mb5">提测量统计</h5>
                  <p class="mb15">统计最近30天的提测量(不通过量/其他状态[待测,在测试,通过,已覆盖])</p>
                  <div id="stacked-chart_test_all" class="body-chart"><img src="/static/images/loaders/loader3.gif" />载入中…</div>
                </div><!-- col-md-6 -->
              </div><!-- row -->
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div><!-- col-sm-12 -->
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
jQuery(document).ready(function() {
  
  "use strict";
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
