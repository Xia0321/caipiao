<?php /* Smarty version 2.6.18, created on 2026-02-11 20:17:14
         compiled from classlists.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style>
.addtb {
	width:200px;
	display:none;
	position:absolute;
	background:#fff
}
.addtb td {
	text-align:left;
}
.s_tb tr:hover {
	background:#FCF
}
</style>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='sclass';</script>
</head><body>
<div class="xbody1">
 <table class="tinfo wd100 s_tb">
  <tr>
   <th colspan="3">小分类</th>
   <th colspan="4"> 大分类：
    <select id='bid'>
     
     
 
 <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['b']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
 
     
     <option value="<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
"><?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
</option>
     
     
 <?php endfor; endif; ?>
 
    
    </select>
    <input type="text" id='name' />
    <input type="button" class="btn3 btnf" id='add'  value="添加小分类" /></th>
  </tr>
  <TR>
   <th><input type="checkbox" id='clickall' />
    全选</th>
   <th> <select class='bid'>
     <option value="">选择大分类</option>
     
     
 <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['b']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
 
     
     <option value="<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
"><?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
</option>
     
     
 <?php endfor; endif; ?>
 
    
    </select></th>
   <th>编号</th>
   <th>名称</th>
   <th>可用</th>
   <th>排序</th>
   <th><input type="button" class="btn3 btnf" id='delall'  value="删除选中" /></th>
  </TR>
  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['s']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
  <TR>
   <td><input type="checkbox" value='<?php echo $this->_tpl_vars['s'][$this->_sections['i']['index']]['sid']; ?>
' /></td>
   <td><input type="text" value='<?php echo $this->_tpl_vars['s'][$this->_sections['i']['index']]['bid']; ?>
' class=bids /><?php echo $this->_tpl_vars['s'][$this->_sections['i']['index']]['bname']; ?>
</td>
   <td><?php echo $this->_tpl_vars['s'][$this->_sections['i']['index']]['sid']; ?>
</td>
   <td><input type="text" value='<?php echo $this->_tpl_vars['s'][$this->_sections['i']['index']]['name']; ?>
' class=name /></td>
   <td><input type="checkbox" class=ifok <?php if ($this->_tpl_vars['s'][$this->_sections['i']['index']]['ifok'] == 1): ?>checked<?php endif; ?> /></td>
   <td><input type="text" value='<?php echo $this->_tpl_vars['s'][$this->_sections['i']['index']]['xsort']; ?>
' class=xsort /></td>
   <td><input type="button" class="edit btn1 btnf" value='修改' />
    <input type="button" class="delone btn1 btnf" value='删除' /></td>
  </TR>
  <?php endfor; endif; ?>
 </table>
</div>
<script language="javascript">
$(".bid").val('<?php echo $this->_tpl_vars['bid']; ?>
');
</script>
<div id='test'></div>
</body>
</html>