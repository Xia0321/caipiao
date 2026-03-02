<?php
require("./checkagent.php");

$f = $msql->arr("select userid,layer,username from `$tb_user` where userid='$userid'", 1);
$f = $f[0];

$username = preg_replace('/[^a-zA-Z0-9]/', '', $_REQUEST['username']);
$page = max(1, intval($_REQUEST['page']));

// 验证被编辑用户是当前管理员的下线
$eu = $msql->arr("select * from `$tb_user` where username='$username' and fid{$f['layer']}='{$f['userid']}' and ifagent=0", 1);
$eu = $eu[0];
if (!$eu['userid']) {
    echo '无此用户或无权限';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'save') {
    $name       = mb_substr(preg_replace('/[<>\'"\\\\]/', '', $_POST['name']), 0, 10);
    $status     = intval($_POST['status']);
    if (!in_array($status, array(0, 1, 2))) $status = 0;
    $defaultpan = strtoupper(substr(preg_replace('/[^a-eA-E]/', '', $_POST['defaultpan']), 0, 1));
    if (!in_array($defaultpan, array('A','B','C','D','E'))) $defaultpan = $eu['defaultpan'];
    $kmaxmoney  = max(0, round(floatval($_POST['kmaxmoney']), 2));

    $sql = "update `$tb_user` set name='$name', status='$status', defaultpan='$defaultpan', kmaxmoney='$kmaxmoney'";

    $newpass = trim($_POST['newpass']);
    if ($newpass !== '') {
        $hashedpass = md5(md5($newpass) . $config['upass']);
        $sql .= ", userpass='$hashedpass'";
    }

    $sql .= " where userid='{$eu['userid']}'";
    $msql->query($sql);

    header("Location:/agent/user/list?page=$page");
    exit;
}

$tpl->assign("f", $f);
$tpl->assign("eu", $eu);
$tpl->assign("page", $page);
$tpl->display("agent_user_edit.html");
