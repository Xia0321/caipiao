<?php /* Smarty version 2.6.18, created on 2026-02-14 18:04:00
         compiled from money_log.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'headers.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
<body id="topbody">
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';
var js=1;var sss='money_log';
</script>
<div class="main">
<div class="top_info">
<span class="title"><?php echo $this->_tpl_vars['username']; ?>
 资金日志</span>
<span class="right"></span>
</div>
<div class="contents">
<table class="data_table data_list list">
<thead><tr><th>时间</th><th>类型</th><th>发生金额</th><th>余额</th><th>备注</th><th>ip</th><th>IP归属地</th><th>操作员</th></tr></thead>
<tbody>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['log']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
<tr>
    <td class="time"><?php echo $this->_tpl_vars['log'][$this->_sections['i']['index']]['time']; ?>
</td>
    <td><?php if ($this->_tpl_vars['log'][$this->_sections['i']['index']]['type'] == 0): ?>低频<?php elseif ($this->_tpl_vars['fudong'] == 1): ?>现金<?php else: ?>快开<?php endif; ?></td>
    <td><?php echo $this->_tpl_vars['log'][$this->_sections['i']['index']]['money']; ?>
</td>
    <td><?php echo $this->_tpl_vars['log'][$this->_sections['i']['index']]['usermoney']; ?>
</td>
    <td><?php echo $this->_tpl_vars['log'][$this->_sections['i']['index']]['bz']; ?>
</td>
    <td><?php echo $this->_tpl_vars['log'][$this->_sections['i']['index']]['ip']; ?>
</td><td><?php echo $this->_tpl_vars['log'][$this->_sections['i']['index']]['addr']; ?>
</td><td><?php echo $this->_tpl_vars['log'][$this->_sections['i']['index']]['modiuser']; ?>
(<?php echo $this->_tpl_vars['log'][$this->_sections['i']['index']]['modisonuser']; ?>
)</td></tr>
<?php endfor; endif; ?>
</tbody>
</table>
</div>

<div class="page"><div class="page_info" page="<?php echo $this->_tpl_vars['page']; ?>
" pcount="<?php echo $this->_tpl_vars['pcount']; ?>
" uid='<?php echo $this->_tpl_vars['uid']; ?>
'>
<span class="record">共 <span><?php echo $this->_tpl_vars['rcount']; ?>
</span> 条记录</span>
<span class="page_count">共 <span><?php echo $this->_tpl_vars['pcount']; ?>
</span> 页</span>
<span class="page_control"><a class="prev">前一页</a>『<span class="current">&nbsp;1&nbsp;</span>&nbsp;<a href="javascript:void(0)" class="p">2</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">3</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">4</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">5</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">6</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">7</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">8</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">9</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">10</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">11</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">12</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">13</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">14</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="p">15</a>&nbsp;』<a class="next">后一页</a></span>
</div>
</div>

</div>
</body>
</html>