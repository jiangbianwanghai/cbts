<?php if ($type == 'test') {?>
<?php if ($topUserStr) { ?>
<div id="m5" class="body-chart">统计图标载入中</div>
<script type="text/javascript">
jQuery(document).ready(function() {
  $("#m5").text('');
  var m5 = new Morris.Donut({
      element: 'm5',
      data: <?php echo $topUserStr;?>,
      colors: ['#D9534F','#1CAF9A','#428BCA','#5BC0DE','#428BCA']
  });
  m5.redraw();
});
</script>
<?php } else { 
  echo '暂无数据';
} ?>
<?php } ?>

<?php if ($type == 'issue') {?>
<?php if ($topUserIssueStr) { ?>
<div id="m6" class="body-chart">统计图标载入中</div>
<script type="text/javascript">
jQuery(document).ready(function() {
  $("#m6").text('');
  var m6 = new Morris.Donut({
      element: 'm6',
      data: <?php echo $topUserIssueStr;?>,
      colors: ['#D9534F','#1CAF9A','#428BCA','#5BC0DE','#428BCA']
  });
  m6.redraw();
});
</script>
<?php } else { 
  echo '暂无数据';
} ?>
<?php } ?>

<?php if ($type == 'testpass') {?>
<?php if ($topPassUserStr) { ?>
<div id="m7" class="body-chart">统计图标载入中</div>
<script type="text/javascript">
jQuery(document).ready(function() {
  $("#m7").text('');
  var m7 = new Morris.Donut({
      element: 'm7',
      data: <?php echo $topPassUserStr;?>,
      colors: ['#D9534F','#1CAF9A','#428BCA','#5BC0DE','#428BCA']
  });
  m7.redraw();
});
</script>
<?php } else { 
  echo '暂无数据';
} ?>
<?php } ?>

<?php if ($type == 'testaccept') {?>
<?php if ($topAcceptUserStr) { ?>
<div id="m8" class="body-chart">统计图标载入中</div>
<script type="text/javascript">
jQuery(document).ready(function() {
  $("#m8").text('');
  var m8 = new Morris.Donut({
      element: 'm8',
      data: <?php echo $topAcceptUserStr;?>,
      colors: ['#D9534F','#1CAF9A','#428BCA','#5BC0DE','#428BCA']
  });
  m8.redraw();
});
</script>
<?php } else { 
  echo '暂无数据';
} ?>
<?php } ?>