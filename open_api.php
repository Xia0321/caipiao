<?php
/**
 * 开放接口（仅此文件，不修改项目其他文件）
 * - 快速注册：写入 x_user，is_api=1
 * - 快速登录：校验账号密码后返回带 token 的登录链接（不写缓存），接口方用该链接即可免登
 * - 入口：支持 token 或 (userid,ts,sign)，校验通过后写 session 并按设备跳转 uxj/mxj
 * 兼容 PHP 5.6
 */
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');
define('OPEN_API_SECRET', 'open_api_sign_key_please_change'); // 验签密钥，请自行修改
define('OPEN_API_TOKEN_TTL', 300); // token 有效秒数，默认 5 分钟
$slat = "zj989";

// ===== 调试日志（排查问题时启用，上线后可删除此段） =====
@mkdir(__DIR__ . '/logs', 0755, true);
define('API_LOG_FILE', __DIR__ . '/logs/open_api_debug.log');
function api_log($tag, $ctx = array()) {
    $line = '[' . date('Y-m-d H:i:s') . '] [' . $tag . ']';
    if (!empty($ctx)) {
        $line .= ' ' . json_encode($ctx, JSON_UNESCAPED_UNICODE);
    }
    @file_put_contents(API_LOG_FILE, $line . "\n", FILE_APPEND | LOCK_EX);
}
// 捕获 Fatal Error（PHP 5.6 兼容）
register_shutdown_function(function () {
    global $msql;
    $e = error_get_last();
    if ($e && in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        api_log('FATAL_ERROR', $e);
    }
    // 记录最后执行的 SQL 及 MySQL 错误（die() 退出时也能捕获）
    if (isset($msql) && is_object($msql)) {
        $last_sql = (is_array($msql->sql) && !empty($msql->sql)) ? end($msql->sql) : 'none';
        $mysql_error = '';
        $mysql_errno = 0;
        try {
            $ref = new ReflectionObject($msql);
            $prop = $ref->getProperty('mysqli');
            $prop->setAccessible(true);
            $mysqli_obj = $prop->getValue($msql);
            if ($mysqli_obj instanceof mysqli) {
                $mysql_error = $mysqli_obj->error;
                $mysql_errno = $mysqli_obj->errno;
            }
        } catch (Exception $ex) {}
        api_log('SHUTDOWN_SQL_TRACE', array('last_sql' => substr($last_sql, 0, 300), 'mysql_errno' => $mysql_errno, 'mysql_error' => $mysql_error));
    }
    $buf = @ob_get_contents();
    if ($buf !== false && $buf !== '') {
        api_log('UNEXPECTED_OUTPUT_ON_SHUTDOWN', array('len' => strlen($buf), 'preview' => substr($buf, 0, 300)));
    }
});
// 用输出缓冲兜住所有 include 阶段可能产生的杂散输出
ob_start();
$_log_ip     = isset($_SERVER['REMOTE_ADDR'])     ? $_SERVER['REMOTE_ADDR']     : '';
$_log_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
$_log_action = isset($_REQUEST['action'])        ? $_REQUEST['action']        : '';
$_log_post = $_POST;
if (isset($_log_post['password'])) $_log_post['password'] = '***';
api_log('request_start', array('ip' => $_log_ip, 'method' => $_log_method, 'action' => $_log_action, 'post' => $_log_post));
// ===== 调试日志 END =====

include __DIR__ . '/data/config.inc.php';
api_log('after_config');
include __DIR__ . '/data/db.php';
$_log_tb = isset($tb_user) ? $tb_user : 'NOT_SET';
api_log('after_db_php', array('tb_user' => $_log_tb));
include __DIR__ . '/global/db.inc.php';
$_log_msql = isset($msql) ? 'ok' : 'NOT_SET';
api_log('after_db_inc', array('msql' => $_log_msql));
include __DIR__ . '/global/session.class.php';
api_log('after_session');
include __DIR__ . '/data/pan.inc.php';
$_log_upass = isset($config['upass']) ? substr($config['upass'], 0, 3) . '***' : 'NOT_SET';
api_log('after_pan', array('upass_prefix' => $_log_upass));
include __DIR__ . '/func/func.php';
include __DIR__ . '/func/userfunc.php';
require_once __DIR__ . '/task_notify_mch.php';
api_log('after_all_includes');
// data/config.inc.php 会把 error_reporting 重置为 0，这里恢复以确保错误被捕获
error_reporting(E_ALL);

$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
// Bug10 fix: OPTIONS 预检请求需先返回 CORS 头再退出，否则跨域请求全部失败
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    http_response_code(204);
    exit;
}
if ($action !== 'entry') {
    header('Content-Type: application/json; charset=utf-8');
}

switch ($action) {
    case 'quick_register':
        quick_register();
        break;
    case 'quick_login':
        quick_login();
        break;
    case 'entry':
        entry();
        break;
    default:
        out_json(array('code' => -1, 'msg' => 'action invalid'));
}

function out_json($arr)
{
    $buf = ob_get_clean();
    if ($buf !== false && $buf !== '') {
        $log_code = isset($arr['code']) ? $arr['code'] : -99;
        api_log('UNEXPECTED_EARLY_OUTPUT', array('len' => strlen($buf), 'preview' => substr($buf, 0, 300), 'code' => $log_code));
    }
    api_log('response', array('code' => isset($arr['code']) ? $arr['code'] : null, 'msg' => isset($arr['msg']) ? $arr['msg'] : null));
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    exit;
}

function is_mobile_device()
{
    if (!empty($_SERVER['HTTP_X_WAP_PROFILE'])) return true;
    if (!empty($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap')) return true;
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $kw = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'lg', 'sharp', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'nexusone', 'midp', 'wap', 'mobile');
        if (preg_match('/(' . implode('|', $kw) . ')/i', strtolower($_SERVER['HTTP_USER_AGENT']))) return true;
    }
    return false;
}

/**
 * 生成带 token 的免登链接（不写缓存，仅组合 URL）
 * @param string $userid 用户ID
 * @param int $expiry 过期时间戳
 * @param string $device 'm'=移动端 uxj, 'p'=PC mxj
 * @return string 完整 URL
 */
function build_entry_url_with_token($userid, $expiry, $device, $gid = '')
{
    $sign = md5(OPEN_API_SECRET . $userid . $expiry . $device . $gid);
    $payload = $userid . '|' . $expiry . '|' . $device . '|' . $gid . '|' . $sign;
    $token = strtr(base64_encode($payload), '+/', '-_');
    $token = rtrim($token, '=');
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $base = $scheme . '://' . $host . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    $path = $base . '/open_api.php';
    return $path . '?action=entry&token=' . rawurlencode($token);
}

/**
 * 解析并校验 token，成功返回 array('userid'=>, 'device'=>)，失败返回 null
 * PHP 5.6 兼容
 */
function parse_entry_token($token)
{
    if (!is_string($token) || $token === '') {
        return null;
    }
    $token = strtr($token, '-_', '+/');
    $pad = strlen($token) % 4;
    if ($pad) {
        $token .= str_repeat('=', 4 - $pad);
    }
    $raw = base64_decode($token, true);
    if ($raw === false) {
        return null;
    }
    $parts = explode('|', $raw);
    // 兼容旧版4段token和新版5段token（含gid）
    if (count($parts) === 4) {
        // 旧版token：userid|expiry|device|sign
        $userid = $parts[0];
        $expiry = $parts[1];
        $device = $parts[2];
        $sign = $parts[3];
        $gid = '';
        if (!is_numeric($expiry) || (int)$expiry < time()) {
            return null;
        }
        $expect = md5(OPEN_API_SECRET . $userid . $expiry . $device);
        if (!hash_equals($expect, $sign)) {
            return null;
        }
    } elseif (count($parts) === 5) {
        // 新版token：userid|expiry|device|gid|sign
        $userid = $parts[0];
        $expiry = $parts[1];
        $device = $parts[2];
        $gid = $parts[3];
        $sign = $parts[4];
        if (!is_numeric($expiry) || (int)$expiry < time()) {
            return null;
        }
        $expect = md5(OPEN_API_SECRET . $userid . $expiry . $device . $gid);
        if (!hash_equals($expect, $sign)) {
            return null;
        }
    } else {
        return null;
    }
    return array('userid' => $userid, 'device' => $device, 'gid' => $gid);
}

function quick_register()
{
    // BugN1 fix: 移除 $slat，改用与全站一致的 $config['upass'] 做密码哈希
    // 原来用 md5(md5(pass)."zj989") 与全站 md5(pass.$config['upass']) 完全不兼容，
    // 导致 API 注册的用户无法通过网页登录，后台改密码后 API 也登不上
    global $msql, $fsql, $tb_user, $tb_fastje, $tb_zpan, $tb_points, $config;
    $username = isset($_POST['username']) ? strtoupper(trim($_POST['username'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    // 这里的 mch_code 作为"商户号"使用：对应后台代理帐号的 username
    $merchant = isset($_POST['mch_code']) ? strtoupper(trim($_POST['mch_code'])) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $tel = isset($_POST['tel']) ? trim($_POST['tel']) : '';
    $qq = isset($_POST['qq']) ? trim($_POST['qq']) : '';
    $sign = isset($_POST['sign']) ? trim($_POST['sign']) : '';

    api_log('qr_start', array('username' => $username, 'merchant' => $merchant, 'pwd_len' => strlen($password), 'sign_len' => strlen($sign)));

    if ($username === '' || $password === '' || $merchant === '') {
        api_log('qr_missing_params');
        out_json(array('code' => 1, 'msg' => '缺少 username / password / mch_code(商户号)'));
    }
    if (!preg_match('/^[a-zA-Z0-9]{1}([a-zA-Z0-9]|[._]){1,24}$/', $username)) {
        api_log('qr_username_format_fail', array('username' => $username));
        out_json(array('code' => 2, 'msg' => '用户名格式不正确'));
    }
    api_log('qr_format_ok');

    $msql->query("SELECT username FROM `$tb_user` WHERE username='" . addslashes($username) . "'");
    $msql->next_record();
    $found_username = $msql->f('username');
    api_log('qr_user_exists_check', array('found' => $found_username, 'match' => ($found_username === $username)));
    if ($found_username === $username) {
        out_json(array('code' => 3, 'msg' => '用户名已存在'));
    }

    // Bug4 fix: 补全 SELECT 字段（表只有 fid1~fid8，无 fid9）
    api_log('qr_merchant_query_start', array('merchant' => $merchant));
    $msql->query("SELECT userid,username,ifagent,layer,is_api,status,mch_code,mch_secret,wid,pan,defaultpan,gid,fid1,fid2,fid3,fid4,fid5,fid6,fid7,fid8 FROM `$tb_user` WHERE username='" . addslashes($merchant) . "' AND ifagent='1' AND is_api='1' AND status='1'");
    api_log('qr_merchant_query_done');
    $msql->next_record();
    api_log('qr_merchant_next_record_done');
    $userid = $msql->f('userid');
    api_log('qr_merchant_query', array('userid' => $userid, 'mch_code_db' => $msql->f('mch_code')));
    if ($userid === '' || $userid === null) {
        // Bug6 fix: 不能把 SQL 语句返回给客户端，会暴露数据库表结构
        out_json(array('code' => 7, 'msg' => '商户不存在或未开启 API（请检查代理账号与 is_api 设置）'));
    }
    $agent_mch_code = $msql->f('mch_code');
    $agent_mch_secret = $msql->f('mch_secret');
    if ($agent_mch_code === '' || $agent_mch_code === null || $agent_mch_secret === '' || $agent_mch_secret === null) {
        api_log('qr_no_mch_secret', array('mch_code' => $agent_mch_code));
        out_json(array('code' => 8, 'msg' => '商户未配置 mch_code / mch_secret'));
    }
    if ($sign !== '') {
        $payload = $_POST;
        unset($payload['sign']);
        if (isset($payload['mch_code'])) {
            $payload['mch_code'] = strtoupper(trim($payload['mch_code']));
        }
        ksort($payload);
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $expect = md5($agent_mch_secret . $json);
        api_log('qr_sign_check', array('payload_json' => $json, 'expect_prefix' => substr($expect, 0, 6), 'got_prefix' => substr($sign, 0, 6), 'match' => hash_equals($expect, $sign)));
        // Bug5 fix: 不能把正确签名 $expect 返回给客户端，攻击者可直接拿到签名绕过验签
        if (!hash_equals($expect, $sign)) {
            out_json(array('code' => 9, 'msg' => '商户验签失败'));
        }
    }
    api_log('qr_sign_passed');
    $layer = $msql->f('layer') + 1;
    $fid = $userid;
    $defaultpan = $msql->f('defaultpan');
    $pan = $msql->f('pan');
    $gid = $msql->f('gid');
    // Bug2 fix: $wid 从代理记录中取，之前未定义导致 INSERT 写入空值
    $wid = $msql->f('wid');
    $uid = setupid($tb_user, 'userid');
    api_log('qr_uid_generated', array('uid' => $uid));
    // 登录前端会先md5(明文)再提交，后端再md5(md5值+盐)，所以注册也要用双层md5
    $userpass = md5(md5($password) . $config['upass']);
    $name = $name ? addslashes($name) : $username;
    $tel = addslashes($tel);
    $qq = addslashes($qq);
    $un = addslashes($username);
    $mc = addslashes($agent_mch_code);

    $sql = "INSERT INTO `$tb_user` SET username='$un', fid='$fid', userid='$uid', userpass='$userpass', name='$name', tname='$name', sex='', birthday='', shengshi='', street='', shr='', bz='', qq='$qq', tel='$tel', lastloginip='', status='1', layer='$layer', maxren='0', plc='0', pan='$pan', defaultpan='$defaultpan', maxmoney='0', kmaxmoney='0', money='0', kmoney='0', fudong='0', ftime=NOW(), wid='$wid', fastje=0, gid='$gid', passtime=NOW(), regtime=NOW(), liushui=0, garr='', kf='', moneypass='', is_api=1, mch_code='$mc'";

    // Bug3 fix: 原代码 for($i=1; $j>=1; $j--) 用了未定义的 $j，循环从不执行，上级代理链路全丢失
    // 表只有 fid1~fid8
    for ($i = 1; $i <= 8; $i++) {
        $f = $msql->f('fid' . $i);
        if (empty($f)) break;
        $sql .= ", fid{$i}='" . addslashes($f) . "'";
    }
    api_log('qr_before_insert', array('uid' => $uid, 'wid' => $wid, 'fid' => $fid, 'sql_head' => substr($sql, 0, 80)));
    if (!$msql->query($sql)) {
        api_log('qr_insert_failed');
        out_json(array('code' => 6, 'msg' => '注册写入失败'));
    }
    api_log('qr_main_insert_ok');
    $msql->query("INSERT INTO `$tb_fastje` SELECT NULL,$uid,je,xsort FROM `$tb_fastje` WHERE userid='99999999'");
    api_log('qr_fastje_ok');
    $msql->query("INSERT INTO `$tb_zpan` SELECT NULL,gid,$uid,class,lowpeilv,0 FROM `$tb_zpan` WHERE userid='$fid'");
    api_log('qr_zpan_ok');
    $msql->query("INSERT INTO `$tb_points` SELECT NULL,gid,$uid,class,ab,a,b,c,d,cmaxje,maxje,minje FROM `$tb_points` WHERE userid='$fid'");
    api_log('qr_points_ok');
    $gamecs = getgamecs($fid);
    api_log('qr_gamecs', array('count' => count($gamecs)));
    $cg = count($gamecs);
    for ($i = 0; $i < $cg; $i++) {
        $gamecs[$i]['flyzc'] = 0;
        $gamecs[$i]['zc'] = 0;
        $gamecs[$i]['upzc'] = 0;
    }
    insertgame($gamecs, $uid);
    api_log('qr_insertgame_ok');
    out_json(array('code' => 0, 'msg' => 'ok', 'userid' => $uid, 'username' => $username));
}

function quick_login()
{
    // BugN1 fix: 移除 $slat，与全站使用统一的密码哈希方案 md5($password . $config['upass'])
    global $msql, $config, $tb_user;
    $username = isset($_POST['username']) ? strtoupper(trim($_POST['username'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    if ($username === '' || $password === '') {
        out_json(array('code' => 1, 'msg' => '缺少 username / password'));
    }
    // BugN2 fix: 增加暴力破解防护，与 uxj/login.php 保持一致（错误 5 次锁定账号）
    $msql->query("SELECT errortimes FROM `$tb_user` WHERE username='" . addslashes($username) . "' AND ifagent='0' AND ifson='0'");
    $msql->next_record();
    if ((int)$msql->f('errortimes') >= 5) {
        out_json(array('code' => 4, 'msg' => '密码错误次数过多，请联系代理重置'));
    }
    // 登录前端会先md5(明文)再提交，后端再md5(md5值+盐)
    $pass = md5(md5($password) . $config['upass']);
    $msql->query("SELECT userid,username,userpass,status,wid,layer FROM `$tb_user` WHERE username='" . addslashes($username) . "' AND ifagent='0' AND ifson='0'");
    $msql->next_record();
    if ($msql->f('username') !== $username || $msql->f('userpass') !== $pass) {
        // BugN2 fix: 登录失败时累计错误次数
        $msql->query("UPDATE `$tb_user` SET errortimes=errortimes+1 WHERE username='" . addslashes($username) . "'");
        out_json(array('code' => 2, 'msg' => '账号或密码错误'));
    }
    if ((int)$msql->f('status') === 0) {
        out_json(array('code' => 3, 'msg' => '账号已禁用'));
    }
    // BugN2 fix: 登录成功清零错误次数
    $msql->query("UPDATE `$tb_user` SET errortimes=0 WHERE username='" . addslashes($username) . "'");
    $userid = $msql->f('userid');
    $gid = isset($_POST['gid']) ? trim($_POST['gid']) : '';
    $ttl = defined('OPEN_API_TOKEN_TTL') ? OPEN_API_TOKEN_TTL : 300;
    $expiry = time() + $ttl;
    $mobile = is_mobile_device();
    $device = $mobile ? 'm' : 'p';
    $game_url = build_entry_url_with_token($userid, $expiry, $device, $gid);
    out_json(array(
        'code' => 0,
        'msg' => 'ok',
        'game_url' => $game_url,
        'device' => $mobile ? 'mobile' : 'pc',
        'expire_at' => $expiry
    ));
}

function entry()
{
    global $msql, $fsql, $config, $tb_user, $tb_online;
    $userid = '';
    $use_mobile = null;

    // 确保 session 已启动，并清除上次登录的 session（新免登请求以新用户为准）
    if (!session_id()) {
        session_start();
    }
    if (!empty($_SESSION['uuid'])) {
        $old_userid = $_SESSION['uuid'];
        $fsql->query("DELETE FROM `$tb_online` WHERE xtype=2 AND userid='" . addslashes($old_userid) . "'");
        sessiondelu();
    }

    $token = isset($_GET['token']) ? trim($_GET['token']) : '';
    if ($token !== '') {
        $parsed = parse_entry_token($token);
        if ($parsed === null) {
            header('Location: /');
            exit;
        }
        $userid = $parsed['userid'];
        $entry_gid = isset($parsed['gid']) ? $parsed['gid'] : '';
        // Bug7 fix: token 中记录了生成链接时的设备类型，应优先使用，而不是重新检测 UA
        // 原代码设置了 $use_mobile 但最终跳转时完全不用，重新调用 is_mobile_device()，导致设备信息失效
        $use_mobile = ($parsed['device'] === 'm');
    } else {
        $uid = isset($_GET['userid']) ? trim($_GET['userid']) : '';
        $ts = isset($_GET['ts']) ? trim($_GET['ts']) : '';
        $sign = isset($_GET['sign']) ? trim($_GET['sign']) : '';
        if ($uid === '' || $ts === '' || $sign === '') {
            header('Location: /');
            exit;
        }
        if (!is_numeric($ts) || abs(time() - (int)$ts) > 300) {
            header('Location: /');
            exit;
        }
        $expect = md5(OPEN_API_SECRET . $uid . $ts);
        if (!hash_equals($expect, $sign)) {
            header('Location: /');
            exit;
        }
        $userid = $uid;
        $entry_gid = '';
        // 旧版参数方式没有 device 信息，回退到实时检测 UA
        $use_mobile = is_mobile_device();
    }

    $msql->query("SELECT userid,username,wid,layer,gid FROM `$tb_user` WHERE userid='" . addslashes($userid) . "' AND status=1");
    $msql->next_record();
    if ($msql->f('userid') !== $userid) {
        header('Location: /');
        exit;
    }
    $ip = function_exists('getip') ? getip() : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0');
    $time = time();
    $passcode = (getmicrotime() * 100000000) . $time;
    $wid = $msql->f('wid');
    $layer = $msql->f('layer');
    $ugid = $msql->f('gid');
    $fsql->query("DELETE FROM `$tb_online` WHERE xtype=2 AND userid='$userid'");
    $fsql->query("INSERT INTO `$tb_online` SET page='xy', passcode='$passcode', xtype='2', userid='$userid', logintime=NOW(), savetime=NOW(), ip='$ip', server='2', wid='$wid', layer='$layer', os='entry'");
    // Bug8 fix: session 在函数开头已经 start（第244行），这里的二次检查是死代码，直接写 session 即可
    $_SESSION['upasscode'] = $passcode;
    $_SESSION['uuid'] = $userid;
    $_SESSION['ucheck'] = md5($config['allpass'] . $userid);
    $_SESSION['ip'] = $ip;
    // 如果外部传入了gid，优先使用；否则用用户自身的gid
    // 支持：数字gid（先精确匹配，再按class匹配）、游戏名称、class代码
    $default_gid = $ugid ? $ugid : (isset($config['gid']) && $config['gid'] > 0 ? $config['gid'] : '172');
    if (!empty($entry_gid)) {
        $safe_gid = addslashes($entry_gid);
        // 统一查询：按gid精确匹配、按gname匹配、按class匹配
        $msql->query("SELECT gid FROM `x_game` WHERE gid='{$safe_gid}' OR gname='{$safe_gid}' OR class='{$safe_gid}' OR class='c{$safe_gid}' LIMIT 1");
        $msql->next_record();
        $found_gid = $msql->f('gid');
        $_SESSION['gid'] = $found_gid ? $found_gid : $default_gid;
    } else {
        $_SESSION['gid'] = $default_gid;
    }
    $_SESSION['wid'] = $wid;
    $_SESSION['sv'] = '2';
    session_write_close();
    // 登录时主动向商户拉取一次最新余额并同步
    mch_get_balance_from_api($userid);
    // 来访设备判断：以当前打开链接的浏览器 UA 为准，手机访问跳 mxj，电脑访问跳 uxj。
    // 不再使用 token 中的 device（接口登录时多为服务端请求，UA 非真实访客设备），避免手机来访被错误跳转到电脑端。
    $use_mobile = is_mobile_device();
    $device = $use_mobile ? "m" : "u";
    exit(header("Location: /{$device}xj"));
}
