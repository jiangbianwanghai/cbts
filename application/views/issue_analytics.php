<?php include('common_header.php');?>

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> 任务统计 <span>了解一些统计数据，全面掌握提测状态</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">我的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">巧克力提测系统</a></li>
          <li><a href="/issue/plaza">任务管理</a></li>
          <li class="active">任务统计</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">
      
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-btns">
            <a href="" class="panel-close">&times;</a>
            <a href="" class="minimize">&minus;</a>
          </div><!-- panel-btns -->
          <h4 class="panel-title">任务统计</h4>
          <p>提供两种纬度进行分析：1.任务提交量，2.任务提交占比</p>
        </div><!-- panel-heading -->
        <div class="panel-body">

            <div class="row">
              <div class="col-md-6 mb30">
                <h5 class="subtitle mb5">任务提交量</h5>
                <p class="mb15">统计最近30天的任务提交量</p>
                <div id="stacked-chart" class="body-chart"></div>
              </div><!-- col-md-6 -->
              <div class="col-md-6 mb30">
                <h5 class="subtitle mb5">任务统计占比</h5>
                <p class="mb15">统计最近30天的任务提交占比</p>
                <div id="piechart" style="width: 100%; height: 300px"></div>
              </div><!-- col-md-6 -->
            </div><!-- row -->
              
        </div><!-- panel-body -->
      </div><!-- panel -->
            
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

  var m4 = new Morris.Bar({
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
    
    /***** PIE CHART *****/
    
    var piedata = [
        { label: "a", data: [[1,10]], color: '#D9534F'},
        { label: "b", data: [[1,30]], color: '#1CAF9A'},
        { label: "c", data: [[1,90]], color: '#F0AD4E'},
        { label: "d", data: [[1,70]], color: '#428BCA'},
        { label: "e", data: [[1,80]], color: '#5BC0DE'}
   ];
    
    jQuery.plot('#piechart', piedata, {
        series: {
            pie: {
                show: true,
                radius: 1,
                label: {
                    show: true,
                    radius: 2/3,
                    formatter: labelFormatter,
                    threshold: 0.1
                }
            }
        },
        grid: {
            hoverable: true,
            clickable: true
        }
    });
    
    function labelFormatter(label, series) {
    return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
  }

  jQuery(window).resize(function() {
    delay(function() {
      m4.redraw();
  }, 200);
   }).trigger('resize');
  
});

</script>
</body>
</html>
