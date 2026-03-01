<?php /* Smarty version 2.6.18, created on 2026-02-14 18:03:19
         compiled from xxtz.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script language="javascript" type="text/javascript" src="../js/My97DatePicker/WdatePicker.js"></script>
<style>
.zd th {
}
input.vsmall {
	width:20px;
	padding:0px;
}
.points {
	width:30px;
}
.kpoints {
	width:30px;
}
input.bred {
	background:red
}
a.red {
	color:#D50000
}
.nowtb td {
	font-size:10px;
}
.nowtb th {
	font-size:10px;
}
.nowtb tr:hover {
	background:#FCF
}
.user tr:hover {
	background:#FCF
}
select {
	width:120px;
}
.qishu{width:210px;}
.s_head .r{text-align:left;padding-left:10px;}

</style>
</head><body>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='xxtz';</script>
<div class="xbody1" style="width:98%;">
 <table class="tinfo wd100 s_head" style="width:100%;">
   <tr>
   <th style="width:150px;" >查询方式</th>
   <td class="r"  colspan="2">
    <input type="radio" value="0" name="fs"  checked />
    按日期
    <input type="radio" value="1" name="fs" />
    按期数&nbsp;&nbsp;</td>
  </tr>
  <TR>
   <th >期数选择</th>
   <Td style="text-align:left;padding-left:10px;" colspan="2"> <select class=qishu>
     
        <?php $_from = $this->_tpl_vars['qishu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
     <option value="<?php echo $this->_tpl_vars['i']; ?>
"><?php echo $this->_tpl_vars['i']; ?>
期</option>
     <?php endforeach; endif; unset($_from); ?>
      
    </select>
    
      



   </Td>
  </TR>
  <tr>
   <th>日期选择</th>
   <td class="r"  colspan="2"><input class='textb' id="start"  value='<?php echo $this->_tpl_vars['sdate'][10]; ?>
' size='11' />
    &nbsp;—&nbsp;
    <input class='textb' id="end" name='end'  value='<?php echo $this->_tpl_vars['sdate'][10]; ?>
' size='11' />
    <input type="button" class="s btnf"  d=1 value="今天" />
    <input type="button" class="s btnf"  d=2 value="昨天" />
    <input type="button" class="s btnf"  d=3 value="本星期" />
    <input type="button" class="s btnf"  d=4 value="上星期" />
    <input type="button" class="s btnf"  d=5 value="本月" />
    <input type="button" class="s btnf"  d=6 value="上月" /></td>
  </tr>

  <tr>
  <th>分类选择</th><td class="r" colspan="2">
    <select class='bid'>
     <option value="">全部</option>
     
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
    <select class='sid'>
    </select>
    <select class='cid'>
    </select></td>
  </tr>
  <tr><th></th><td  colspan="2" class="r">    <input class="btn1 btnf query" type="button" value="明细查询"  style="margin:1px;" />
    <input class="btn1 btnf winprint" type="button" value="打印"  style="margin:1px;" />
    <input type="hidden" value="<?php echo $this->_tpl_vars['topid']; ?>
" id='saveuserid' LAYER="<?php echo $this->_tpl_vars['layer']; ?>
"  />
    <input type="hidden" id=page value="1" />
    <input type="hidden" id='topid' value="<?php echo $this->_tpl_vars['topid']; ?>
" LAYER="<?php echo $this->_tpl_vars['layer']; ?>
" username='<?php echo $this->_tpl_vars['username']; ?>
' /></td></tr>
  <tr>
  <TD></TD>
   <td style="text-align:left;padding-left:10px;"> 当前用户：
    <label class="nowuser" uid='<?php echo $this->_tpl_vars['topid']; ?>
' LAYER="<?php echo $this->_tpl_vars['layer']; ?>
"><?php echo $this->_tpl_vars['username']; ?>
</label></td>
   <td style="text-align:left;padding-left:10px;"><font color="blue">[返回上线]</font>：<a href='javascript:void(0)'>
    <label class="upuser" uid='<?php echo $this->_tpl_vars['topid']; ?>
' LAYER="<?php echo $this->_tpl_vars['layer']; ?>
"></label>
    </a></td>
  </TR>
 </table>
 <table class="tinfo user wd100" style="margin-top:10px;margin-bottom:10px;width:100%;">
 </table>
 <table class="einfo nowtb wd100" style='margin-top:10px;background:#fff;width:100%;'>
  <tr class="bt">
   <th>期数</th>
   <th>交易号</th>
   <th>类型</th>
   <th>类别</th>
   <th>大盘</th>
   <th>小盘</th>
   <th>内容</th>
   <th>金额</th>
   <th>赔率</th>
   <th>退水%</th>
   <th>会员</th>
   <th>时间</th>
  </tr>
 </table>
</div>
<div id='test' style="margin-top:0px;"></div>
<script language="javascript">
layernames= new Array();
layername = new Array();
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['layername']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
layernames[<?php echo $this->_sections['i']['index']; ?>
] = new Array();
layernames[<?php echo $this->_sections['i']['index']; ?>
]['wid'] = <?php echo $this->_tpl_vars['layername'][$this->_sections['i']['index']]['wid']; ?>
;
layernames[<?php echo $this->_sections['i']['index']; ?>
]['layer'] = new Array();
<?php $_from = $this->_tpl_vars['layername'][$this->_sections['i']['index']]['layer']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['j']):
?>
layernames[<?php echo $this->_sections['i']['index']; ?>
]['layer'][<?php echo $this->_tpl_vars['key']; ?>
] = '<?php echo $this->_tpl_vars['j']; ?>
';
<?php endforeach; endif; unset($_from); ?>
layernames[<?php echo $this->_sections['i']['index']; ?>
]['namehead'] = '<?php echo $this->_tpl_vars['layername'][$this->_sections['i']['index']]['namehead']; ?>
';
<?php endfor; endif; ?>
<?php $_from = $this->_tpl_vars['layername'][0]['layer']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['j']):
?>
layername[<?php echo $this->_tpl_vars['key']; ?>
] = '<?php echo $this->_tpl_vars['j']; ?>
';
<?php endforeach; endif; unset($_from); ?>
var maxlayer= layername.length;
var layer =<?php echo $this->_tpl_vars['layer']; ?>
 ;
sdate=new Array();
<?php $_from = $this->_tpl_vars['sdate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['i']):
?>
sdate[<?php echo $this->_tpl_vars['key']; ?>
]="<?php echo $this->_tpl_vars['i']; ?>
";
<?php endforeach; endif; unset($_from); ?>
</script>
</body>
</html>