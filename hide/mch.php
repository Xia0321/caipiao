<?php
include '../data/comm.inc.php';
include '../data/myadminvar.php';
include '../func/func.php';
include '../func/adminfunc.php';
include '../include.php';
include './checklogin.php';

// 商户配置已迁至 x_user 表（is_api, mch_code, mch_secret, callback_url），不再使用 x_mchs
switch ($_REQUEST['xtype']) {
    case 'show':
        $list = array();
        $msql->query("SELECT userid, username, mch_code, callback_url, mch_secret, status FROM `$tb_user` WHERE ifagent=1 AND is_api=1 ORDER BY userid DESC");
        while ($msql->next_record()) {
            $list[] = array(
                'id' => $msql->f('userid'),
                'userid' => $msql->f('userid'),
                'username' => $msql->f('username'),
                'mch_code' => $msql->f('mch_code'),
                'callback_url' => $msql->f('callback_url'),
                'mch_secret' => $msql->f('mch_secret'),
                'status' => $msql->f('status'),
                'statusz' => $msql->f('status') == 1 ? '启用' : '禁用',
                'created_at' => '',
            );
        }
        $tpl->assign('list', $list);
        $tpl->assign('title', $config['webname'] . '-商户管理');
        $tpl->display('mch.html');
        break;

    case 'add':
        $tpl->assign('row', array('id' => '', 'userid' => '', 'username' => '', 'mch_code' => '', 'callback_url' => '', 'mch_secret' => '', 'status' => 1));
        $tpl->assign('title', $config['webname'] . '-添加商户');
        $tpl->assign('agent_list', array());
        $msql->query("SELECT userid, username FROM `$tb_user` WHERE ifagent=1 AND (is_api=0 OR is_api IS NULL OR mch_code='' OR mch_code IS NULL) AND status=1 ORDER BY userid");
        $agent_list = array();
        while ($msql->next_record()) {
            $agent_list[] = array('userid' => $msql->f('userid'), 'username' => $msql->f('username'));
        }
        $tpl->assign('agent_list', $agent_list);
        $tpl->display('mchform.html');
        break;

    case 'edit':
        $uid = isset($_REQUEST['userid']) ? trim($_REQUEST['userid']) : (isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '');
        if ($uid === '') {
            header('Location: mch.php?xtype=show');
            exit;
        }
        $msql->query("SELECT userid, username, mch_code, callback_url, mch_secret, status FROM `$tb_user` WHERE userid='" . addslashes($uid) . "' AND ifagent=1 AND is_api=1");
        $msql->next_record();
        if ($msql->f('userid') === '' || $msql->f('userid') === null) {
            header('Location: mch.php?xtype=show');
            exit;
        }
        $row = array(
            'id' => $msql->f('userid'),
            'userid' => $msql->f('userid'),
            'username' => $msql->f('username'),
            'mch_code' => $msql->f('mch_code'),
            'callback_url' => $msql->f('callback_url'),
            'mch_secret' => $msql->f('mch_secret'),
            'status' => $msql->f('status'),
        );
        $tpl->assign('row', $row);
        $tpl->assign('title', $config['webname'] . '-编辑商户');
        $tpl->display('mchform.html');
        break;

    case 'save':
        $userid = trim($_POST['userid']);
        $mch_code = trim($_POST['mch_code']);
        $callback_url = trim($_POST['callback_url']);
        $mch_secret = trim($_POST['mch_secret']);
        $status = (int)$_POST['status'];
        if ($userid === '' || $mch_code === '') {
            echo 0;
            exit;
        }
        $userid = addslashes($userid);
        $mch_code_sql = addslashes($mch_code);
        $callback_url_sql = addslashes($callback_url);
        $mch_secret_sql = addslashes($mch_secret);
        $msql->query("SELECT userid FROM `$tb_user` WHERE userid='$userid' AND ifagent=1");
        $msql->next_record();
        if ($msql->f('userid') === '' || $msql->f('userid') === null) {
            echo 0;
            exit;
        }
        $msql->query("SELECT userid FROM `$tb_user` WHERE mch_code='$mch_code_sql' AND userid!='$userid'");
        $msql->next_record();
        if ($msql->f('userid') !== '' && $msql->f('userid') !== null) {
            echo 2;
            exit;
        }
        $msql->query("UPDATE `$tb_user` SET is_api=1, mch_code='$mch_code_sql', callback_url='$callback_url_sql', mch_secret='$mch_secret_sql', status='$status' WHERE userid='$userid'");
        echo 1;
        break;

    case 'update':
        $userid = trim($_POST['userid']);
        if ($userid === '' && isset($_POST['id'])) $userid = trim($_POST['id']);
        $mch_code = trim($_POST['mch_code']);
        $callback_url = trim($_POST['callback_url']);
        $mch_secret = trim($_POST['mch_secret']);
        $status = (int)$_POST['status'];
        if ($userid === '' || $mch_code === '') {
            echo 0;
            exit;
        }
        $userid = addslashes($userid);
        $mch_code_sql = addslashes($mch_code);
        $callback_url_sql = addslashes($callback_url);
        $mch_secret_sql = addslashes($mch_secret);
        $msql->query("SELECT userid FROM `$tb_user` WHERE mch_code='$mch_code_sql' AND userid!='$userid'");
        $msql->next_record();
        if ($msql->f('userid') !== '' && $msql->f('userid') !== null) {
            echo 2;
            exit;
        }
        $msql->query("UPDATE `$tb_user` SET mch_code='$mch_code_sql', callback_url='$callback_url_sql', mch_secret='$mch_secret_sql', status='$status' WHERE userid='$userid'");
        echo 1;
        break;

    default:
        header('Location: mch.php?xtype=show');
        exit;
}
