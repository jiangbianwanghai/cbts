<?php
function countdown($timer) {
	$str = '';
	$rem = $timer - time();
	$day = round($rem / 86400);
	$hr  = floor(($rem % 86400) / 3600);
	$min = floor(($rem % 3600) / 60);
	$sec = ($rem % 60);
	if($day) $str .= "$day 天 ";
	if($hr) $str .= "$hr 小时 ";
	if($min) $str .= "$min 分钟 ";
	if($sec) $str .= "$sec 秒";
	return $str;
}