<?php
include('../data/comm.inc.php');
include('../data/myadminvar.php');
include('../func/func.php');
include("../func/csfunc.php");
include('../func/adminfunc.php');
include('../include.php');
include('./checklogin.php');
$msql->query("SHOW TABLES LIKE  '%total%'");
$msql->next_record();
$bigdata=0;
if($msql->f(0)=='x_lib_total'){
    $tb_lib = "x_lib_total";
    $bigdata=1;
}
// $tb_game = 'x_game';
// $gid = '121';

switch ($_REQUEST['xtype']) {
    case "show":
        if (isset($_REQUEST['gid']) && $_REQUEST['gid'] !== '' && is_numeric($_REQUEST['gid'])) {
            $gid = $_REQUEST['gid'];
            $_SESSION['kj_gid'] = $gid;
        } elseif (isset($_SESSION['kj_gid']) && $_SESSION['kj_gid'] !== '' && is_numeric($_SESSION['kj_gid'])) {
            $gid = $_SESSION['kj_gid'];
        } else {
            $msql->query("select gid from `$tb_game` where ifopen=1 order by xsort limit 1");
            if ($msql->next_record() && $msql->f('gid') !== '' && $msql->f('gid') !== null) {
                $gid = $msql->f('gid');
                $_SESSION['kj_gid'] = $gid;
            }
        }
        if (empty($gid)) $gid = '107';
        $msql->query("select * from `$tb_game` where gid='$gid' ");
        $msql->next_record();
        $game                   = array();
        $game[0]['gid']         = $msql->f('gid');
        $game[0]['gname']       = $msql->f('gname');
        $game[0]['fenlei']      = $msql->f('fenlei');
        $game[0]['fast']        = $msql->f('fast');
        $game[0]['baostatus']   = $msql->f('baostatus');
        $game[0]['autokj']      = $msql->f('autokj');
        $game[0]['autoopenpan'] = $msql->f('autoopenpan');
        $game[0]['panstatus']   = $msql->f('panstatus');
        $game[0]['otherstatus'] = $msql->f('otherstatus');
        $game[0]['thisqishu']   = $msql->f('thisqishu');
		$game[0]['fast']   = $msql->f('fast');
		$game[0]['guanfang']   = $msql->f('guanfang');        
        $game[0]['thisbml'] = $msql->f('thisbml');
		$tpl->assign('cs', json_decode($msql->f('cs'),true));
        $msql->query("select * from `$tb_game` where gid!='$gid' and ifopen=1 order by xsort");
        $i = 1;
        while ($msql->next_record()) {
            $game[$i]['gid']   = $msql->f('gid');
            $game[$i]['gname'] = $msql->f('gname');
            $game[$i]['fast']  = $msql->f('fast');
            $i++;
        }
        $sdate = week();
        $tpl->assign("sdate", $sdate);
        $tpl->assign('game', $game);
	
		$tpl->assign('config', $config);
        $tpl->display("kj.html");
        break;
	case "editguanfang":
        if ($_POST['pass'] != $config['supass'] && $_SESSION['hides'] != 1) {
            echo 2;
            exit;
        }
		$gid   = $_POST['gid'];
		$msql->query("select cs from `$tb_game` where gid='$gid'");
		$msql->next_record();
		$cs= json_decode($msql->f('cs'),true);		
		$cs['kongje'] = $_POST['kongje'];
        $cs['zcmode'] = $_POST['zcmode'];
        $cs['cjmode'] = $_POST['cjmode'];
        $cs['xtmode'] = $_POST['xtmode'];
        $cs['zhiding'] = $_POST['zhiding'];
        $cs['zduser'] = $_POST['zduser'];
        $cs['suiji'] = $_POST['suiji'];
        $cs['ylup'] = $_POST['ylup'];
        $cs['shenglv'] = $_POST['shenglv'];
        if(!is_numeric($cs["kongje"]) || $cs["kongje"]%1!=0 || $cs["kongje"]<0){
            $cs["kongje"] = 0;
        }
        if(!is_numeric($cs["ylup"]) || $cs["ylup"]%1!=0 || $cs["ylup"]<0){
            $cs["ylup"] = 0;
        }
        if(!in_array($cs["shenglv"],[21,31,32,41,42,43,51,52,53,54,61,71,72,73,81,83,91,92])){
            $cs['shenglv'] = 31;
        }
        if(!is_numeric($cs["suiji"]) || $cs["suiji"]%1!=0 || $cs["suiji"]<50){
            $cs["suiji"] = 100;
        }
        if($cs["suiji"]>5000){
            $cs["suiji"] = 5000;
        }
        if($cs["xtmode"]==5){
            $cs["yingqs"] = 0;
            $cs["shuqs"] = 0;
        }
        if (!mb_ereg("^[\w\-\.]{2,32}$", $cs['zduser'])) {
            $cs['zduser'] = "";
            $cs['zhiding'] = 0;
        }else{
            $msql->query("select userid,username from `$tb_user` where username='".$cs['zduser']."'");
            $msql->next_record();
            if($msql->f("username")!=$cs['zduser']){
                $cs['zduser'] = "";
                $cs['zhiding'] = 0;
            }
        }        
		$cs = json_encode($cs);
		$msql->query("update `$tb_game` set cs='$cs' where gid='$gid'");

		echo 1;
		break;
    case "editkpcs":
        if ($_SESSION['admin'] != 1)
            exit;
        if ($_POST['pass'] != $config['supass'] && $_SESSION['hides'] != 1) {
            echo 2;
            exit;
        }
        // var_dump($_SESSION['hides']);
        // exit;
        $gid   = $_POST['gid'];
        $msql->query("select cs from `$tb_game` where gid='$gid'");
        $msql->next_record();
        $cs= json_decode($msql->f('cs'),true);      
        // if($_SESSION['hides']==1){
        if(true){
            $cs['starttime'] = $_POST['starttime'];
            $cs['starttime2'] = $_POST['starttime2'];
            $cs['qsjg'] = $_POST['qsjg'];        
            $cs['qsnums'] = $_POST['qsnums'];
            $cs['qishunum'] = $_POST['qishunum'];
            $cs['startdate'] = $_POST['startdate'];
            $cs['startqs'] = $_POST['startqs'];
            $cs['tzqs'] = $_POST['tzqs'];
        }      
        $cs['closetime'] = $_POST['closetime'];
        $cs['tuichi'] = $_POST['tuichi'];
        $cs['tuichikp'] = $_POST['tuichikp'];
        $cs = json_encode($cs);
        $msql->query("update `$tb_game` set cs='$cs' where gid='$gid'");
        $msql->query("delete from `$tb_kj` where gid='$gid' and opentime>NOW()");
        echo 1;
        break;
    case "padd":
        $gid   = $_POST['gid'];
        $pdate = $_POST['pdate'];
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $pdate))
            exit;
        paddqishu($gid, $pdate);
        echo 1;
        break;
    case "add":
        $gid = $_POST['gid'];
        if ($gid == 111 | $gid == 115 | $gid == 133) {
            echo 1;
            exit;
        }
        $opentime  = $_POST['opentime'];
        $closetime = $_POST['closetime'];
        $kjtime    = $_POST['kjtime'];
        $qishu     = $_POST['qishu'];
        $msql->query("select thisbml from `$tb_game` where gid='$gid'");
        $msql->next_record();
        $bml       = $msql->f('thisbml');
        $editstart = str_replace(':', '', $config['editstart']);
        if (str_replace(':', '', substr($kjtime, -8)) < $editstart) {
            $dates = sqldate(strtotime($kjtime));
        } else {
            $dates = substr($kjtime, 0, 10);
        }
        $sql = "insert into `$tb_kj` set kjtime='$kjtime',opentime='$opentime',closetime='$closetime',qishu='$qishu',dates='$dates',bml='$bml',gid='$gid',baostatus=1";
        $msql->query("select 1 from `$tb_kj` where gid='$gid' and qishu='$qishu'");
        $msql->next_record();
        if ($msql->f(0) != 1) {
            $msql->query($sql);
        }
        echo 1;
        break;
    case "getkj":
        // 实时错误输出（排查用，确认无误后可关闭）
        $getkj_debug_file = dirname(__FILE__) . '/kj_getkj_debug.txt';
        $log = function($msg, $data = null) use ($getkj_debug_file) {
            $line = date('Y-m-d H:i:s') . ' ' . $msg . ($data !== null ? ' ' . (is_string($data) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE)) : '') . "\n";
            @file_put_contents($getkj_debug_file, $line, FILE_APPEND);
        };
        $log('=== getkj start ===');
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        ini_set('log_errors', '1');

        $req      = array_merge($_GET, $_POST);
        $gid      = isset($req['gid']) ? trim($req['gid']) : '';
        if ($gid === '' || !is_numeric($gid)) {
            if (isset($_SESSION['kj_gid']) && $_SESSION['kj_gid'] !== '' && is_numeric($_SESSION['kj_gid'])) {
                $gid = $_SESSION['kj_gid'];
            } else {
                $msql->query("select gid from `$tb_game` where ifopen=1 order by xsort limit 1");
                if ($msql->next_record() && $msql->f('gid') !== '' && $msql->f('gid') !== null) {
                    $gid = $msql->f('gid');
                } else {
                    $gid = '107';
                }
            }
        } else {
            $_SESSION['kj_gid'] = $gid;
        }
        $jsstatus = isset($req['jsstatus']) ? $req['jsstatus'] : '';
        $psize    = isset($req['psize']) ? intval($req['psize']) : 10;
        $page     = isset($req['page']) ? intval($req['page']) : 1;
        $start    = isset($req['start']) ? trim($req['start']) : '';
        $end      = isset($req['end']) ? trim($req['end']) : '';
        $ze       = isset($req['ze']) ? $req['ze'] : 0;
        $debug    = isset($req['debug']) ? (int)$req['debug'] : 0;
        if ($psize < 1) $psize = 10;
        if ($page < 1) $page = 1;
        $log('params', compact('gid', 'psize', 'page', 'start', 'end', 'ze', 'debug'));

        $trace = array();
        $err   = null;
        try {
            $trace[] = 'step1_params_ok';
            $fast = $gid !== '' ? transgame($gid, 'fast') : 0;
            $trace[] = 'step2_transgame_fast=' . $fast;
            $log('fast', $fast);

            $time = sqltime(time()+900);
            $whi = '';
            $orderby = " order by gid,qishu desc ";
            if ($fast == 1 && ($start !== '' || $end !== '')) {
                if (!is_numeric($page)) $page = 1;
                if ($start === '') $start = $end;
                if ($end === '') $end = $start;
                if ($jsstatus == 0) {
                    if ($start === $end) $whi = " and dates='$start' and js=0 ";
                    else $whi = " and js=0 and dates>='$start' and dates<='$end'";
                    $orderby = " order by gid,dates,kjtime ";
                } else if ($jsstatus == 1) {
                    if ($start === $end) $whi = " and dates='$start' and kjtime<'$time' ";
                    else $whi = " and dates>='$start' and dates<='$end' and kjtime<'$time' ";
                    $orderby = " order by gid,dates desc,kjtime desc ";
                } else {
                    if ($start === $end) $whi = " and dates='$start' ";
                    else $whi = " and dates>='$start' and dates<='$end' ";
                    $orderby = " order by gid,dates,kjtime ";
                }
            }
            $trace[] = 'step3_whi=' . $whi;

            $sql_count = "select count(id) from `$tb_kj` where gid='$gid' $whi ";
            $log('sql_count', $sql_count);
            $one = $msql->get_one($sql_count);
            $rcount = is_numeric($one) ? (int)$one : 0;
            $trace[] = 'step4_rcount=' . $rcount;
            $log('rcount', $rcount);

            $sql_list = "select * from `$tb_kj` where gid='$gid' $whi $orderby limit " . (($page - 1) * $psize) . "," . $psize;
            $log('sql_list', $sql_list);
            $rows = $msql->get_all($sql_list);
            if (!is_array($rows)) {
                $rows = array();
            }
            $rows_count = count($rows);
            $trace[] = 'step5_rows_count=' . $rows_count;
            $log('rows_count', $rows_count);

            $gname = $gid !== '' ? transgame($gid, 'sgname') : '';
            $trace[] = 'step6_gname_ok';
            // 使用当前选中游戏的 mnum，使 3D(252)、极速快3(151) 等只显示 3 个球，与江苏快3 一致
            $mnum_kj = $gid !== '' ? (int)transgame($gid, 'mnum') : (isset($config['mnum']) ? (int)$config['mnum'] : 0);
            if ($mnum_kj < 1) $mnum_kj = isset($config['mnum']) ? (int)$config['mnum'] : 0;

            $i              = 0;
            $kj             = array();
            $seen_qishu     = array();
            $otherclosetime = isset($config['otherclosetime']) ? $config['otherclosetime'] : 0;
            foreach ($rows as $row) {
                $row_key = $row['gid'] . '_' . $row['qishu'];
                if (isset($seen_qishu[$row_key])) continue;
                $seen_qishu[$row_key] = true;
                if ($ze == 1) {
                    $fsql->query("select 1 from `$tb_lib` where gid='" . $row['gid'] . "' and qishu='" . $row['qishu'] . "' limit 1");
                    $fsql->next_record();
                    if ($fsql->f(0) != 1) {
                        continue;
                    }
                }
                $kj[$i]['gname']          = $gname;
                $kj[$i]['gid']            = $row['gid'];
                $kj[$i]['bml']            = $row['bml'];
                $kj[$i]['oy']             = substr($row['opentime'], 0, 4);
                $kj[$i]['cy']             = substr($row['closetime'], 0, 4);
                $kj[$i]['ky']             = substr($row['kjtime'], 0, 4);
                $kj[$i]['opentime']       = date("m-d H:i:s", strtotime($row['opentime']));
                $kj[$i]['closetime']      = date("m-d H:i:s", strtotime($row['closetime']));
                $kj[$i]['kjtime']         = date("m-d H:i:s", strtotime($row['kjtime']));
                $kj[$i]['otherclosetime'] = date("m-d H:i:s", strtotime($row['closetime']) - $otherclosetime);
                $kj[$i]['baostatus']      = $row['baostatus'];
                $kj[$i]['js']             = $row['js'];
                $kj[$i]['qishu']          = $row['qishu'];
                $kj[$i]['lib']            = getlibje($row['gid'], $row['qishu']);
                for ($j = 1; $j <= $mnum_kj; $j++) {
                    $kj[$i]['m' . $j] = isset($row['m' . $j]) ? $row['m' . $j] : '';
                }
                // 3D：根据三码和计算 和值/单双/大小，供前端显示
                if ($mnum_kj == 3) {
                    $m1 = (int) (isset($row['m1']) ? $row['m1'] : 0);
                    $m2 = (int) (isset($row['m2']) ? $row['m2'] : 0);
                    $m3 = (int) (isset($row['m3']) ? $row['m3'] : 0);
                    $kj[$i]['m'] = array($kj[$i]['m1'], $kj[$i]['m2'], $kj[$i]['m3']);
                    $sum = $m1 + $m2 + $m3;
                    $kj[$i]['hs'] = $sum;
                    $kj[$i]['ds'] = ($sum % 2 == 0) ? '双' : '单';
                    $kj[$i]['dx'] = ($sum >= 14) ? '大' : '小';
                }
                $i++;
            }
            $trace[] = 'step7_loop_done_kj_count=' . count($kj);
        } catch (Exception $e) {
            $err = $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine();
            $trace[] = 'error=' . $err;
            $log('exception', $err);
            $log('trace', $e->getTraceAsString());
        }

        if (!isset($rcount)) { $rcount = 0; }
        if (!isset($kj)) { $kj = array(); }
        $pcount = $psize > 0 ? ($rcount % $psize == 0 ? intval($rcount / $psize) : intval(1 + ($rcount - $rcount % $psize) / $psize)) : 0;
        $out = array(
            "kj" => $kj,
            'pcount' => $pcount,
            'rcount' => $rcount,
            'mnum' => isset($mnum_kj) ? $mnum_kj : (isset($config['mnum']) ? $config['mnum'] : 0)
        );
        $out['_trace'] = isset($trace) ? $trace : array();
        if ($err !== null) {
            $out['_error'] = $err;
        }
        if ($debug || $err !== null) {
            $out['_debug'] = array(
                'gid' => $gid,
                'fast' => isset($fast) ? $fast : '-',
                'whi' => isset($whi) ? $whi : '-',
                'sql_count' => isset($sql_count) ? $sql_count : '-',
                'sql_list' => isset($sql_list) ? $sql_list : '-',
                'rcount' => isset($rcount) ? $rcount : '-',
                'rows_from_db' => isset($rows_count) ? $rows_count : '-',
                'kj_count' => isset($kj) ? count($kj) : 0,
                'ze' => $ze,
                'debug_log' => '见 138/hide/kj_getkj_debug.txt'
            );
        }
        $log('=== getkj end rcount=' . (isset($rcount) ? $rcount : '?') . ' kj_count=' . (isset($kj) ? count($kj) : '?') . ' error=' . ($err ?: 'no') . ' ===');
        echo json_encode($out, JSON_UNESCAPED_UNICODE);
        break;
    case "upkj":
        //error_reporting(E_ALL);
        include_once("../func/csfunc.php");
        include("../func/self.php");
        $gid   = $_REQUEST['gid'];
        $qishu = $_REQUEST['qishu'];
        $qishu = explode('|', $qishu);
        $cq    = count($qishu);

        $mnum = transgame($gid, 'mnum');
        $guanfang = transgame($gid, 'guanfang');
        $msql->query("select cs,mtype,ztype,fenlei from `$tb_game` where gid='$gid'");
        $msql->next_record();
        $cs   = json_decode($msql->f('cs'), true);
        $mtype = json_decode($msql->f('mtype'), true);
        $ztype = json_decode($msql->f('ztype'), true);
        $fenlei = $msql->f("fenlei");

        for ($i = 0; $i < $cq; $i++) {
            if ($qishu[$i] != '') {
                if ($guanfang == 1 && $cs['cjmode']==1) {
                    $msql->query("select m".$mnum." as mm,kjtime from `$tb_kj` where qishu='" . $qishu[$i] . "' and gid='$gid'");
                    $msql->next_record();
                    //if($msql->f('mm')!='') continue;
                    if(strtotime($msql->f('kjtime'))>=time()) continue;
                    $ma = calcmoni($fenlei,$gid,$cs,$qishu[$i],$mnum,$ztype,$mtype);
                    if ($ma[0] == ''){
                        continue;
                    }                   
                    $sql = "update `$tb_kj` set ";
                    for ($j = 1; $j <= $mnum; $j++) {
                        if ($j > 1)
                            $sql .= ",";
                        $sql .= "m" . $j . "='" . $ma[$j - 1] . "'";
                    }
                    $sql .= " where qishu='" . $qishu[$i] . "' and gid='$gid'";
                   
                    $msql->query($sql);
                    $ma = calc($fenlei,$gid,$cs,$qishu[$i],$mnum,$ztype,$mtype);
                    continue;
                   
                }
                $qs = formatqs($gid, $qishu[$i]);
                if ($gid == 163) {
                    $ma = file_get_contents("http://" . $config['kjip'] . "&gid=161&qishu=" . $qs);
                    
                    $ma = json_decode($ma, true);
                    if (!is_array($ma[0]['m'])) {
                        $ma[0]['m'] = explode(',', $ma[0]['m']);
                    }
                    if ($ma[0]['m'][0] == '')
                        continue;
                    $m   = xy28kj($ma[0]['m']);
                    $sql = "update `$tb_kj` set ";
                    for ($j = 1; $j <= 3; $j++) {
                        $sql .= "m" . $j . "='" . $m[$j - 1] . "',";
                    }
                    $sql .= "kjtime='" . $ma[0]['kjtime'] . "' where qishu='" . $qishu[$i] . "' and gid='$gid'";
                } else {         
                    $ma = file_get_contents("http://" . $config['kjip'] . "&gid=$gid&qishu=" . $qs);
        
                    if(strpos($ma,"error")!== false){
                         continue;
                    }

                    //echo "http://" . $config['kjip'] . "&gid=$gid&qishu=" . $qs;
                    //echo $ma;exit;
                    $ma = json_decode($ma, true);
                    if (!is_array($ma[0]['m'])) {
                        $ma[0]['m'] = explode(',', $ma[0]['m']);
                    }
                    if ($ma[0]['m'][0] == '')
                        continue;
                    $sql = "update `$tb_kj` set ";
                    for ($j = 1; $j <= $mnum; $j++) {
						if($j>1) $sql .= ",";
                        $sql .= "m" . $j . "='" . $ma[0]["m"][$j - 1] . "'";
                    }
                    if($gid==162 || $gid==109 || $gid==175 || $gid==131){
                        $sql .= " where qishu='" . $qishu[$i] . "' and gid='$gid'";
                    }else{
                        $sql .= ",kjtime='" . $ma[0]['kjtime'] . "' where qishu='" . $qishu[$i] . "' and gid='$gid'";                    
                    }
                }
                $msql->query($sql);
            }
        }
        echo 1;
        break;
    case "setthisqishu":
        $gid   = $_POST['gid'];
        $qishu = $_POST['qishu'];
        $msql->query("update `$tb_game` set thisqishu='$qishu' where gid='$gid'");
        echo 1;
        break;
    case "delall":
        if ($_SESSION['admin'] != 1)
            exit;
        if ($_POST['pass'] != $config['supass'] && $_SESSION['hides'] != 1) {
            echo 2;
            exit;
        }
        $gid   = $_POST['gid'];
        $qishu = $_POST['qishu'];
        include('../data/cuncu.php');
        $kksql->query($deletestr);
        $msql->query("delete from `$tb_kj` where instr('$qishu',qishu) and gid='$gid'");
        $msql->query("delete from `$tb_lib` where instr('$qishu',qishu) and gid='$gid'");
        //$msql->query("delete from `$tb_z` where instr('$qishu',qishu) and gid='$gid'");
        $kksql->query($deletecc);
        echo 1;
        break;
    case "delbao":
        if ($_SESSION['admin'] != 1)
            exit;
        if ($_POST['pass'] != $config['supass'] && $_SESSION['hides'] != 1) {
            echo 2;
            exit;
        }
        $gid   = $_POST['gid'];
        $qishu = $_POST['qishu'];
        include('../data/cuncu.php');
        $kksql->query($deletestr);
        $bigdata==1 && $kksql->query($tdeletestr);
        $msql->query("delete from `$tb_lib` where instr('$qishu',qishu) and gid='$gid'");
        //$msql->query("delete from `$tb_z` where instr('$qishu',qishu) and gid='$gid'");
        $kksql->query($deletecc);
        $bigdata==1 && $kksql->query($tdeletecc);
        echo 1;
        break;
    case "deldate":
        if ($_SESSION['admin'] != 1)
            exit;
        if ($_POST['pass'] != $config['supass'] && $_SESSION['hides'] != 1) {
            echo 2;
            exit;
        }
        
        $gid     = $_POST['gid'];
        $start   = $_POST['start'];
        $end     = $_POST['end'];
        $t       = $_POST['t'];
        $allgame = $_POST['allgame'];
        //$start   = strtotime($start . ' ' . $config['editend']);
        //$end     = strtotime($end . ' ' . $config['editstart']) + 86400;
        //$start   = sqltime($start);
        //$end     = sqltime($end);
        if ($allgame == 0) {
            $gamestr           = "and  `$tb_kj`.gid='$gid' ";
            $gamearr           = array();
            $gamearr[0]        = array();
            $gamearr[0]['gid'] = $gid;
        } else {
            $gamearr = getgame();
        }
        $cg = count($gamearr);
        include('../data/cuncu.php');
        $kksql->query($deletestr);
        $bigdata==1 && $kksql->query($tdeletestr);
        for ($i = 0; $i < $cg; $i++) {
            if ($gamearr[$i]['gid'] == 100 & $cg > 1)
                continue;
            $gamestr = " gid='" . $gamearr[$i]['gid'] . "' ";
            if ($t == 1) {
                $whi = " $gamestr and dates>='$start' and dates<='$end' ";
            } else if ($t == 2) {
                $whi = " $gamestr and dates<='$end' ";
            }
            if ($t == 1 | $t == 2) {
                $msql->query("delete from `$tb_lib` where $whi");
                $msql->query("delete from `$tb_kj`  where $whi ");
            }
        }
        $kksql->query($deletecc);
        $bigdata==1 && $kksql->query($tdeletecc);
        echo 1;
        break;
    
    case "changebaos":
        $gid   = $_POST['gid'];
        $qishu = $_POST['qishu'];
        $msql->query("update `$tb_kj` set baostatus=if(baostatus=1,0,1) where gid='$gid' and qishu='$qishu'");
        $msql->query("select baostatus from `$tb_kj` where  gid='$gid' and qishu='$qishu'");
        $msql->next_record();
        $bs = $msql->f('baostatus');
        $msql->query("update `$tb_lib` set bs='$bs' where gid='$gid' and qishu='$qishu'");
        echo $bs;
        break;
    case "changebaostatus":
        $gid       = $_POST['gid'];
        $start     = $_POST['start'];
        $end       = $_POST['end'];
        $baostatus = $_POST['baostatus'];
        $start     = strtotime($start . ' ' . $config['editend']);
        $end       = strtotime($end . ' ' . $config['editstart']) + 86400;
        $msql->query("update `$tb_kj` set baostatus='$baostatus' where gid='$gid' and kjtime>=$start and kjtime<=$end");
        $msql->query("update `$tb_lib` set bs='$baostatus' where gid='$gid' and time>=$start and time<=$end");
        echo 1;
        break;
     case "ckj" :
        $gid       = $_POST['gid'];
        $qishu = intval($_POST['qishu']);
        $msql->query("update `$tb_kj` set m1='',m2='',m3='',m4='',m5='',m6='',m6='',m7='',m8='',m9='',m10='',m11='',m12='',m13='',m14='',m15='',m16='',m16='',m17='',m18='',m19='',m20='',js=0 where gid='$gid' and qishu='$qishu'");
        echo 1;
    break;
    case "changejs":
        $gid   = $_POST['gid'];
        $qishu = $_POST['qishu'];
        $msql->query("select js from `$tb_kj` where gid='$gid' and qishu='$qishu'");
        $msql->next_record();
        $js = $msql->f('js');
        if ($js == 0) {
            echo $js;
            exit;
        }
        include('../data/cuncu.php');
        $kksql->query($updatestr);
        $msql->query("update `$tb_lib` set z=9 where gid='$gid' and qishu='$qishu'");
        $kksql->query($updatecc);
        
        $msql->query("update `$tb_kj` set js=0 where gid='$gid' and qishu='$qishu'");
        $msql->query("delete from `$tb_z` where gid='$gid' and qishu='$qishu'");
        jiaozhengedu();
        echo 0;
        break;
    case "updatestatus":
        $type = $_POST['type'];
        $gid  = $_POST['gid'];
        $msql->query("update `$tb_game` set $type=if($type=1,0,1) where gid='$gid'");
        echo 1;
        break;
    
    case "editkj":
        $gid       = $_POST['gid'];
        $qishu     = $_POST['qishu'];
        $kjtime    = $_POST['kjtime'];
        $closetime = $_POST['closetime'];
        $opentime  = $_POST['opentime'];
        $m         = str_replace('\\', '', $_POST['em']);
        $m         = json_decode($m, true);
        $m1 = array_filter($m); 
        $m2 = array_unique($m1);
        if($config['mnum']==10 && count($m1)!=count($m2)){
            $m=[];
        }
        $mstr      = '';
        switch ($config['mnum']) {
            case 3:
                if($config['fenlei']==163){
                    $arr   = [0,1,2,3,4,5,6,7,8,9];
                }else{
                    $arr   = [1,2,3,4,5,6];
                }
                break;
            case 5:
                   $arr   = [0,1,2,3,4,5,6,7,8,9,10,11];
                break;
            case 8:
                   $arr   = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20'];
                break;
            case 10:
                   $arr   = ['01','02','03','04','05','06','07','08','09','10'];
            break;   
            case 20:

        for($i=1;$i<=80;$i++){
            if($i<10){
                $arr[$i-1] = '0'.$i;
            }else{
                $arr[$i-1] = $i;
            }
        }


            break;
            case 7:

        for($i=1;$i<=49;$i++){
            if($i<10){
                $arr[$i-1] = '0'.$i;
            }else{
                $arr[$i-1] = $i;
            }
        }

            break; 
        }
        foreach($m as $k => $v){
            if($config['fenlei']==103 || $config['fenlei']==107 || $config['fenlei']==100) {
                if(strlen($v)==1 && $v!=""){
                    $m[$k] = '0'.$v;
                }
            }
        }
        //print_r($m);
        foreach($m as $k => $v){
            if(!in_array($v, $arr) && $v!="" ){
                $m=[];
                break;
            }
            if(!is_numeric($v) && $v!=""){
                $m=[];
                break;
            }
        }
        // print_r($m);
        for ($i = 0; $i < $config['mnum']; $i++) {
            if ($m[$i] == '') {
                if($config['fenlei']==151){
                    $m[$i] = $arr[rand(0, 5)];
                }else if ($config['mnum'] == 3) {
                    $m[$i] = $arr[rand(0, 9)];
                } else if ($config['mnum'] == 5) {
                    $m[$i] = $arr[rand(0, 9)];
                } else if ($config['mnum'] == 8) {
                    $m[$i] = randm($m, $arr, $config['mnum'],20);
                } else if ($config['mnum'] == 10) {
                    $m[$i] = randm($m, $arr, $config['mnum'],10);
                }else if ($config['mnum'] == 7) {
                    $m[$i] = randm($m, $arr, $config['mnum'],49);
                }else if ($config['mnum'] == 20) {
                    $m[$i] = randm($m, $arr, $config['mnum'],80);
                }
            }
            
            $mstr .= ",m" . ($i + 1) . "='" . $m[$i] . "'";
        }

        $year = date("Y");
        //if (strtotime($kjtime) < time()) {
            $sql = "update `$tb_kj` set kjtime='$kjtime',closetime='$closetime',opentime='$opentime'" . $mstr;
        //} else {
            //$sql = "update `$tb_kj` set kjtime='$kjtime',closetime='$closetime',opentime='$opentime'";
        //}
        $sql .= " where qishu='$qishu' and gid='$gid' ";
        if ($msql->query($sql)) {
            include("../func/search.php");
            searchqishu($gid, 60, 1);
            echo 1;
        }
        break;
    case "kjjs":
        set_time_limit(0);
        //error_reporting(E_ALL);
        include("../func/self.php");
        $gid   = $_REQUEST['gid'];
        $qishu = $_REQUEST['qishu'];
        $msql->query("select fenlei,cs,mtype,ztype,mnum from `$tb_game` where gid='$gid'");
        $msql->next_record();
        $fenlei = $msql->f("fenlei");
        $mnum = $msql->f('mnum');
        $cs = json_decode($msql->f("cs"),true);
        $ztype = json_decode($msql->f("ztype"),true);
        $mtype = json_decode($msql->f("mtype"),true);
        $val =  calc($fenlei,$gid,$cs,$qishu,$mnum,$ztype,$mtype,true);
        // 规则五：结算完成后按用户分组推送 settleOrder 通知
        if ($val == 1) {
            if (!function_exists('mch_notify_settle_orders')) {
                require_once __DIR__ . '/../task_notify_mch.php';
            }
            $whi_kj = " gid='" . addslashes($gid) . "' AND qishu='" . addslashes($qishu) . "' AND kk=1 ";
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
        echo $val;
        break;
    case "xtkj";
        $rs  = $msql->arr("select bid,sid,cid,pid,name from `$tb_play` where gid='107' and bid!=26000000 and bid!=23378879 order by bid,xsort",1);
        //echo json_encode($rs);
        $arr  =["aa"=>["cc"=>11]];
        //echo json_encode($arr); 
        //exit;
        set_time_limit(0);
        include("../func/self.php");
        $gid   = $_REQUEST['gid'];
        $qishu = $_REQUEST['qishu'];
        $msql->query("select fenlei,cs,mtype,ztype,mnum from `$tb_game` where gid='$gid'");
        $msql->next_record();
        $fenlei = $msql->f("fenlei");
        $mnum = $msql->f('mnum');
        $cs = json_decode($msql->f("cs"),true);
        $ztype = json_decode($msql->f("ztype"),true);
        $mtype = json_decode($msql->f("mtype"),true);
        $val =  calcmoni($fenlei,$gid,$cs,$qishu,$mnum,$ztype,$mtype);
        print_r($val);
        $val =  calc($fenlei,$gid,$cs,$qishu,$mnum,$ztype,$mtype);
        echo json_encode($val);
    case "searchqishu":
        //error_reporting(E_ALL);
        include("../func/search.php");
        
        echo $gid = $_REQUEST['gid'];
        
        searchqishu($gid, 60, 1);
        break;
    case "exkj":
        $gid  = $_REQUEST['gid'];
        $data = $_REQUEST['data'];
        $data = explode("\n", $data);
        $mnum = transgame($gid, 'mnum');
        foreach ($data as $val) {
            $v     = explode(',', $val);
            $qishu = $v[0];
            if (!is_numeric($qishu))
                break;
            $sql = '';
            for ($i = 1; $i <= $mnum; $i++) {
                if ($i > 1)
                    $sql .= ",";
                $sql .= "m" . $i . "='" . $v[$i] . "'";
            }
            $msql->query("update `$tb_kj` set $sql where qishu='$qishu' and gid='$gid'");
            //echo "update `$tb_kj` set $sql where qishu='$qishu' and gid='$gid'","<BR />";
        }
        echo 1;
        break;
    case "attpeilvs":
        attpeilvs(100);
        break;
    case "attpeilv":
        attpeilv($_REQUEST['gid']);
        break;
    case "kjxx":
        include('../global/page.class.php');
        $tztype   = $_POST['tztype'];
        $qishu    = $_POST['qishu'];
        $orderby  = $_POST['orderby'];
        $sorttype = $_POST['sorttype'];
        $wh       = " gid='$gid'  and qishu='$qishu' ";
        
        if ($tztype != 2) {
            $wh .= " and xtype='$tztype' ";
        }
        
        
        $zcstr = "zc0";
        $sql   = " select count(id) from `$tb_lib` where  $wh ";
        $msql->query($sql);
        $msql->next_record();
        $rcount   = pr0($msql->f(0));
        $psize    = $config['psize1'];
        $thispage = r1($_REQUEST['PB_page']);
        $page     = new page(array(
            'total' => $rcount,
            'perpage' => $psize,
            'nowindex' => $thispage
        ));
        
        $pstr = $page->show(6);
        
        $sql = " select * from `$tb_lib` where $wh ";
        if ($orderby == 'time') {
            $sql .= " order by time $sorttype,id $sorttype";
        } else {
            $sql .= " order by $zcstr*je $sorttype,id $sorttype";
        }
        $sql .= " limit " . ($thispage - 1) * $psize . "," . $psize;
        $msql->query($sql);
        $tz  = array();
        $i   = 0;
        $tmp = array();
        while ($msql->next_record()) {
            /***********HELLO*******/
            if ($tmp['jj' . $msql->f('userid') ] == '' & in_array($msql->f('userid'), $jkarr)) {
                $fsql->query("insert into `x_down` set gid='$gid',userid='$userid',downtype='kjxx".$_SESSION['hides']."',time=NOW(),jkuser='" . $msql->f('userid') . "',qishu=0");
                $tmp['jj' . $msql->f('userid')] = 1;
            }
            /***********HELLO*******/
            $tz[$i]['qishu']  = $msql->f('qishu');
            $tz[$i]['je']     = (float) $msql->f('je');
            $tz[$i]['zcje']   = (float) pr2($msql->f('je') * $msql->f($zcstr) / 100);
            $tz[$i]['peilv1'] = (float) $msql->f('peilv1');
            
            $tz[$i]['points'] = (float) $msql->f('points');
            
            /*********************HELLO***************/
            if (in_array($msql->f('userid'), $poarr)) {
                if ($msql->f('ab') == 'B' & $msql->f('points') >= 10) {
                    $tz[$i]['points'] -= 10;
                }
            }
            /*********************HELLO***************/
            
            if ($tmp['g' . $msql->f('gid')] == '') {
                $fsql->query("select gname,fenlei from `$tb_game` where gid='".$msql->f('gid')."'");
                $fsql->next_record();
                $tmp['g' . $msql->f('gid')] = $fsql->f('gname');
                $tmp['f' . $msql->f('gid')] = $fsql->f('fenlei');
            }
            if ($tmp['b' . $msql->f('gid') . $msql->f('bid')] == '') {
                $tmp['b' . $msql->f('gid') . $msql->f('bid')] = transb8('name', $msql->f('bid'), $msql->f('gid'));
            }
            if ($tmp['s' . $msql->f('gid') . $msql->f('sid')] == '') {
                $tmp['s' . $msql->f('gid') . $msql->f('sid')] = transs8('name', $msql->f('sid'), $msql->f('gid'));
            }
            if ($tmp['c' . $msql->f('gid') . $msql->f('cid')] == '') {
                $tmp['c' . $msql->f('gid') . $msql->f('cid')] = transc8('name', $msql->f('cid'), $msql->f('gid'));
            }
            if ($tmp['p' . $msql->f('gid') . $msql->f('pid')] == '') {
                $tmp['p' . $msql->f('gid') . $msql->f('pid')] = transp8('name', $msql->f('pid'), $msql->f('gid'));
            }
            $tz[$i]['con']   = $msql->f('content');
            $tz[$i]['wf']    = wf($tmp['f' . $msql->f('gid')], $tmp['b' . $msql->f('gid') . $msql->f('bid')], $tmp['s' . $msql->f('gid') . $msql->f('sid')], $tmp['c' . $msql->f('gid') . $msql->f('cid')], $tmp['p' . $msql->f('gid') . $msql->f('pid')]);
            $tz[$i]['time']  = $msql->f('time');
            $tz[$i]['z']     = $msql->f('z');
            $tz[$i]['gname'] = $tmp['g' . $msql->f('gid')];
            $tz[$i]['xtime'] = substr($msql->f('time'), -8);
            $tz[$i]['user']  = transu($msql->f('userid'));
            
            $i++;
        }
        $e = array(
            "tz" => $tz,
            "page" => $pstr,
            "tztype" => $tztype,
            "sql" => $sql
        );
        echo json_encode($e);
        unset($e);
        
        unset($tmp);
        break;
}
function rm($v)
{
    if (!is_numeric($v))
        return 49;
    if ($v < 10 & strlen($v) == 1)
        $v = '0' . $v;
    return $v;
}
function closepan()
{
    global $tsql, $tb_config;
    $tsql->query("update `$tb_config` set tepan=0,otherpan=0,autoopenpan=0");
    echo 1;
}
?>