<?php
include '../data/comm.inc.php';
include '../data/myadminvar.php';
include '../func/func.php';
include '../func/adminfunc.php';
include '../include.php';
include './checklogin.php';

$tb_mchs = 'x_mchs';

switch ($_REQUEST['xtype']) {
    case 'show':
        $list = array();
        $msql->query("SELECT id, mch_code, callback_url, mch_secret, status, created_at FROM `$tb_mchs` ORDER BY id DESC");
        while ($msql->next_record()) {
            $list[] = array(
                'id' => $msql->f('id'),
                'mch_code' => $msql->f('mch_code'),
                'callback_url' => $msql->f('callback_url'),
                'mch_secret' => $msql->f('mch_secret'),
                'status' => $msql->f('status'),
                'statusz' => $msql->f('status') == 1 ? '启用' : '禁用',
                'created_at' => $msql->f('created_at'),
            );
        }
        $tpl->assign('list', $list);
        $tpl->assign('title', $config['webname'] . '-商户管理');
        $tpl->display('mch.html');
        break;

    case 'add':
        $tpl->assign('row', array('id' => '', 'mch_code' => '', 'callback_url' => '', 'mch_secret' => '', 'status' => 1));
        $tpl->assign('title', $config['webname'] . '-添加商户');
        $tpl->display('mchform.html');
        break;

    case 'edit':
        $id = (int)$_REQUEST['id'];
        if ($id <= 0) {
            header('Location: mch.php?xtype=show');
            exit;
        }
        $msql->query("SELECT id, mch_code, callback_url, mch_secret, status FROM `$tb_mchs` WHERE id='$id'");
        $msql->next_record();
        if ($msql->f('id') === '' || $msql->f('id') === null) {
            header('Location: mch.php?xtype=show');
            exit;
        }
        $row = array(
            'id' => $msql->f('id'),
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
        $mch_code = trim($_POST['mch_code']);
        $callback_url = trim($_POST['callback_url']);
        $mch_secret = trim($_POST['mch_secret']);
        $status = (int)$_POST['status'];
        if ($mch_code === '') {
            echo 0;
            exit;
        }
        $mch_code = addslashes($mch_code);
        $callback_url = addslashes($callback_url);
        $mch_secret = addslashes($mch_secret);
        $msql->query("SELECT id FROM `$tb_mchs` WHERE mch_code='$mch_code'");
        $msql->next_record();
        if ($msql->f('id') !== '' && $msql->f('id') !== null) {
            echo 2;
            exit;
        }
        $msql->query("INSERT INTO `$tb_mchs` SET mch_code='$mch_code', callback_url='$callback_url', mch_secret='$mch_secret', status='$status'");
        echo 1;
        break;

    case 'update':
        $id = (int)$_POST['id'];
        $mch_code = trim($_POST['mch_code']);
        $callback_url = trim($_POST['callback_url']);
        $mch_secret = trim($_POST['mch_secret']);
        $status = (int)$_POST['status'];
        if ($id <= 0 || $mch_code === '') {
            echo 0;
            exit;
        }
        $mch_code = addslashes($mch_code);
        $callback_url = addslashes($callback_url);
        $mch_secret = addslashes($mch_secret);
        $msql->query("SELECT id FROM `$tb_mchs` WHERE mch_code='$mch_code' AND id!='$id'");
        $msql->next_record();
        if ($msql->f('id') !== '' && $msql->f('id') !== null) {
            echo 2;
            exit;
        }
        $msql->query("UPDATE `$tb_mchs` SET mch_code='$mch_code', callback_url='$callback_url', mch_secret='$mch_secret', status='$status' WHERE id='$id'");
        echo 1;
        break;

    default:
        header('Location: mch.php?xtype=show');
        exit;
}
