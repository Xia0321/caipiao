<?php
/**
 * 入口页：根据设备类型跳转
 * 电脑 → /uxj  手机 → /mxj
 * 免登：action=entry 时调用 open_api.php 的 entry 逻辑（验证、写 session、按设备跳转），实现自动登录
 */

function is_mobile_device() {
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $ua = strtolower($ua);
    $mobile_keywords = array(
        'mobile', 'android', 'iphone', 'ipod', 'ipad', 'webos', 'blackberry',
        'windows phone', 'opera mini', 'opera mobi', 'iemobile', 'ucweb', 'micromessenger'
    );
    foreach ($mobile_keywords as $kw) {
        if (strpos($ua, $kw) !== false) {
            return true;
        }
    }
    return false;
}

$path = is_mobile_device() ? '/mxj/' : '/uxj/';
$query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '';
header('Location: ' . $path . $query, true, 302);
exit;
