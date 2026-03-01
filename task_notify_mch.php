<?php
/**
 * 任务：检索所有 is_api=1 的用户，按 mch_code 从 x_mchs 取回调链接，将该用户游戏数据实时通知到该链接。
 * 扩展：基于 x_mchs 表 callback_url + 方法名请求商户接口：
 *   - getBalance：实时获取 is_api=1 用户余额
 *   - changeBalance：下注扣款通知（带本次下注详情 orders：tid,gid,qishu,pid,content,je,gname）
 *   - settleOrder：注单结算通知（带有效投注 valid_je、输赢 win_loss、注单详情）
 *   - cancelOrder：取消注单通知（带注单详情，便于回调方加回余额）
 * 仅此文件，不修改项目其他文件。由计划任务或外部定时调用。
 */
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

// 检查是否已经被 include，如果是则只定义函数，不引入可能有副作用的文件
$is_included = (isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) !== realpath($_SERVER['SCRIPT_FILENAME']));

// 使用 require_once 防止重复引入导致页面空白（PHP 内置机制会自动判断是否已引入）
require_once __DIR__ . '/data/config.inc.php';
require_once __DIR__ . '/data/db.php';
require_once __DIR__ . '/global/db.inc.php';

// pan.inc.php 中有 header() 和 exit() 逻辑，在被 include 时可能造成页面空白
// 只在直接访问时才引入，被 include 时跳过（函数内部会使用已存在的全局变量）
if (!$is_included) {
    require_once __DIR__ . '/data/pan.inc.php';
}

require_once __DIR__ . '/func/func.php';

// 被 include 时不覆盖调用方已设置的表名（如 kj.php 中 $tb_lib 可能为 x_lib_total）
if (!isset($tb_user)) $tb_user = 'x_user';
if (!isset($tb_mchs)) $tb_mchs = 'x_mchs';
if (!isset($tb_lib))  $tb_lib  = 'x_lib';

/** 根据商户回调根地址 + 方法名拼出请求 URL，如 https://www.baidu.com -> https://www.baidu.com/getBalance */
function task_mch_method_url($callback_url, $method) {
    $callback_url = trim($callback_url);
    return rtrim($callback_url, '/') . '/' . $method;
}

/** 向商户地址发起 POST JSON 请求，可选 sign（sign = md5(secret + json)，json 为除 sign 外全部字段的 JSON，再追加 sign 后发送） */
function task_mch_http_post($url, $payload, $mch_secret = '') {
    if ($mch_secret !== '' && $mch_secret !== null) {
        $json_for_sign = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $payload['sign'] = md5($mch_secret . $json_for_sign);
    }
    $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    $res = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    return array('body' => $res, 'err' => $err);
}

/**
 * 向上遍历用户层级，找到最顶级的 API 代理，并获取其商户回调信息。
 * 逐级通过 fid（直接上级）轮询直到最顶层，再从中找出开启了 API 模式的代理。
 * @param string $userid 会员 userid
 * @return array|null 含 agent_userid, agent_username, mch_code, callback_url, mch_secret，无则 null
 */
function mch_get_top_api_agent($userid) {
    global $msql, $tb_user, $tb_mchs;
    $userid = trim($userid);
    if ($userid === '') return null;

    // 通过 fid（直接上级）逐级向上轮询，收集完整祖先链（从近到远）
    $ancestors = array();
    $current   = $userid;
    $visited   = array($userid => true);
    for ($i = 0; $i < 10; $i++) {
        $msql->query("SELECT fid, layer FROM `$tb_user` WHERE userid='" . addslashes($current) . "'");
        $msql->next_record();
        $layer = (int)$msql->f('layer');
        $fid   = $msql->f('fid');
        if ($layer <= 1 || $fid === '' || $fid === null || $fid === '0') break;
        if (isset($visited[$fid])) break; // 防止循环
        $visited[$fid]  = true;
        $ancestors[]    = $fid;
        $current        = $fid;
    }

    // 反转：最顶级排在最前，优先匹配
    $chain = array_reverse($ancestors);

    foreach ($chain as $anc_id) {
        $safe = addslashes($anc_id);
        $msql->query("SELECT userid, username, ifagent, is_api, mch_code, status FROM `$tb_user` WHERE userid='$safe' AND ifagent='1' AND is_api='1' AND status='1'");
        $msql->next_record();
        $anc_userid = $msql->f('userid');
        if ($anc_userid === '' || $anc_userid === null) continue;
        $mch_code = $msql->f('mch_code');
        if ($mch_code === '' || $mch_code === null) continue;
        $anc_username = $msql->f('username');
        // 查商户回调配置
        $msql->query("SELECT callback_url, mch_secret FROM `$tb_mchs` WHERE mch_code='" . addslashes($mch_code) . "' AND status=1");
        $msql->next_record();
        $cb  = $msql->f('callback_url');
        $sec = $msql->f('mch_secret');
        $callback_url = ($cb !== null && $cb !== '') ? trim($cb) : '';
        $mch_secret   = ($sec !== null) ? $sec : '';
        if ($callback_url === '') continue;
        return array(
            'agent_userid'   => $anc_userid,
            'agent_username' => $anc_username,
            'mch_code'       => $mch_code,
            'callback_url'   => $callback_url,
            'mch_secret'     => $mch_secret,
        );
    }

    // 兼容旧逻辑：用户自身直接打标 is_api=1（API 注册时写入）
    $msql->query("SELECT userid, username, mch_code FROM `$tb_user` WHERE userid='" . addslashes($userid) . "' AND is_api='1' AND status='1' AND mch_code!=''");
    $msql->next_record();
    $self_id  = $msql->f('userid');
    if ($self_id !== '' && $self_id !== null) {
        $mch_code     = $msql->f('mch_code');
        $self_uname   = $msql->f('username');
        $msql->query("SELECT callback_url, mch_secret FROM `$tb_mchs` WHERE mch_code='" . addslashes($mch_code) . "' AND status=1");
        $msql->next_record();
        $cb  = $msql->f('callback_url');
        $sec = $msql->f('mch_secret');
        $callback_url = ($cb !== null && $cb !== '') ? trim($cb) : '';
        $mch_secret   = ($sec !== null) ? $sec : '';
        if ($callback_url !== '') {
            return array(
                'agent_userid'   => $self_id,
                'agent_username' => $self_uname,
                'mch_code'       => $mch_code,
                'callback_url'   => $callback_url,
                'mch_secret'     => $mch_secret,
            );
        }
    }

    return null;
}

/**
 * 向运营商请求最新余额（getBalance），更新本地数据库并返回最新余额。
 * @param string     $userid     会员 userid
 * @param array|null $agent_info 可选，mch_get_top_api_agent 的返回值；null 则内部查询
 * @return array|null 含 money, kmoney；无 API 代理或请求失败时返回 null
 */
function mch_get_balance_from_api($userid, $agent_info = null) {
    global $msql, $tb_user;
    $userid = trim($userid);
    if ($userid === '') return null;

    if ($agent_info === null) {
        $agent_info = mch_get_top_api_agent($userid);
    }
    if ($agent_info === null) return null;

    $msql->query("SELECT username FROM `$tb_user` WHERE userid='" . addslashes($userid) . "'");
    $msql->next_record();
    $username = $msql->f('username');

    $url     = task_mch_method_url($agent_info['callback_url'], 'getBalance');
    $payload = array(
        'userid'      => $userid,
        'username'    => $username,
        'mch_code'    => $agent_info['mch_code'],
        'notify_time' => date('Y-m-d H:i:s'),
    );
    $ret = task_mch_http_post($url, $payload, $agent_info['mch_secret']);
    if ($ret['err'] !== '' || $ret['body'] === '') return null;

    $data = @json_decode($ret['body'], true);
    if (!is_array($data)) return null;

    $money  = isset($data['money'])   ? (float)$data['money']   : (isset($data['balance']) ? (float)$data['balance'] : null);
    $kmoney = isset($data['kmoney'])  ? (float)$data['kmoney']  : null;

    // 合理性校验，防止恶意值篡改
    $max_balance = 1000000000;
    if ($money  !== null && ($money  < 0 || $money  > $max_balance)) $money  = null;
    if ($kmoney !== null && ($kmoney < 0 || $kmoney > $max_balance)) $kmoney = null;

    if ($money !== null || $kmoney !== null) {
        $uid = addslashes($userid);
        if ($money !== null && $kmoney !== null) {
            $msql->query("UPDATE `$tb_user` SET money='$money', kmoney='$kmoney' WHERE userid='$uid'");
        } elseif ($money !== null) {
            $msql->query("UPDATE `$tb_user` SET money='$money' WHERE userid='$uid'");
        } else {
            $msql->query("UPDATE `$tb_user` SET kmoney='$kmoney' WHERE userid='$uid'");
        }
    }

    // 返回数据库最新值
    $msql->query("SELECT money, kmoney FROM `$tb_user` WHERE userid='" . addslashes($userid) . "'");
    $msql->next_record();
    return array(
        'money'  => (float)$msql->f('money'),
        'kmoney' => (float)$msql->f('kmoney'),
    );
}

/**
 * 解析运营商通知响应中的余额，与本地期望值核对；若不一致记录日志并更新本地余额。
 * 规则七：每次通知都必须核对运营商反馈余额，确保与系统预期一致。
 * @param string     $userid          会员 userid
 * @param string     $response_body   运营商 HTTP 响应 body
 * @param float|null $expected_kmoney 期望的 kmoney；null 则读取当前本地值
 * @return array 含 operator_balance (float|null), matched (bool)
 */
function mch_verify_and_update_balance($userid, $response_body, $expected_kmoney = null) {
    global $msql, $tb_user;

    if ($expected_kmoney === null) {
        $msql->query("SELECT kmoney FROM `$tb_user` WHERE userid='" . addslashes($userid) . "'");
        $msql->next_record();
        $expected_kmoney = (float)$msql->f('kmoney');
    }

    $data = @json_decode($response_body, true);
    if (!is_array($data)) {
        return array('operator_balance' => null, 'matched' => false);
    }

    $op_money  = isset($data['money'])   ? (float)$data['money']   : (isset($data['balance']) ? (float)$data['balance'] : null);
    $op_kmoney = isset($data['kmoney'])  ? (float)$data['kmoney']  : null;
    $op_main   = ($op_kmoney !== null) ? $op_kmoney : $op_money;

    $matched = ($op_main === null) ? true : (abs($op_main - $expected_kmoney) < 0.02);

    // 更新本地余额（合理性校验后）
    $max_balance = 1000000000;
    if ($op_money  !== null && ($op_money  < 0 || $op_money  > $max_balance)) $op_money  = null;
    if ($op_kmoney !== null && ($op_kmoney < 0 || $op_kmoney > $max_balance)) $op_kmoney = null;

    if ($op_money !== null || $op_kmoney !== null) {
        $uid = addslashes($userid);
        if ($op_money !== null && $op_kmoney !== null) {
            $msql->query("UPDATE `$tb_user` SET money='$op_money', kmoney='$op_kmoney' WHERE userid='$uid'");
        } elseif ($op_money !== null) {
            $msql->query("UPDATE `$tb_user` SET money='$op_money' WHERE userid='$uid'");
        } else {
            $msql->query("UPDATE `$tb_user` SET kmoney='$op_kmoney' WHERE userid='$uid'");
        }
    }

    // 余额不一致时写入日志
    if (!$matched && $op_main !== null) {
        $log_dir  = __DIR__ . '/logs';
        if (!is_dir($log_dir)) @mkdir($log_dir, 0755, true);
        $log_line = date('Y-m-d H:i:s') . ' BALANCE_MISMATCH userid=' . $userid
            . ' local=' . $expected_kmoney . ' operator=' . $op_main
            . ' diff=' . round($op_main - $expected_kmoney, 4) . "\n";
        @file_put_contents($log_dir . '/balance_verify.log', $log_line, FILE_APPEND | LOCK_EX);
    }

    return array('operator_balance' => $op_main, 'matched' => $matched);
}

/**
 * 下注时变更余额通知：遍历层级找到最顶级 API 代理，向其 changeBalance 地址发起请求，带上本次下注详情。
 * 通知后自动核对运营商反馈余额（规则七）。
 * @param string $userid 会员 userid
 * @param float  $amount 变动金额（正数）
 * @param string $type   deduct=扣款（下注）, add=加款（如退款、派奖）
 * @param array  $orders 本次下注的注单列表，每项含 tid,gid,qishu,bid,sid,cid,pid,content,je,gname 等
 * @return bool 是否成功请求到商户
 */
function mch_notify_change_balance($userid, $amount, $type = 'deduct', $orders = array()) {
    global $msql, $tb_user, $tb_mchs;
    $userid = trim($userid);
    $amount = (float) $amount;
    if ($userid === '' || $amount <= 0) return false;

    // 遍历层级找到最顶级 API 代理（规则三/四核心逻辑）
    $agent = mch_get_top_api_agent($userid);
    if ($agent === null) return false;

    // 读取当前本地 kmoney 作为期望值（此时已完成扣款）
    $msql->query("SELECT username, kmoney FROM `$tb_user` WHERE userid='" . addslashes($userid) . "'");
    $msql->next_record();
    $username        = $msql->f('username');
    $expected_kmoney = (float)$msql->f('kmoney');

    $url     = task_mch_method_url($agent['callback_url'], 'changeBalance');
    $payload = array(
        'userid'      => $userid,
        'username'    => $username,
        'mch_code'    => $agent['mch_code'],
        'amount'      => $amount,
        'type'        => $type,
        'notify_time' => date('Y-m-d H:i:s'),
    );
    if (!empty($orders)) {
        $payload['orders'] = $orders;
    }
    $ret = task_mch_http_post($url, $payload, $agent['mch_secret']);
    if ($ret['err'] !== '') return false;

    // 规则七：核对运营商反馈余额
    mch_verify_and_update_balance($userid, $ret['body'], $expected_kmoney);
    return true;
}

/**
 * 注单结算通知：遍历层级找到最顶级 API 代理，向其 settleOrder 地址推送已结算注单。
 * 通知后自动核对运营商反馈余额（规则七）。
 * @param string $userid 会员 userid
 * @param array  $orders 已结算注单列表，每项含 id,tid,code,userid,qishu,dates,gid,bid,sid,cid,pid,content,je,prize,z,valid_je,win_loss,time 等
 * @return bool 是否成功请求到商户
 */
function mch_notify_settle_orders($userid, $orders) {
    global $msql, $tb_user, $tb_mchs;
    $userid = trim($userid);
    if ($userid === '' || empty($orders)) return false;

    // 遍历层级找到最顶级 API 代理（规则五核心逻辑）
    $agent = mch_get_top_api_agent($userid);
    if ($agent === null) return false;

    // 结算前读取本地 kmoney + 计算本批奖金总额，作为运营商反馈余额的期望值
    // 运营商结算后余额 = 本地 kmoney（未含奖金）+ 本期奖金总和
    $msql->query("SELECT username, kmoney FROM `$tb_user` WHERE userid='" . addslashes($userid) . "'");
    $msql->next_record();
    $username        = $msql->f('username');
    $current_kmoney  = (float)$msql->f('kmoney');
    $total_prize     = 0;
    foreach ($orders as $o) {
        $total_prize += (float)$o['prize'];
    }
    $settle_expected = $current_kmoney + $total_prize;

    $url     = task_mch_method_url($agent['callback_url'], 'settleOrder');
    $payload = array(
        'userid'      => $userid,
        'username'    => $username,
        'mch_code'    => $agent['mch_code'],
        'orders'      => $orders,
        'notify_time' => date('Y-m-d H:i:s'),
    );
    $ret = task_mch_http_post($url, $payload, $agent['mch_secret']);
    if ($ret['err'] !== '') return false;

    // 规则七：核对运营商反馈余额（期望 = 结算前本地余额 + 本期奖金总额）
    mch_verify_and_update_balance($userid, $ret['body'], $settle_expected);
    return true;
}

/**
 * 取消注单通知：遍历层级找到最顶级 API 代理，向其 cancelOrder 地址推送被取消的注单。
 * 通知后自动核对运营商反馈余额（规则七）。
 * @param string $userid 会员 userid
 * @param array  $orders 被取消的注单列表，每项含 id,tid,code,qishu,gid,bid,sid,cid,pid,content,je,time 等
 * @return bool 是否成功请求到商户
 */
function mch_notify_cancel_orders($userid, $orders) {
    global $msql, $tb_user, $tb_mchs;
    $userid = trim($userid);
    if ($userid === '' || empty($orders)) return false;

    // 遍历层级找到最顶级 API 代理（规则六核心逻辑）
    $agent = mch_get_top_api_agent($userid);
    if ($agent === null) return false;

    // 取消前读取本地 kmoney 并计算退款总额，作为核对期望值
    // cancelOrder 通知时本地余额尚未退回，运营商收到通知后会加回，其反馈余额 = 本地 kmoney + 退款总额
    $msql->query("SELECT username, kmoney FROM `$tb_user` WHERE userid='" . addslashes($userid) . "'");
    $msql->next_record();
    $username        = $msql->f('username');
    $before_kmoney   = (float)$msql->f('kmoney');
    $total_refund    = 0;
    foreach ($orders as $o) {
        $total_refund += (float)$o['je'];
    }
    $expected_after = $before_kmoney + $total_refund;

    $url     = task_mch_method_url($agent['callback_url'], 'cancelOrder');
    $payload = array(
        'userid'      => $userid,
        'username'    => $username,
        'mch_code'    => $agent['mch_code'],
        'orders'      => $orders,
        'notify_time' => date('Y-m-d H:i:s'),
    );
    $ret = task_mch_http_post($url, $payload, $agent['mch_secret']);
    if ($ret['err'] !== '') return false;

    // 规则七：核对运营商反馈余额（期望 = 本地原余额 + 退款总额）
    mch_verify_and_update_balance($userid, $ret['body'], $expected_after);
    return true;
}

// 仅在被直接访问时执行任务逻辑，被 include 时只提供函数
$is_main = (isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME']));

if (!$is_main) {
    return;
}

// BugN3 fix: 任何人都可以 HTTP 访问触发结算/取消/余额同步等敏感操作，需要访问控制
// 限制只允许本机（127.0.0.1）或通过预共享密钥访问
$_task_ip   = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$_task_key  = isset($_REQUEST['task_key']) ? trim($_REQUEST['task_key']) : '';
// task_key 从系统配置读取，与 OPEN_API_SECRET 独立；本机 IP 访问（cron）不需要 key
$_allowed_ip = array('127.0.0.1', '::1');
if (!in_array($_task_ip, $_allowed_ip, true)) {
    $db_task_key = isset($config['rkey']) ? $config['rkey'] : '';
    if ($db_task_key === '' || $db_task_key !== $_task_key) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(403);
        echo json_encode(array('code' => 403, 'msg' => 'forbidden'), JSON_UNESCAPED_UNICODE);
        exit;
    }
}
unset($_task_ip, $_task_key, $_allowed_ip, $db_task_key);

$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : 'notify';

switch ($action) {
    case 'getBalance':
        // 实时获取所有 is_api=1 用户余额：通过层级遍历找到对应的顶级 API 代理并请求 getBalance
        // 使用 mch_get_balance_from_api() 保持与其他通知路径一致的层级遍历逻辑
        $msql->query("SELECT userid FROM `$tb_user` WHERE is_api=1 AND status=1");
        $uid_list = array();
        while ($msql->next_record()) {
            $uid_list[] = $msql->f('userid');
        }
        foreach ($uid_list as $uid) {
            mch_get_balance_from_api($uid);
        }
        if (php_sapi_name() !== 'cli' && isset($_REQUEST['action'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('code' => 0, 'msg' => 'getBalance done'), JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'changeBalance':
        // 由外部 POST/GET 触发：userid, amount, type(deduct|add)，可选 orders（JSON 数组）
        $userid = isset($_REQUEST['userid']) ? trim($_REQUEST['userid']) : '';
        $amount = isset($_REQUEST['amount']) ? (float)$_REQUEST['amount'] : 0;
        $type   = isset($_REQUEST['type'])   ? trim($_REQUEST['type'])   : 'deduct';
        $orders = array();
        if (isset($_REQUEST['orders']) && is_string($_REQUEST['orders'])) {
            $orders = @json_decode($_REQUEST['orders'], true);
        }
        if (!is_array($orders)) {
            $orders = array();
        }
        if ($type !== 'add') {
            $type = 'deduct';
        }
        $ok = mch_notify_change_balance($userid, $amount, $type, $orders);
        if (php_sapi_name() !== 'cli' && isset($_REQUEST['action'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('code' => $ok ? 0 : 1, 'msg' => $ok ? 'ok' : 'fail'), JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'settleNotify':
        // 按期号、彩种拉取已结算注单，按 is_api=1 用户分组推送到商户 settleOrder
        $qishu = isset($_REQUEST['qishu']) ? trim($_REQUEST['qishu']) : '';
        $gid   = isset($_REQUEST['gid'])   ? trim($_REQUEST['gid'])   : '';
        if ($qishu === '' || $gid === '') {
            if (php_sapi_name() !== 'cli' && isset($_REQUEST['action'])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('code' => 1, 'msg' => 'missing qishu or gid'), JSON_UNESCAPED_UNICODE);
            }
            break;
        }
        $whi = " qishu='" . addslashes($qishu) . "' AND gid='" . addslashes($gid) . "' AND kk=1 ";
        $msql->query("SELECT id,tid,code,userid,qishu,dates,gid,bid,sid,cid,pid,content,je,prize,z,time FROM `$tb_lib` WHERE $whi");
        $by_user = array();
        while ($msql->next_record()) {
            $uid = $msql->f('userid');
            $je  = (float)$msql->f('je');
            $prize = (float)$msql->f('prize');
            $z   = (int)$msql->f('z');
            $valid_je = $je;
            if ($z == 7) {
                $valid_je = 0;
            }
            $win_loss = $prize - $je;
            $by_user[$uid][] = array(
                'id'       => $msql->f('id'),
                'tid'      => $msql->f('tid'),
                'code'     => $msql->f('code'),
                'userid'   => $msql->f('userid'),
                'qishu'    => $msql->f('qishu'),
                'dates'    => $msql->f('dates'),
                'gid'      => $msql->f('gid'),
                'bid'      => $msql->f('bid'),
                'sid'      => $msql->f('sid'),
                'cid'      => $msql->f('cid'),
                'pid'      => $msql->f('pid'),
                'content'  => $msql->f('content'),
                'je'       => $je,
                'prize'    => $prize,
                'z'        => $z,
                'valid_je'=> $valid_je,
                'win_loss'=> $win_loss,
                'time'     => $msql->f('time'),
            );
        }
        foreach ($by_user as $uid => $orders) {
            mch_notify_settle_orders($uid, $orders);
        }
        if (php_sapi_name() !== 'cli' && isset($_REQUEST['action'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('code' => 0, 'msg' => 'settleNotify done'), JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'cancelOrder':
        // 取消注单通知：传入注单 id 或 code 列表，查询后按用户分组推送到商户 cancelOrder
        $ids   = isset($_REQUEST['ids'])   ? trim($_REQUEST['ids'])   : '';
        $codes = isset($_REQUEST['codes']) ? trim($_REQUEST['codes']) : '';
        if ($ids === '' && $codes === '') {
            if (php_sapi_name() !== 'cli' && isset($_REQUEST['action'])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('code' => 1, 'msg' => 'missing ids or codes'), JSON_UNESCAPED_UNICODE);
            }
            break;
        }
        $whi = '';
        if ($ids !== '') {
            $id_arr = array_map('intval', explode(',', $ids));
            $id_arr = array_filter($id_arr);
            if (!empty($id_arr)) {
                $whi = " id IN (" . implode(',', $id_arr) . ") ";
            }
        }
        if ($whi === '' && $codes !== '') {
            $code_arr = array_map(function($c) { return "'" . addslashes(trim($c)) . "'"; }, explode(',', $codes));
            $code_arr = array_filter($code_arr);
            if (!empty($code_arr)) {
                $whi = " code IN (" . implode(',', $code_arr) . ") ";
            }
        }
        if ($whi === '') {
            if (php_sapi_name() !== 'cli' && isset($_REQUEST['action'])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array('code' => 1, 'msg' => 'invalid ids or codes'), JSON_UNESCAPED_UNICODE);
            }
            break;
        }
        $msql->query("SELECT id,tid,code,userid,qishu,dates,gid,bid,sid,cid,pid,content,je,time FROM `$tb_lib` WHERE $whi");
        $by_user = array();
        while ($msql->next_record()) {
            $uid = $msql->f('userid');
            $by_user[$uid][] = array(
                'id'      => $msql->f('id'),
                'tid'     => $msql->f('tid'),
                'code'    => $msql->f('code'),
                'userid'  => $msql->f('userid'),
                'qishu'   => $msql->f('qishu'),
                'dates'   => $msql->f('dates'),
                'gid'     => $msql->f('gid'),
                'bid'     => $msql->f('bid'),
                'sid'     => $msql->f('sid'),
                'cid'     => $msql->f('cid'),
                'pid'     => $msql->f('pid'),
                'content' => $msql->f('content'),
                'je'      => (float)$msql->f('je'),
                'time'    => $msql->f('time'),
            );
        }
        foreach ($by_user as $uid => $orders) {
            mch_notify_cancel_orders($uid, $orders);
        }
        if (php_sapi_name() !== 'cli' && isset($_REQUEST['action'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('code' => 0, 'msg' => 'cancelOrder done'), JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        // 原有逻辑：按用户通知游戏数据到 callback_url（不带方法名，保持兼容）
        $msql->query("SELECT userid, username, mch_code, money, kmoney, maxmoney, kmaxmoney FROM `$tb_user` WHERE is_api=1 AND status=1 AND mch_code!=''");
        $users = array();
        while ($msql->next_record()) {
            $users[] = array(
                'userid'   => $msql->f('userid'),
                'username' => $msql->f('username'),
                'mch_code' => $msql->f('mch_code'),
                'money'    => $msql->f('money'),
                'kmoney'   => $msql->f('kmoney'),
                'maxmoney' => $msql->f('maxmoney'),
                'kmaxmoney'=> $msql->f('kmaxmoney'),
            );
        }

        foreach ($users as $u) {
            $mch_code = $u['mch_code'];
            $msql->query("SELECT id, callback_url, mch_secret, status FROM `$tb_mchs` WHERE mch_code='" . addslashes($mch_code) . "' AND status=1");
            $msql->next_record();
            if ($msql->f('callback_url') === '' || $msql->f('callback_url') === null) {
                continue;
            }
            $callback_url = trim($msql->f('callback_url'));
            $mch_secret   = $msql->f('mch_secret');
            if ($callback_url === '') continue;

            $userid = $u['userid'];
            $dates  = date('Y-m-d');
            $msql->query("SELECT COUNT(*) AS cnt, COALESCE(SUM(je),0) AS total_je FROM `$tb_lib` WHERE userid='$userid' AND dates='$dates'");
            $msql->next_record();
            $today_cnt   = (int)$msql->f('cnt');
            $today_je    = (float)$msql->f('total_je');

            $payload = array(
                'userid'     => $u['userid'],
                'username'   => $u['username'],
                'mch_code'   => $mch_code,
                'money'      => (float)$u['money'],
                'kmoney'     => (float)$u['kmoney'],
                'maxmoney'   => (float)$u['maxmoney'],
                'kmaxmoney'  => (float)$u['kmaxmoney'],
                'today_count'=> $today_cnt,
                'today_je'   => $today_je,
                'notify_time'=> date('Y-m-d H:i:s'),
            );
            // Bug9 fix: 原代码用 md5(secret+userid+notify_time) 签名，与 task_mch_http_post 函数
            // 用 md5(secret+json) 的算法不一致，商户无法用统一方式验签。统一改为调用 task_mch_http_post。
            task_mch_http_post($callback_url, $payload, $mch_secret);
        }
        break;
}
