<?php /* Smarty version 2.6.18, created on 2026-02-12 17:28:00
         compiled from play.html */ ?>
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
td{font-size:11px;}
</style>
</head><body>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='play';</script>
<div class="xbody1" style="width:1200px;">
 <table class="tinfo wd100 s_tb">
  <tr>
   <th colspan="17">名称：
    <input type="text" id='name' />
    <select id="bid" >
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
' ><?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
</option>
     
       <?php endfor; endif; ?>
       
    </select>
    
    <select id="sid" >
     <option value="">小分类</option>
       
    </select>
    
    <select id="cid">
     <option value="">分类</option>
    </select>
    赔率1：
    <input type="text" value="" id='peilv1' />
    赔率2：
    <input type="text" value="" id='peilv2' />
    <select id='ztype'>
     <option value="">中奖类型</option>
     
       <?php $_from = $this->_tpl_vars['ztype']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
       
     <option value="<?php echo $this->_tpl_vars['i']; ?>
" ><?php echo $this->_tpl_vars['i']; ?>
</option>
     
       <?php endforeach; endif; unset($_from); ?>
       
    </select>
    中奖个数1：
    <input type="text" value="" id='znum1' />
    中奖个数2：
    <input type="text" value="" id='znum2' />
    <input type="button" class="btn3 btnf" id='add'  value="添加玩法" /></th>
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
       
    </select></th>
   <th><select class="sid" >
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
       
    </select></th>
   <th><select class="cid" >
     <option value="">分类</option>
     
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
          
     <option value='<?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['cid']; ?>
' <?php if ($this->_tpl_vars['c'][$this->_sections['i']['index']]['cid'] == $this->_tpl_vars['cid']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['c'][$this->_sections['i']['index']]['name']; ?>
</option>
     
       <?php endfor; endif; ?>
       
    </select></th>
   <th>名称</th>
   <th>可用</th>
   <th>赔率1</th>

   <th>赔率2</th>
   <th>中奖类型</th>
   <th>中奖个数1</th>
   <th>中奖个数2</th>
   <th>排序</th>
   <th>赔率类型</th>
   <th><input type="button" class="btn3 btnf" id='delall'  value="删除选中" /><input type="button" class="btn3 btnf" id='edit'  value="修改选中" /></th>
  </TR>
  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['p']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
   <td><input type="checkbox" value='<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['pid']; ?>
' /></td>
   <td><?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['pid']; ?>
</td>
   <td bid='<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['bid']; ?>
'><input type="text" value="<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['bid']; ?>
" class="bids" /><Br /><?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['bname']; ?>
</td>
   <td sid='<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['sid']; ?>
'><input type="text" value="<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['sid']; ?>
" class="sids" /><Br /><?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['sname']; ?>
</td>
   <td cid='<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['cid']; ?>
'><input type="text" value="<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['cid']; ?>
" class="cids" /><Br /><?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['cname']; ?>
</td>
   <td><input type="text" value="<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['name']; ?>
" class="names" /></td>
   <td><input type="checkbox" class="ifok" value="1" <?php if ($this->_tpl_vars['p'][$this->_sections['i']['index']]['ifok'] == 1): ?>checked<?php endif; ?> /></td>
   <td><input type="text" value="<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['peilv1']; ?>
" class="peilv1" /></td>
   <td><input type="text" value="<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['peilv2']; ?>
" class="peilv2" /></td>
   <td><select class="ztype">
     
       <?php $_from = $this->_tpl_vars['ztype']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['j']):
?>
          
     <option value='<?php echo $this->_tpl_vars['j']; ?>
' <?php if ($this->_tpl_vars['j'] == $this->_tpl_vars['p'][$this->_sections['i']['index']]['ztype']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['j']; ?>
</option>
     
       <?php endforeach; endif; unset($_from); ?>
       
    </select></td>
   <td><input type="text" value="<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['znum1']; ?>
" class="znum1" /></td>
   <td><input type="text" value="<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['znum2']; ?>
" class="znum2" /></td>
   <TD><input type="text" class='xsort' value="<?php echo $this->_tpl_vars['p'][$this->_sections['i']['index']]['xsort']; ?>
" /></TD>
   <td><select class="ptype">
     
       <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['ptype']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
          
     <option value='<?php echo $this->_tpl_vars['ptype'][$this->_sections['j']['index']]['id']; ?>
' <?php if (( $this->_tpl_vars['ptype'][$this->_sections['j']['index']]['id'] == $this->_tpl_vars['p'][$this->_sections['i']['index']]['ptype'] )): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['ptype'][$this->_sections['j']['index']]['c']; ?>
</option>
     
       <?php endfor; endif; ?>
       
    </select></td>
   <td><input type="button" class="delone btn1 btnf" value='删除' />
    </td>
  </TR>
  <?php endfor; endif; ?>
 </table>
</div>
<div id='test'></div>
</body>
</html>