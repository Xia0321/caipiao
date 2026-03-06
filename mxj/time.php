<?php
include('../data/comm.inc.php');



$time = time();
$msql->query("select opentime,closetime,kjtime from `$tb_kj` where gid='$gid' and qishu='" . $config['thisqishu'] . "'");

$msql->next_record();
// 修复：thisqishu  被提前切换时（opentime 还未到或记录不存在），回退到实际当前期
if ($msql->f('opentime') == '' || strtotime($msql->f('opentime')) > $time) {
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
        $pantime = 0;
}
if ($config['otherstatus'] == 1 & ($config['autoopenpan']==0 | ($time - strtotime($msql->f('opentime'))-$config['times']['o'])>0)) {
    $othertime = strtotime($msql->f('closetime')) - $time - $config['userclosetime'] - $config['otherclosetime']-$config['times']['c'];
} else {
	$config['otherstatus'] = 0;
    $othertime = $time - strtotime($msql->f('opentime'))-$config['times']['o'];
    if ($othertime > 0)
        $othertime = 0;
}
if ($config['autoopenpan'] == 0 | $config['times']['io']==0) {
    $pantime = 9999;
    $othertime = 9999;
}
$kjtime = strtotime($msql->f('kjtime'))-time();

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
$config['thisqishu'] = substr($config['thisqishu'], -8);
$_logfile = dirname(__FILE__) . '/time_debug.log';
$_logline = date('Y-m-d H:i:s') . ' qishu=' . $config['thisqishu'] . ' pan=' . $config['panstatus'] . ' kjsecs=' . $kjtime . "\n";
@file_put_contents($_logfile, $_logline, FILE_APPEND);
echo abs($pantime) . '|' . abs($othertime).'|'.$config['thisqishu'].'|'.$config['panstatus'].'|'.$config['otherstatus'].'|'.date("Hi").'|'.$check.'|'.$kjtime;
?>