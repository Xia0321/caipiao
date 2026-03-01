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

$tb_user = 'x_user';
$tb_mchs = 'x_mchs';
$tb_lib  = 'x_lib';

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
 * 下注时变更余额通知：向该用户所属商户的 changeBalance 地址发起请求，可带上本次下注详情。
 * @param string $userid 会员 userid
 * @param float  $amount 变动金额（正数）
 * @param string $type   deduct=扣款（下注）, add=加款（如退款、派奖）
 * @param array  $orders 本次下注的注单列表，每项含 tid,gid,qishu,bid,sid,cid,pid,content,je,gname 等，便于回调方记账
 * @return bool 是否成功请求到商户（不解析商户返回内容）
 */
function mch_notify_change_balance($userid, $amount, $type = 'deduct', $orders = array()) {
    global $msql, $tb_user, $tb_mchs;
    $userid = trim($userid);
    $amount = (float) $amount;
    if ($userid === '' || $amount <= 0) {
        return false;
    }
    $msql->query("SELECT userid, username, mch_code FROM `$tb_user` WHERE userid='" . addslashes($userid) . "' AND is_api=1 AND status=1 AND mch_code!=''");
    $msql->next_record();
    if ($msql->f('userid') !== $userid) {
        return false;
    }
    $username  = $msql->f('username');
    $mch_code   = $msql->f('mch_code');
    $msql->query("SELECT callback_url, mch_secret FROM `$tb_mchs` WHERE mch_code='" . addslashes($mch_code) . "' AND status=1");
    $msql->next_record();
    if ($msql->f('callback_url') === '' || $msql->f('callback_url') === null) {
        return false;
    }
    $callback_url = trim($msql->f('callback_url'));
    $mch_secret   = $msql->f('mch_secret');
    $url = task_mch_method_url($callback_url, 'changeBalance');
    $payload = array(
        'userid'     => $userid,
        'username'   => $username,
        'mch_code'   => $mch_code,
        'amount'     => $amount,
        'type'       => $type,
        'notify_time'=> date('Y-m-d H:i:s'),
    );
    if (!empty($orders)) {
        $payload['orders'] = $orders;
    }
    $ret = task_mch_http_post($url, $payload, $mch_secret);
    return $ret['err'] === '';
}

/**
 * 注单结算通知：向商户 settleOrder 地址推送已结算注单（有效投注、输赢金额等）。
 * @param string $userid 会员 userid
 * @param array  $orders 已结算注单列表，每项含 id,tid,code,userid,qishu,dates,gid,bid,sid,cid,pid,content,je,prize,z,valid_je,win_loss,time 等
 * @return bool 是否成功请求到商户
 */
function mch_notify_settle_orders($userid, $orders) {
    global $msql, $tb_user, $tb_mchs;
    $userid = trim($userid);
    if ($userid === '' || empty($orders)) {
        return false;
    }
    $msql->query("SELECT userid, username, mch_code FROM `$tb_user` WHERE userid='" . addslashes($userid) . "' AND is_api=1 AND status=1 AND mch_code!=''");
    $msql->next_record();
    if ($msql->f('userid') !== $userid) {
        return false;
    }
    $username   = $msql->f('username');
    $mch_code   = $msql->f('mch_code');
    $msql->query("SELECT callback_url, mch_secret FROM `$tb_mchs` WHERE mch_code='" . addslashes($mch_code) . "' AND status=1");
    $msql->next_record();
    if ($msql->f('callback_url') === '' || $msql->f('callback_url') === null) {
        return false;
    }
    $callback_url = trim($msql->f('callback_url'));
    $mch_secret   = $msql->f('mch_secret');
    $url = task_mch_method_url($callback_url, 'settleOrder');
    $payload = array(
        'userid'     => $userid,
        'username'   => $username,
        'mch_code'   => $mch_code,
        'orders'     => $orders,
        'notify_time'=> date('Y-m-d H:i:s'),
    );
    $ret = task_mch_http_post($url, $payload, $mch_secret);
    return $ret['err'] === '';
}

/**
 * 取消注单通知：向商户 cancelOrder 地址推送被取消的注单，便于回调方加回余额。
 * @param string $userid 会员 userid
 * @param array  $orders 被取消的注单列表，每项含 id,tid,code,qishu,gid,bid,sid,cid,pid,content,je,time 等
 * @return bool 是否成功请求到商户
 */
function mch_notify_cancel_orders($userid, $orders) {
    global $msql, $tb_user, $tb_mchs;
    $userid = trim($userid);
    if ($userid === '' || empty($orders)) {
        return false;
    }
    $msql->query("SELECT userid, username, mch_code FROM `$tb_user` WHERE userid='" . addslashes($userid) . "' AND is_api=1 AND status=1 AND mch_code!=''");
    $msql->next_record();
    if ($msql->f('userid') !== $userid) {
        return false;
    }
    $username   = $msql->f('username');
    $mch_code   = $msql->f('mch_code');
    $msql->query("SELECT callback_url, mch_secret FROM `$tb_mchs` WHERE mch_code='" . addslashes($mch_code) . "' AND status=1");
    $msql->next_record();
    if ($msql->f('callback_url') === '' || $msql->f('callback_url') === null) {
        return false;
    }
    $callback_url = trim($msql->f('callback_url'));
    $mch_secret   = $msql->f('mch_secret');
    $url = task_mch_method_url($callback_url, 'cancelOrder');
    $payload = array(
        'userid'     => $userid,
        'username'   => $username,
        'mch_code'   => $mch_code,
        'orders'     => $orders,
        'notify_time'=> date('Y-m-d H:i:s'),
    );
    $ret = task_mch_http_post($url, $payload, $mch_secret);
    return $ret['err'] === '';
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
        // 实时获取 is_api=1 用户余额：向商户 getBalance 地址请求，可选回写本地余额
        $msql->query("SELECT userid, username, mch_code, money, kmoney FROM `$tb_user` WHERE is_api=1 AND status=1 AND mch_code!=''");
        $user_list = array();
        while ($msql->next_record()) {
            $user_list[] = array(
                'userid'   => $msql->f('userid'),
                'username' => $msql->f('username'),
                'mch_code' => $msql->f('mch_code'),
                'money'    => (float)$msql->f('money'),
                'kmoney'   => (float)$msql->f('kmoney'),
            );
        }
        foreach ($user_list as $u) {
            $mch_code = $u['mch_code'];
            $msql->query("SELECT callback_url, mch_secret FROM `$tb_mchs` WHERE mch_code='" . addslashes($mch_code) . "' AND status=1");
            $msql->next_record();
            if ($msql->f('callback_url') === '' || $msql->f('callback_url') === null) {
                continue;
            }
            $callback_url = trim($msql->f('callback_url'));
            $mch_secret   = $msql->f('mch_secret');
            $url = task_mch_method_url($callback_url, 'getBalance');
            $payload = array(
                'userid'     => $u['userid'],
                'username'   => $u['username'],
                'mch_code'   => $mch_code,
                'notify_time'=> date('Y-m-d H:i:s'),
            );
            $ret = task_mch_http_post($url, $payload, $mch_secret);
            if ($ret['err'] !== '') {
                continue;
            }
            $data = @json_decode($ret['body'], true);
            if (!is_array($data)) {
                continue;
            }
            // 若商户返回 balance 或 money/kmoney，则回写本地（可选）
            $money  = isset($data['money'])  ? (float)$data['money']  : (isset($data['balance']) ? (float)$data['balance'] : null);
            $kmoney = isset($data['kmoney']) ? (float)$data['kmoney'] : null;
            // BugN4 fix: 回写前检查数据合理性，防止恶意商户返回异常值篡改余额
            $max_balance = isset($config['maxmoney']) ? (float)$config['maxmoney'] : 1000000000;
            if ($money !== null && ($money < 0 || $money > $max_balance)) {
                $money = null;
            }
            if ($kmoney !== null && ($kmoney < 0 || $kmoney > $max_balance)) {
                $kmoney = null;
            }
            if ($money !== null || $kmoney !== null) {
                $uid = addslashes($u['userid']);
                if ($money !== null && $kmoney !== null) {
                    $msql->query("UPDATE `$tb_user` SET money='$money', kmoney='$kmoney' WHERE userid='$uid'");
                } elseif ($money !== null) {
                    $msql->query("UPDATE `$tb_user` SET money='$money' WHERE userid='$uid'");
                } else {
                    $msql->query("UPDATE `$tb_user` SET kmoney='$kmoney' WHERE userid='$uid'");
                }
            }
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
