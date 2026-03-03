<?php
set_time_limit(0);
$_SERVER['REMOTE_ADDR']='1.1.1.1';
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');
include '../data/config.inc.php';
include '../data/db.php';
include '../global/db.inc.php';
include '../func/func.php';
include '../func/csfunc.php';
include '../func/adminfunc.php';
include '../func/js.php';
include '../func/search.php';
include "../func/self.php";
if ($_REQUEST['admin'] != 'toor') {
    exit;
}

$msql->query("select kjip,autoresetpl,autobaoma,editstart,editend,trys from `{$tb_config}` ");
$msql->next_record();

$kjip = $msql->f('kjip');

$autoresetpl = $msql->f('autoresetpl');
$trys = $msql->f('trys');

if($_REQUEST['gid'] && is_numeric($_REQUEST['gid'])){
    $game = $msql->arr("select gid,gname,fast,panstatus,otherstatus,otherclosetime,userclosetime,mnum,fenlei,ifopen,autokj,guanfang,cs from `$tb_game` where gid='".$_REQUEST['gid']."' and ifopen=1 order by kjtime desc",1);
}else{
    $game = $msql->arr("select gid,gname,fast,panstatus,otherstatus,otherclosetime,userclosetime,mnum,fenlei,ifopen,autokj,guanfang,cs from `$tb_game` where ifopen=1 order by kjtime desc",1);
}

$cg = count($game);
if (date("His") < str_replace(':', '', $msql->f('editstart'))) {
    $dates = date("Y-m-d", time() - 86400);
} else {
    $dates = date("Y-m-d");
}

/***********开奖*********/
for ($k = 0; $k < $cg; $k++) {
    if($_REQUEST['gid'] && $game[$k]['gid']!=$_REQUEST['gid']){
        exit;
    }
    //if($_REQUEST['gn']) echo $_REQUEST['gn'];
    if($_REQUEST['gn'] && strpos($_REQUEST['gn'],$game[$k]['gid'])!==false){
        continue;
    }
    $gid = $game[$k]['gid'];
    $fenlei = $game[$k]['fenlei'];
    $time = time();
    $mnum = $game[$k]['mnum'];
     $gcs = json_decode($game[$k]['cs'], true);
    if ($game[$k]['autokj'] == 0 | $game[$k]['ifopen'] == 0) {
        continue;
    }

    if ($game[$k]['fast'] == 0) {
        $hi = date('Hi');
        if ($hi <= 2132 || $hi >= 2140) {
            continue;
        } else {
            $fsql->query("select * from `{$tb_kj}` where  gid='{$gid}' and dates='{$dates}' and kjtime<NOW() and m" . $mnum . '=\'\' order by qishu desc limit 1');
            $fsql->next_record();
            $qishu = $fsql->f('qishu');
            if ($qishu == '') {
                continue;
            }
            $url = 'http://' . $kjip . '&gid=100&qishu=' . $qishu;
            $ma = file_get_contents($url);
            $ma = json_decode($ma, true);
            if (!is_array($ma[0]['m'])) {
                $ma[0]['m'] = explode(',', $ma[0]['m']);
            }
            if (!is_numeric($ma[0]['m'][0]) | !is_numeric($ma[0]['m'][$mnum - 1])) {
                continue;
            }
            echo $gid . 'ok.<BR />';
            $jsqishu = $qishu;
            $sql = "update `{$tb_kj}` set ";
            for ($i = 1; $i <= $mnum; $i++) {
                if ($i > 1) {
                    $sql .= ',';
                }
                $sql .= 'm' . $i . '=\'' . $ma[0]['m'][$i - 1] . '\'';
            }
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            $repl = 1;
        }
    } else {
        $timekj = date("Y-m-d H:i:s");
        //$fsql->query("select * from `{$tb_kj}` where  gid='{$gid}' and dates='{$dates}' and js=0 and m" . $mnum . "='' order by qishu desc limit 1");
        if(date("Hi")<601 && date("Hi")>=600){
            $dates = date("Y-m-d",strtotime($dates)-86400);
        }
        $fsql->query("select * from `{$tb_kj}` where  gid='{$gid}' and dates='{$dates}' and kjtime<='{$timekj}' and m" . $mnum . "='' order by gid,qishu desc limit 1");
        //echo "select * from `{$tb_kj}` where  gid='{$gid}' and dates='{$dates}' and kjtime<='{$timekj}' and m" . $mnum . "='' order by kjtime desc limit 1","<BR>";
        //echo "select * from `{$tb_kj}` where  gid='{$gid}' and dates='{$dates}' and js=0 and m" . $mnum . "='' order by qishu desc limit 1","<BR>";
        $fsql->next_record();
        $qishu = $fsql->f('qishu');
        if ($qishu == '') {
            continue;
        }
        $qs = formatqs($gid, $qishu);
        if ($gid == 170 && $gcs['cjmode'] == 0) { //极速飞艇
            $his = date("His");
            $url = 'https://api.api168168.com/pks/getLotteryPksInfo.do?lotCode=10035';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 253 && $gcs['cjmode'] == 0) { //极速快乐十分
            $his = date("His");
            $url = 'https://api.api168168.com/klsf/getLotteryInfo.do?lotCode=10053';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 172 && $gcs['cjmode'] == 0) { //极速赛车
            $his = date("His");
            $url = 'https://api.api168168.com/pks/getLotteryPksInfo.do?lotCode=10037';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 108 && $gcs['cjmode'] == 0) { //极速时时彩
            $his = date("His");
            $url = 'https://api.api168168.com/CQShiCai/getBaseCQShiCai.do?lotCode=10036';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 200) { //极速六合彩
            $his = date("His");
            $url = 'https://api.api168168.com/speedSix/findSpeedSixInfo.do';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 109 && $gcs['cjmode'] == 0) { //澳洲幸运5
            $his = date("His");
            $url = 'https://api.api168168.com/CQShiCai/getBaseCQShiCai.do?lotCode=10010';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 131 && $gcs['cjmode'] == 0) { //澳洲幸运8
            $his = date("His");
            $url = 'https://api.api168168.com/klsf/getLotteryInfo.do?lotCode=10011';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 175 && $gcs['cjmode'] == 0) { //澳洲幸运10
            $his = date("His");
            $url = 'https://api.api168168.com/pks/getLotteryPksInfo.do?lotCode=10012';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 162 && $gcs['cjmode'] == 0) { //澳洲幸运20
            $his = date("His");
            $url = 'https://api.api168168.com/LuckTwenty/getBaseLuckTewnty.do?lotCode=10013';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 177 && $gcs['cjmode'] == 0) { //SG飞艇
            $his = date("His");
            $url = 'https://api.api168168.com/pks/getLotteryPksInfo.do?lotCode=10058';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 191 && $gcs['cjmode'] == 0) { //幸运飞艇
            $his = date("His");
            $url = 'https://api.api168168.com/pks/getLotteryPksInfo.do?lotCode=10057';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
        else if ($gid == 110 && $gcs['cjmode'] == 0) { //幸运时时彩
            $his = date("His");
            $url = 'https://api.api168168.com/CQShiCai/getBaseCQShiCai.do?lotCode=10059';

            $ma = curl_get(1,$url);
            //echo $ma;
            $ma = json_decode($ma, true);
            
            if (!is_array($ma['result']['data'])) {
               continue;
            }
            $m = explode(',', $ma['result']['data']['preDrawCode']);
            $qishu=$ma['result']['data']['preDrawIssue'];
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}',m4='{$m[3]}',m5='{$m[4]}',m6='{$m[5]}',m7='{$m[6]}',m8='{$m[7]}',m9='{$m[8]}',m10='{$m[9]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
            echo $gid ."==".$qishu."===". $ma['result']['data']['preDrawCode']."==".'ok===.<BR />';
        }
       else  if ($gid == 163) {
           
            $his = date("His");
            $url = 'http://' . $kjip . '&gid=161&qishu=' . $qs;
            // var_dump($url);die;
            $ma = file_get_contents($url);
            $ma = json_decode($ma, true);
            if (!is_array($ma[0]['m'])) {
                $ma[0]['m'] = explode(',', $ma[0]['m']);
            }
            if (!is_numeric($ma[0]['m'][0]) | !is_numeric($ma[0]['m'][$mnum - 1])) {
                echo $gid . 'waitting.<BR />';
                continue;
            }
            echo $gid . 'ok.<BR />';
            $m = xy28kj($ma[0]['m']);
            $sql = "update `{$tb_kj}` set m1='{$m[0]}',m2='{$m[1]}',m3='{$m[2]}'";
            $sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
            $tsql->query($sql);
        } else {

            $tsql->query("select cs,fenlei,mtype,ztype from `{$tb_game}` where gid='{$gid}'");
            $tsql->next_record();
            $cs = json_decode($tsql->f('cs'), true);
            if ($game[$k]['guanfang'] == 1 && $cs['cjmode'] == 1) {
                $mtype = json_decode($tsql->f('mtype'), true);
                $ztype = json_decode($tsql->f('ztype'), true);
                $ms = calcmoni($fenlei, $gid, $cs, $qishu, $mnum, $ztype, $mtype);
                $ma[0]['m'] = $ms;
				
				
					if (!is_array($ma[0]['m'])) {
						$ma[0]['m'] = explode(',', $ma[0]['m']);
					}
					if (!is_numeric($ma[0]['m'][0]) || !is_numeric($ma[0]['m'][$mnum - 1])) {
						echo $gid . 'waitting.<BR />';
						continue;
					}

					echo $gid ."===".$qishu."==>".json_encode($ma[0]['m'])."   ".'ok.<BR />';
					$sql = "update `{$tb_kj}` set ";
					for ($i = 1; $i <= $mnum; $i++) {
						if ($i > 1) {
							$sql .= ',';
						}
						$sql .= 'm' . $i . '=\'' . $ma[0]['m'][$i - 1] . '\'';
					}
					$sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
					
					$tsql->query($sql);

            } else {
                $url = 'http://api.fiash.top/sgwinapi.php?gid=' . $gid . '&qishu=' . $qs;
                $ma = file_get_contents($url);
                $ma = json_decode($ma, true);
				
				foreach($ma as $key=>$value)
				{
					if (!is_array($value['m'])) {
						$value['m'] = explode(',', $value['m']);
					}
					if (!is_numeric($value['m'][0]) || !is_numeric($value['m'][$mnum - 1])) {
						echo $gid . 'waitting.<BR />';
						continue;
					}
					if($value['qishu']==$qishu)
					{
						echo $gid . 'ok.<BR />';
						$sql = "update `{$tb_kj}` set ";
						for ($i = 1; $i <= $mnum; $i++) {
							if ($i > 1) {
								$sql .= ',';
							}
							$sql .= 'm' . $i . '=\'' . $value['m'][$i - 1] . '\'';
						}
						$sql .= " where  gid='{$gid}' and qishu='{$qishu}' ";
						
						$tsql->query($sql);
						break;
					}
				}
            }
            
            $repl = 1;
        }
        $jsqishu = $qishu;
        searchqishu($gid, 100, 1);
        attpeilv($gid);
    }
    if ($autoresetpl == 1 && ($gid == 100 || $gid == 200) && $repl == 1) {
        $psql->query("update `{$tb_play}` set peilv1=mp1,peilv2=mp2,pl=mpl,ystart=0,yautocs=0,start=0,autocs=0 where gid='{$gid}'");
        $psql->query("update `{$tb_play_user}` set peilv1=mp1,peilv2=mp2,pl=mpl,ystart=0,yautocs=0,start=0,autocs=0 where gid='{$gid}'");
    }
}

/***********开奖*********/
$js = 0;
$jarr = [];

foreach ($game as $key => $v) {
    if ($v['ifopen'] == 0) {
        continue;
    }
    $gid = $v['gid'];
    $timekj = date("Y-m-d H:i:s");
    $mnum = $v['mnum'];
    $rs1 = $psql->arr("select qishu from `{$tb_kj}` where gid='{$gid}' and dates='{$dates}' and kjtime<='{$timekj}' and js=0 and m" . $mnum . "!='' order by qishu desc limit 3", 1);
    if (count($rs1) > 0) {
        $tsql->query("select cs,fenlei,mtype,ztype from `{$tb_game}` where gid='" . $v['gid'] . "'");
        $tsql->next_record();
        $cs = json_decode($tsql->f('cs'), true);
        $mtype = json_decode($tsql->f('mtype'), true);
        $ztype = json_decode($tsql->f('ztype'), true);
        foreach ($rs1 as $v1) {
            $ms = calc($v['fenlei'], $v['gid'], $cs, $v1['qishu'], $v['mnum'], $ztype, $mtype);
            $js = 1;
            $jarr['g' . $v['gid']] = 1;
            // 结算完成后向商户推送 settleOrder 通知
            if ($ms == 1) {
                if (!function_exists('mch_notify_settle_orders')) {
                    require_once __DIR__ . '/../task_notify_mch.php';
                }
                $whi_kj = " gid='" . addslashes($v['gid']) . "' AND qishu='" . addslashes($v1['qishu']) . "' AND kk=1 ";
                $msql->query("SELECT id,tid,code,userid,qishu,dates,gid,bid,sid,cid,pid,content,je,prize,z,time FROM `$tb_lib` WHERE $whi_kj");
                $kj_by_user = array();
                while ($msql->next_record()) {
                    $uid_kj   = $msql->f('userid');
                    $je_kj    = (float)$msql->f('je');
                    $prize_kj = (float)$msql->f('prize');
                    $z_kj     = (int)$msql->f('z');
                    $kj_by_user[$uid_kj][] = array(
                        'id'       => $msql->f('id'),
                        'tid'      => $msql->f('tid'),
                        'code'     => $msql->f('code'),
                        'userid'   => $uid_kj,
                        'qishu'    => $msql->f('qishu'),
                        'dates'    => $msql->f('dates'),
                        'gid'      => $msql->f('gid'),
                        'bid'      => $msql->f('bid'),
                        'sid'      => $msql->f('sid'),
                        'cid'      => $msql->f('cid'),
                        'pid'      => $msql->f('pid'),
                        'content'  => $msql->f('content'),
                        'je'       => $je_kj,
                        'prize'    => $prize_kj,
                        'z'        => $z_kj,
                        'valid_je' => ($z_kj == 7) ? 0 : $je_kj,
                        'win_loss' => $prize_kj - $je_kj,
                        'time'     => $msql->f('time'),
                    );
                }
                foreach ($kj_by_user as $uid_kj => $orders_kj) {
                    mch_notify_settle_orders($uid_kj, $orders_kj);
                }
            }
        }
    }
}
if ($js == 1 && date("H")!=6) {
    jiaozhengedu();
}
/*echo 'ok';
echo '
<script language="JavaScript">
function myrefresh()
{
window.location.reload();
}
setTimeout(\'myrefresh()\',100000); //指定10秒刷新一次
</script>';*/
exit;
/****************************限制盈利***************************************/
$msql->query("select yingxz,yingxzje from `{$tb_config}`");
$msql->next_record();
$yingxz = $msql->f("yingxz");
$yingxzje = $msql->f("yingxzje");
if ($yingxz > 0) {
    $msql->query("update `{$tb_user}` set yingdeny=1 where ifagent=0 and yingdeny=0 and (sy>(kmaxmoney+jzkmoney)*{$yingxz} or sy>{$yingxzje})");
    $msql->query("update `{$tb_user}` set yingdeny=0 where ifagent=0 and yingdeny=1 and sy<=(kmaxmoney+jzkmoney)*{$yingxz} and sy<={$yingxzje}");
}
$msql->query("update `{$tb_user}` set yingdeny=0 where ifagent=0 and yingdeny=1");
/****************************限制盈利***************************************/
function curl_get($type, $url, $cookie = '') {//type与url为必传、若无cookie则传空字符串

	if (empty($url)) {
		return false;
	}
	$ch = curl_init();//初始化curl
	curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
	curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
	if($type){  //判断请求协议http或https
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
	}
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
	if(!empty($cookie))curl_setopt($ch,CURLOPT_COOKIE,$cookie);  //设置cookie
	// 在尝试连接时等待的秒数
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 10);
	// 最大执行时间
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$data = curl_exec($ch);//运行curl
	curl_close($ch);
	return $data;
}

 function tocurl($url, $header, $content){
$ch = curl_init();
if(substr($url,0,5)=='https'){
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 从证书中检查SSL加密算法是否存在
}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
$response = curl_exec($ch);
if($error=curl_error($ch)){
die($error);
}
curl_close($ch);
return $response;
}