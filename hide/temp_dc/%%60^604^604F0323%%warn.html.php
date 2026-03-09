<?php /* Smarty version 2.6.18, created on 2026-03-09 21:14:34
         compiled from warn.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style type="text/css">
.data_table .flag{width:70px;}
.data_table td{height:30px;}
.pantb td{text-align:center}
input.s1{padding:1.5px;}
</style>
</head>
<body id="topbody">
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';
var js=1;var sss='warn';
</script>

<div class="main">
	<div class="top_info">
		<span class="title">警示金额设定</span><span class="right"></span>
	</div>
	<form style="margin:0;padding:0" method="post">
		<div class="contents param_panel">
			<div class="game_class">
				<ul>
<?php if ($this->_tpl_vars['config']['fasttype'] == 1): ?>
<li><span>快开彩</span>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['game']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <?php if ($this->_tpl_vars['game'][$this->_sections['i']['index']]['fast'] == 1): ?><a href="javascript:void(0)" class="g<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
"  gid='<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
'><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</a><?php endif; ?>
 <?php endfor; endif; ?>
</li>
<?php endif; ?>
<?php if ($this->_tpl_vars['config']['slowtype'] == 1): ?>
<li><span>低频彩</span>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['game']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <?php if ($this->_tpl_vars['game'][$this->_sections['i']['index']]['fast'] == 0): ?><a href="javascript:void(0)" class="g<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
" gid='<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
'><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</a><?php endif; ?>
 <?php endfor; endif; ?>
</li>
<?php endif; ?>
				</ul>
			</div>
 
			<table class="layout pantb">
			<tbody>
			<tr>
				<td class="panel">
					<table class="list data_table at_0">
					<thead>
					<tr>
						<th>
							<input type="checkbox" class="all">全选
						</th>
						<th>
							种类
						</th>
						<th>
							警示注额
						</th>
						<th>
							止损金额
						</th>
					</tr>
					</thead>
					<tbody>
                    
   <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['warn']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
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
     <?php if ($this->_sections['i']['index'] <= $this->_tpl_vars['nums']): ?>
   <TR class='nr' f='<?php echo $this->_tpl_vars['warn'][$this->_sections['i']['index']]['ftype']; ?>
'>
    <td><input type="checkbox"  class="pantbc"  /><input type="button" class="s1 pantb" value="同步选中" /></td>
    <th><?php echo $this->_tpl_vars['warn'][$this->_sections['i']['index']]['name']; ?>
</th>
    <td><input type="text" value='<?php echo $this->_tpl_vars['warn'][$this->_sections['i']['index']]['je']; ?>
'  class="flag je" /></td>
    <td><input type="text" value='<?php echo $this->_tpl_vars['warn'][$this->_sections['i']['index']]['ks']; ?>
'  class="flag ks" /></td>
   </TR>
   <?php endif; ?>
   <?php endfor; endif; ?>
   					</tbody>
					</table>
				</td>
				<td class="panel">
					<table class="list data_table at_0">
					<thead>
					<tr>
						<th>
							<input type="checkbox" class="all">全选
						</th>
						<th>
							种类
						</th>
						<th>
							警示注额
						</th>
						<th>
							止损金额
						</th>
					</tr>
					</thead>
					<tbody>
   <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['warn']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
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
     <?php if ($this->_sections['i']['index'] > $this->_tpl_vars['nums']): ?>
   <TR class='nr' f='<?php echo $this->_tpl_vars['warn'][$this->_sections['i']['index']]['ftype']; ?>
'>
    <td><input type="checkbox"  class="pantbc"  /><input type="button" class="s1 pantb" value="同步选中" /></td>
    <th><?php echo $this->_tpl_vars['warn'][$this->_sections['i']['index']]['name']; ?>
</th>
    <td><input type="text" value='<?php echo $this->_tpl_vars['warn'][$this->_sections['i']['index']]['je']; ?>
'  class="flag je" /></td>
    <td><input type="text" value='<?php echo $this->_tpl_vars['warn'][$this->_sections['i']['index']]['ks']; ?>
'  class="flag ks" /></td>
   </TR>
   <?php endif; ?>
   <?php endfor; endif; ?>
   					</tbody>
					</table>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
        


		<div class="control">
			<input type="button" gid="<?php echo $this->_tpl_vars['gid']; ?>
" class="button yiwotongbu" value="同步<?php echo $this->_tpl_vars['flname']; ?>
">&nbsp;&nbsp;&nbsp;<input name="xtype" value="setautofly" type="hidden"><input type="button" value="保存" class="button send"><input type="button" value="取消" class="button" style="display:none">
		</div>
        
	</form>
</div>

<div id='test'></div>
<script language="javascript">
var gid=<?php echo $this->_tpl_vars['gid']; ?>
;
</script>
</body>
</html>