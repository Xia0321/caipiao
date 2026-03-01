<?php
include('../data/comm.inc.php');



$time = time();
$_orig_qishu   = $config['thisqishu'];
$_fix_trigger  = 0;
$_opentime_raw = '';
$msql->query("select opentime,closetime,kjtime from `$tb_kj` where gid='$gid' and qishu='" . $config['thisqishu'] . "'");
$msql->next_record();
$_opentime_raw = $msql->f('opentime');
// 修复：thisqishu 被提前切换时（opentime 还未到或记录不存在），回退到实际当前期
if ($msql->f('opentime') == '' || strtotime($msql->f('opentime')) > $time) {
    $_fix_trigger = 1;
    $now_sql = date('Y-m-d H:i:s', $time);
    $msql->query("select opentime,closetime,kjtime,qishu from `$tb_kj` where gid='$gid' and opentime <= '$now_sql' order by qishu desc limit 1");
    $msql->next_record();
    if ($msql->f('qishu') != '') $config['thisqishu'] = $msql->f('qishu');
}
if ($config['panstatus'] == 1 & (($time - strtotime($msql->f('opentime'))-$config['times']['o'])>0 | $config['autoopenpan']==0)) {
    $pantime = strtotime($msql->f('closetime')) - $time - $config['userclosetime']-$config['times']['c'];
} else {
	$config['panstatus'] = 0;
    $pantime = $time - strtotime($msql->f('opentime'))-$config['times']['o'];
    if ($pantime > 0)
        $pantime = 3;
}
if ($config['otherstatus'] == 1 & ($config['autoopenpan']==0 | ($time - strtotime($msql->f('opentime'))-$config['times']['o'])>0)) {
    $othertime = strtotime($msql->f('closetime')) - $time - $config['userclosetime'] - $config['otherclosetime']-$config['times']['c'];
} else {
	$config['otherstatus'] = 0;
    $othertime = $time - strtotime($msql->f('opentime'))-$config['times']['o'];
    if ($othertime > 0)
        $othertime = 3;
}
if ($config['autoopenpan'] == 0 | $config['times']['io']==0) {
    $pantime = 9999;
    $othertime = 9999;
}
$kjtime = strtotime($msql->f('kjtime'))-time();
$_kjtime_db = $msql->f('kjtime');

include('../func/userfunc.php');
$check=0;
if($_SESSION['uuid']!='' && $_SESSION['ucheck']==md5($config['allpass'].$_SESSION['uuid']) ){
	 $check=1;
}
$msql->query("select passcode,savetime from `$tb_online` where userid='".$_SESSION['uuid']."' and xtype=2");
$msql->next_record();
if($msql->f('passcode')!=$_SESSION['upasscode']){
	 $check=0;
}
if($check==0 | $config['ifopen']==0){
	 sessiondelu();
}
$_logfile = dirname(__FILE__) . '/time_debug.log';
$_logline = date('Y-m-d H:i:s')
    . ' gid='    . $gid
    . ' orig='   . $_orig_qishu
    . ' final='  . $config['thisqishu']
    . ' fix='    . $_fix_trigger
    . ' ot_raw=' . $_opentime_raw
    . ' ot_ts='  . ($_opentime_raw ? strtotime($_opentime_raw) : 0)
    . ' now_ts=' . $time
    . ' kjsecs=' . $kjtime
    . ' pan='    . $config['panstatus']
    . "\n";
@file_put_contents($_logfile, $_logline, FILE_APPEND);
echo abs($pantime) . '|' . abs($othertime).'|'.$config['thisqishu'].'|'.$config['panstatus'].'|'.$config['otherstatus'].'|'.date("Hi").'|'.$check.'|'.$kjtime;
?>