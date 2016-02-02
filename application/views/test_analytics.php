<?php include('common_header.php');?>

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> 提测统计 <span>了解一些统计数据，全面掌握提测状态</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">我的位置:</span>
        <ol class="breadcrumb">
          <li><a href="/">巧克力提测系统</a></li>
          <li><a href="/test/plaza">提测管理</a></li>
          <li class="active">提测统计</li>
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
          <h4 class="panel-title">提测统计</h4>
          <p>提供两种纬度进行分析：1.测试提交量，2.测试提交占比</p>
        </div><!-- panel-heading -->
        <div class="panel-body">

            <div class="row">
              <div class="col-md-6 mb30">
                <h5 class="subtitle mb5">测试提交量</h5>
                <p class="mb15">统计最近30天的任务提交量</p>
                <div id="barchart" style="width: 100%; height: 300px"></div>
              </div><!-- col-md-6 -->
              <div class="col-md-6 mb30">
                <h5 class="subtitle mb5">测试统计占比</h5>
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

    /***** BAR CHART *****/
    
    var bardata = [ ["Jan", 10], ["Feb", 23], ["Mar", 18], ["Apr", 13], ["May", 17], ["Jun", 30], ["Jul", 26], ["Aug", 16], ["Sep", 17], ["Oct", 5], ["Nov", 8], ["Dec", 15] ];

   jQuery.plot("#barchart", [ bardata ], {
      series: {
            lines: {
              lineWidth: 1  
            },
        bars: {
          show: true,
          barWidth: 0.5,
          align: "center",
               lineWidth: 0,
               fillColor: "#428BCA"
        }
      },
        grid: {
            borderColor: '#ddd',
            borderWidth: 1,
            labelMargin: 10
      },
      xaxis: {
        mode: "categories",
        tickLength: 0
      }
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
  
});

</script>
</body>
</html>
