<?php if ($type == 'my') {?>
<?php if ($stackedMyIssueStr) { ?>
<div id="m4" class="body-chart">统计图标载入中</div>
<script type="text/javascript">
jQuery(document).ready(function() {
  $("#m4").text('');
  var m4 = new Morris.Bar({
        element: 'm4',
        data: <?php echo $stackedMyIssueStr;?>,
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
  m4.redraw();
});
</script>
<?php } else { 
  echo '暂无数据';
} ?>
<?php } ?>

<?php if ($type == 'all') {?>
<?php if ($stacked) { ?>
<div id="m1" class="body-chart">统计图标载入中</div>
<script type="text/javascript">
jQuery(document).ready(function() {
  $("#m1").text('');
  var m1 = new Morris.Bar({
        element: 'm1',
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
  m1.redraw();
});
</script>
<?php } else { 
  echo '暂无数据';
} ?>
<?php } ?>