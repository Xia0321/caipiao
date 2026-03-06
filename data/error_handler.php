<?php
/**
 * 全局错误与异常处理：发生错误时拦截执行并输出详细信息
 * 包含：文件、行号、方法/函数、错误类型、错误信息
 * 兼容 PHP 5.6（未使用 ??、Throwable、标量类型等 PHP 7+ 语法）
 */

if (!defined('ERROR_HANDLER_LOADED')) {
    define('ERROR_HANDLER_LOADED', 1);

    /**
     * 错误类型描述
     */
    function _error_type_name($type) {
        $map = array(
            E_ERROR             => 'E_ERROR',
            E_WARNING           => 'E_WARNING',
            E_PARSE             => 'E_PARSE',
            E_NOTICE            => 'E_NOTICE',
            E_CORE_ERROR        => 'E_CORE_ERROR',
            E_CORE_WARNING      => 'E_CORE_WARNING',
            E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
            E_USER_ERROR        => 'E_USER_ERROR',
            E_USER_WARNING      => 'E_USER_WARNING',
            E_USER_NOTICE       => 'E_USER_NOTICE',
            E_STRICT            => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED        => 'E_DEPRECATED',
            E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
        );
        return isset($map[$type]) ? $map[$type] : ('Unknown(' . $type . ')');
    }

    /**
     * 从 backtrace 取发生错误所在的方法/函数名
     */
    function _error_context_function($errfile, $errline) {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 12);
        foreach ($trace as $t) {
            $file = isset($t['file']) ? $t['file'] : '';
            $line = isset($t['line']) ? $t['line'] : 0;
            if ($file === $errfile && $line === $errline) {
                if (isset($t['class']) && $t['class'] !== '') {
                    return $t['class'] . (isset($t['type']) ? $t['type'] : '->') . (isset($t['function']) ? $t['function'] : '') . '()';
                }
                return (isset($t['function']) && $t['function'] !== '') ? $t['function'] . '()' : '{main}';
            }
        }
        return '{main}';
    }

    /**
     * 统一输出错误详情（纯文本，便于日志和调试）
     */
    function _error_output($info) {
        $is_cli = (php_sapi_name() === 'cli');
        $br = $is_cli ? "\n" : "<br>\n";
        $pre = $is_cli ? '' : '<pre style="text-align:left;background:#f8f8f8;padding:12px;border:1px solid #ccc;">';
        $suf = $is_cli ? '' : '</pre>';

        $lines = array(
            '========== 程序错误已拦截 ==========',
            '时间: ' . date('Y-m-d H:i:s'),
            '文件: ' . $info['file'],
            '行号: ' . $info['line'],
            '方法/函数: ' . $info['function'],
            '错误类型: ' . $info['type'],
            '错误信息: ' . $info['message'],
        );
        if (!empty($info['trace'])) {
            $lines[] = '堆栈跟踪:';
            $lines[] = $info['trace'];
        }
        $lines[] = '===================================';

        $text = implode($br, $lines);
        if (!$is_cli && !headers_sent()) {
            header('Content-Type: text/html; charset=utf-8');
        }
        echo $pre . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . $suf;
        exit(1);
    }

    /**
     * 非致命错误处理：仅拦截用户错误与可恢复错误，警告/注意/弃用等不拦截
     */
    function _global_error_handler($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            return false; // 被 @ 抑制则交给 PHP 默认行为
        }
        // 仅拦截需要终止程序的错误；警告(E_WARNING)、注意(E_NOTICE)、弃用(E_DEPRECATED)等不拦截
        $intercept_only = array(E_USER_ERROR, E_RECOVERABLE_ERROR);
        if (!in_array($errno, $intercept_only, true)) {
            return false;
        }
        $function = _error_context_function($errfile, $errline);
        _error_output(array(
            'file'    => $errfile,
            'line'    => $errline,
            'function'=> $function,
            'type'    => _error_type_name($errno),
            'message' => $errstr,
            'trace'   => '',
        ));
        return true;
    }

    /**
     * 未捕获异常处理
     */
    function _global_exception_handler(Exception $e) {
        $file = $e->getFile();
        $line = $e->getLine();
        $trace0 = $e->getTrace();
        $t0 = isset($trace0[0]) ? $trace0[0] : array();
        $function = isset($t0['function']) ? $t0['function'] : '{main}';
        if (!empty($t0['class'])) {
            $function = (isset($t0['class']) ? $t0['class'] : '') . (isset($t0['type']) ? $t0['type'] : '') . $function . '()';
        } else {
            $function = $function . '()';
        }
        _error_output(array(
            'file'    => $file,
            'line'    => $line,
            'function'=> $function,
            'type'    => get_class($e),
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        ));
    }

    /**
     * 在脚本结束时检查是否有致命错误（Fatal / Parse 等）
     */
    function _global_shutdown_handler() {
        $e = error_get_last();
        if (!is_array($e) || !isset($e['type'])) {
            return;
        }
        $fatals = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);
        if (!in_array($e['type'], $fatals, true)) {
            return;
        }
        $errfile = isset($e['file']) ? $e['file'] : '';
        $errline = isset($e['line']) ? $e['line'] : 0;
        $function = '{main}';
        if (function_exists('_error_context_function')) {
            $function = _error_context_function($errfile, $errline);
        }
        _error_output(array(
            'file'    => $errfile,
            'line'    => $errline,
            'function'=> $function,
            'type'    => _error_type_name($e['type']),
            'message' => isset($e['message']) ? $e['message'] : '',
            'trace'   => '',
        ));
    }

    set_error_handler('_global_error_handler', E_ALL | E_STRICT);
    set_exception_handler('_global_exception_handler');
    register_shutdown_function('_global_shutdown_handler');
}
