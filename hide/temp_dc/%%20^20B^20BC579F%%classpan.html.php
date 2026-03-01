<?php /* Smarty version 2.6.18, created on 2026-02-12 03:31:33
         compiled from classpan.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style>
.addtb{width:200px;display:none;position:absolute;background:#fff}
.addtb td{text-align:left;}
input.bc{background:#fff}
.s_tb tr:hover{background:#FCF}
</style>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='classpan';</script>
</head>
<body>
<div class="xbody1">

 <table class="tinfo wd100 s_tb">
 <tr><th colspan="1">玩法归类</th><td colspan="2">
 <select class="game">
     
     
     
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
  
     
     
     <option value="<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
" <?php if ($this->_tpl_vars['game'][$this->_sections['i']['index']]['gid'] == $this->_tpl_vars['gid']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</option>
     
     
     
  <?php endfor; endif; ?>
 
    
    
    </select>
 </td><th colspan="2"><input type="button" class="edit btn1 btnf" value='修改' /></th></tr>
 <TR>
 <th>ID</th><th>名称</th><th class="abcd">ABCD</th><th class="ab">AB</th><th class="ifok">ifok</th>
 </TR>
 <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['pan']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
       <td><INPUT type='text'  class="class"  value="<?php echo $this->_tpl_vars['pan'][$this->_sections['i']['index']]['class']; ?>
" style="display:none" /><?php echo $this->_tpl_vars['pan'][$this->_sections['i']['index']]['class']; ?>
</td>
       <td class="name"><?php echo $this->_tpl_vars['pan'][$this->_sections['i']['index']]['name']; ?>
</td>
       <TD><INPUT type='checkbox'  class="abcd" value="<?php echo $this->_tpl_vars['pan'][$this->_sections['i']['index']]['abcd']; ?>
" <?php if ($this->_tpl_vars['pan'][$this->_sections['i']['index']]['abcd'] == 1): ?>checked<?php endif; ?> /></TD>
       <TD><INPUT type='checkbox'  class="ab" value="<?php echo $this->_tpl_vars['pan'][$this->_sections['i']['index']]['ab']; ?>
" <?php if ($this->_tpl_vars['pan'][$this->_sections['i']['index']]['ab'] == 1): ?>checked<?php endif; ?> /></TD>
       <TD><INPUT type='checkbox'  class="ifok" value="<?php echo $this->_tpl_vars['pan'][$this->_sections['i']['index']]['ifok']; ?>
" <?php if ($this->_tpl_vars['pan'][$this->_sections['i']['index']]['ifok'] == 1): ?>checked<?php endif; ?> /></TD>

   
   </TR>
 <?php endfor; endif; ?>
  </table>
</div>
<div id='test'></div>
</body>
</html>