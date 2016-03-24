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
                  <div id="stacked-chart_test_my" class="body-chart">暂无数据</div>
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
            <div id="donut-chart2" class="ex-donut-chart"></div>
            </div><!-- panel-body -->
          </div><!-- panel -->

        </div><!-- col-sm-3 -->
        
        <div class="col-sm-4 col-md-3">

          <div class="panel panel-default">
            <div class="panel-body">
            <h5 class="subtitle mb5">提测最多的TA们</h5>
            <p class="mb15">显示提测最多的前5个人</p>
            <div id="donut-chart1" class="ex-donut-chart"></div>
            </div><!-- panel-body -->
          </div><!-- panel -->

        </div><!-- col-sm-3 -->

        <div class="col-sm-4 col-md-3">

          <div class="panel panel-default">
            <div class="panel-body">
            <h5 class="subtitle mb5">不通过最多的TA们</h5>
            <p class="mb15">显示提测最多的前5个人</p>
            <div id="donut-chart3" class="ex-donut-chart"></div>
            </div><!-- panel-body -->
          </div><!-- panel -->

        </div><!-- col-sm-3 -->

        <div class="col-sm-4 col-md-3">

          <div class="panel panel-default">
            <div class="panel-body">
            <h5 class="subtitle mb5">受理测试最多的TA们</h5>
            <p class="mb15">显示提测最多的前5个人</p>
            <div id="donut-chart4" class="ex-donut-chart"></div>
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
                  <div id="stacked-chart" class="body-chart">暂无数据</div>
                </div><!-- col-md-6 -->
                <div class="col-md-6">
                  <h5 class="subtitle mb5">提测量统计</h5>
                  <p class="mb15">统计最近30天的提测量(不通过量/其他状态[待测,在测试,通过,已覆盖])</p>
                  <div id="stacked-chart_test" class="body-chart">暂无数据</div>
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

  $.ajax({
    type: "GET",
    url: "/admin/issueAnalytics?picker=2016-02-24+-+2016-03-25",
    dataType: "text",
    success: function(data){
      if (data) {
        $("#stacked-chart_issue_my").html(data);
      }
    }
  });

  <?php if ($stacked) { ?>
  $("#stacked-chart").text('');
  var m1 = new Morris.Bar({
        element: 'stacked-chart',
        data: <?php echo $stacked;?>,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['关闭', '正常'],
        barColors: ['#428BCA', '#1CAF9A'],
        lineWidth: '1px',
        fillOpacity: 0.8,
        smooth: false,
        stacked: true,
        hideHover: true
  });
  <?php } ?>

  <?php if ($stacked_test) { ?>
  $("#stacked-chart_test").text('');
  var m2 = new Morris.Bar({
        element: 'stacked-chart_test',
        data: <?php echo $stacked_test;?>,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['其他状态', '不通过'],
        barColors: ['#F0AD4E', '#D9534F'],
        lineWidth: '1px',
        fillOpacity: 0.8,
        smooth: false,
        stacked: true,
        hideHover: true
  });
  <?php } ?>

  <?php if ($stackedMyTestStr) { ?>
  $("#stacked-chart_test_my").text('');
  var m3 = new Morris.Bar({
        element: 'stacked-chart_test_my',
        data: <?php echo $stackedMyTestStr;?>,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['其他状态', '<?php if ($role == 1) {?>待测+测试中<?php } ?><?php if ($role == 2) {?>不通过<?php } ?>'],
        barColors: ['#F0AD4E', '#D9534F'],
        lineWidth: '1px',
        fillOpacity: 0.8,
        smooth: false,
        stacked: true,
        hideHover: true
  });
  <?php } ?>

  var m5 = new Morris.Donut({
      element: 'donut-chart1',
      data: <?php echo $topUserStr;?>,
      colors: ['#D9534F','#1CAF9A','#428BCA','#5BC0DE','#428BCA']
  });

  var m6 = new Morris.Donut({
      element: 'donut-chart2',
      data: <?php echo $topUserIssueStr;?>,
      colors: ['#D9534F','#1CAF9A','#428BCA','#5BC0DE','#428BCA']
  });

  var m7 = new Morris.Donut({
      element: 'donut-chart3',
      data: <?php echo $topPassUserStr;?>,
      colors: ['#D9534F','#1CAF9A','#428BCA','#5BC0DE','#428BCA']
  });

  var m8 = new Morris.Donut({
      element: 'donut-chart4',
      data: <?php echo $topAcceptUserStr;?>,
      colors: ['#D9534F','#1CAF9A','#428BCA','#5BC0DE','#428BCA']
  });

  jQuery(window).resize(function() {
    <?php if ($stacked) { ?>m1.redraw();<?php } ?>
    <?php if ($stacked_test) { ?>m2.redraw();<?php } ?>
    <?php if ($stackedMyTestStr) { ?>m3.redraw();<?php } ?>
    m5.redraw();
    m6.redraw();
    m7.redraw();
    m8.redraw();
  }).trigger('resize');
  
});

</script>
</body>
</html>
