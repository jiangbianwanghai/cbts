<?php if ($type == 'my') {?>
<?php if ($stackedMyTestStr) { ?>
<div id="m3" class="body-chart">统计图标载入中</div>
<script type="text/javascript">
jQuery(document).ready(function() {
  $("#m3").text('');
  var m3 = new Morris.Bar({
        element: 'm3',
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
  m3.redraw();
});
</script>
<?php } else { 
  echo '暂无数据';
} ?>
<?php } ?>

<?php if ($type == 'all') {?>
<?php if ($stacked_test) { ?>
<div id="m2" class="body-chart">统计图标载入中</div>
<script type="text/javascript">
  $("#m2").text('');
  var m2 = new Morris.Bar({
        element: 'm2',
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
  m2.redraw();
</script>
<?php } else { 
  echo '暂无数据';
} ?>
<?php } ?>