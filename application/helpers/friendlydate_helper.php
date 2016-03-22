<?php
/**
     * 友好格式化时间
     * @param int $timestamp 时间
     * @param array $formats
     * @return string
     */
    function friendlydate($timestamp, $formats = null)
    {
        if ($formats == null) {
            $formats = array(
                'DAY'           => '%s天前',
                'DAY_HOUR'      => '%s天%s小时前',
                'HOUR'          => '%s小时',
                'HOUR_MINUTE'   => '%s小时%s分前',
                'MINUTE'        => '%s分钟前',
                'MINUTE_SECOND' => '%s分钟%s秒前',
                'SECOND'        => '%s秒前',
            );
        }
 
        /* 计算出时间差 */
        $seconds = time() - $timestamp;
        $minutes = floor($seconds / 60);
        $hours   = floor($minutes / 60);
        $days    = floor($hours / 24);
 
        if ($days > 0 && $days < 31) {
            $diffFormat = 'DAY';
        } elseif($days == 0) {
            $diffFormat = ($hours > 0) ? 'HOUR' : 'MINUTE';
            if ($diffFormat == 'HOUR') {
                $diffFormat .= ($minutes > 0 && ($minutes - $hours * 60) > 0) ? '_MINUTE' : '';
            } else {
                $diffFormat = (($seconds - $minutes * 60) > 0 && $minutes > 0)
                    ? $diffFormat.'_SECOND' : 'SECOND';
            }
        }else{
            $diffFormat = 'TURE_DATE_TIME';//超出30天, 正常时间显示
        }
 
        $dateDiff = null;
        switch ($diffFormat) {
            case 'DAY':
                $dateDiff = sprintf($formats[$diffFormat], $days);
                break;
            case 'DAY_HOUR':
                $dateDiff = sprintf($formats[$diffFormat], $days, $hours - $days * 60);
                break;
            case 'HOUR':
                $dateDiff = sprintf($formats[$diffFormat], $hours);
                break;
            case 'HOUR_MINUTE':
                $dateDiff = sprintf($formats[$diffFormat], $hours, $minutes - $hours * 60);
                break;
            case 'MINUTE':
                $dateDiff = sprintf($formats[$diffFormat], $minutes);
                break;
            case 'MINUTE_SECOND':
                $dateDiff = sprintf($formats[$diffFormat], $minutes, $seconds - $minutes * 60);
                break;
            case 'SECOND':
                $dateDiff = sprintf($formats[$diffFormat], $seconds);
                break;
            default:
                $dateDiff = date('Y-m-d H:i:s');
        }
        return $dateDiff;
    }