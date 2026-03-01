<?php /* Smarty version 2.6.18, created on 2026-02-11 20:17:17
         compiled from classlist.html */ ?>
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
</style>
</head><body>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='class';</script>
<div class="xbody1" style="width:98%">
 <table class="tinfo wd100 s_tb">
  <tr>
   <th colspan="3">分类</th>
   <th colspan="10">名称：
    <input type="text" id='name' />
    <select id="bid">
     <option value="">大分类</option>
     
     
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
          
     
     <option value='<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
'><?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
</option>
     
     
       <?php endfor; endif; ?>
       
    
    </select>
    <select id="sid">
     <option value="">小分类</option>
    </select>
  
    <input type="button" class="btn3 btnf" id='add'  value="添加分类" /></th>
  </tr>
  <TR>
   <th><input type="checkbox" id='clickall' />
    全选</th>
   <th>编号</th>
   <th><select class="bid">
     <option value="">大分类</option>
     
     
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
          
     
     <option value='<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
' <?php if ($this->_tpl_vars['b'][$this->_sections['i']['index']]['bid'] == $this->_tpl_vars['bid']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
</option>
     
     
       <?php endfor; endif; ?>
       
    
    </select>
   </th>
   <th><select class="sid">
     <option value="">小分类</option>
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
          
     
     <option value='<?php echo $this->_tpl_vars['s'][$this->_sections['i']['index']]['sid']; ?>
' <?php if ($this->_tpl_vars['s'][$this->_sections['i']['index']]['sid'] == $this->_tpl_vars['sid']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['s'][$this->_sections['i']['index']]['name']; ?>
</option>
     
     
       <?php endfor; endif; ?>
    </select>
   </th>
   <th>名称</th>
   <th>可用</th>
   <th>码类型</th>
   <th>面类型</th>
   <th>大面类型</th>
   <th>排序</th>

   <th>分类</th>
    <th>唯一</th>
   <th><input type="button" class="btn3 btnf" id='delall'  value="删除选中" /><input type="button" class="edit btn1 btnf"  id='edit' value='修改选中' /></th>
  </TR>
  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['c']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
   <td><input type="checkbox" value='<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['cid']; ?>
' /></td>
   <td><?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['cid']; ?>
</td>
   <td><input type="text" value="<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['bid']; ?>
" cid='<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['cid']; ?>
' class="bid" />
    [<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['bname']; ?>
] </td>
   <td><input type="text" value="<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['sid']; ?>
" cid='<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['cid']; ?>
' class="sid" />
    [<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['sname']; ?>
] </td>
   <td><input type="text" value="<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['name']; ?>
" cid='<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['cid']; ?>
' class="name" /></td>
   <td><input type="checkbox" class="ifok" <?php if ($this->_tpl_vars['c'][$this->_sections['i']['index']]['ifok'] == 1): ?>checked<?php endif; ?> /></td>
   <td><select class="mtype">
     
     
       <?php $_from = $this->_tpl_vars['mtype']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['j']):
?>
          
     
     <option value='<?php echo $this->_tpl_vars['j']; ?>
' <?php if ($this->_tpl_vars['j'] == $this->_tpl_vars['c'][$this->_sections['i']['index']]['mtype']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['j']; ?>
</option>
     
     
       <?php endforeach; endif; unset($_from); ?>
       
    
    </select></td>
   <td><select class="ftype">
     
     
       <?php $_from = $this->_tpl_vars['ftype']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['j']):
?>
          
     
     <option value='<?php echo $this->_tpl_vars['j']; ?>
' <?php if ($this->_tpl_vars['j'] == $this->_tpl_vars['c'][$this->_sections['i']['index']]['ftype']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['j']; ?>
</option>
     
     
       <?php endforeach; endif; unset($_from); ?>
       
    
    </select></td>
     <td><select class="dftype">
     
     
       <?php $_from = $this->_tpl_vars['dftype']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['j']):
?>
          
     
     <option value='<?php echo $this->_tpl_vars['j']; ?>
' <?php if ($this->_tpl_vars['j'] == $this->_tpl_vars['c'][$this->_sections['i']['index']]['dftype']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['j']; ?>
</option>
     
     
       <?php endforeach; endif; unset($_from); ?>
       
    
    </select></td>  
   <TD><input type="text" class='xsort' value="<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['xsort']; ?>
" /></TD>

   <td><input type="checkbox" class="xshow" <?php if ($this->_tpl_vars['c'][$this->_sections['i']['index']]['xshow'] == 1): ?>checked<?php endif; ?> /></td>
   <td><input type="checkbox" class="one" <?php if ($this->_tpl_vars['c'][$this->_sections['i']['index']]['one'] == 1): ?>checked<?php endif; ?> /></td>
   <td><input type="button" class="delone btn1 btnf" value='删除' />
    </td>
  </TR>
  <?php endfor; endif; ?>
 </table>
</div>
<div id='test'></div>
</body>
</html>