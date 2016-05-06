<?php
function timediff( $begin_time, $end_time, $arr = 0, $fix = 0 ) {
  if ( $begin_time < $end_time ) {
    $starttime = $begin_time;
    $endtime = $end_time;
    $fix_str = '还剩 ';
  } else {
    $starttime = $end_time;
    $endtime = $begin_time;
    $fix_str = '超出 ';
  }
  $timediff = $endtime - $starttime;
  $days = intval( $timediff / 86400 );
  $remain = $timediff % 86400;
  $hours = intval( $remain / 3600 );
  $remain = $remain % 3600;
  $mins = intval( $remain / 60 );
  $secs = $remain % 60;
  if ($arr) {
  	$res = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
  } else {
    $res = '';
    if ($fix) {
      $res = $fix_str;
    }
  	if ($days)
  		$res .= $days.'天';
  	if ($hours)
  		$res .= $hours.'小时';
  	if ($mins)
  		$res .= $mins.'分钟';
  	if ($secs)
  		$res .= $secs.'秒';
  }
  
  return $res;
}