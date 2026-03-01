<?php /* Smarty version 2.6.18, created on 2026-02-14 18:03:16
         compiled from now.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script language="javascript" type="text/javascript" src="../js/My97DatePicker/WdatePicker.js"></script>
<style>
.zd th{}
input.vsmall{width:20px;padding:0px;}
.points{width:30px;}
.kpoints{width:30px;}
input.bred{background:red}
a.red{color:#D50000}
.hide{display:none}
.nowtb tr.c:hover{background:#DEEFD8}
.nowtb  {table-layout:inherit;word-wrap:break-word;word-break:break-all;font-size:12px;}
.con {width:150px;}
.s_head th{width:80px;}
td.r{text-align:left;padding-left:2px;}
.w1260{width:1260px;}
.w1560{width:1560px;}

tr.z1{background:#69F}
tr.z2{background:#6cc}
tr.z3{background:#696}
tr.z5{background:#655}
tr.he{background:#acf1a9}
</style>
</head>
<body>
<script ID="myjs" language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='now';</script>
<div class="xbody1" style="width:1260px;">
	<table class="tinfo wd100 s_head">
	<tr>
		<th>
			彩种选择
		</th>
		<td class="r">
			 <?php if ($this->_tpl_vars['gid'] == 100): ?>
			<select id='game'>
				<option value="100">香港彩</option>
			</select>
<?php else: ?>
			<select id='game'>
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
          <?php if ($this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid'] != 100): ?>
				<option value="<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
"><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</option>
           <?php endif; ?>
         <?php endfor; endif; ?>
				<option value="999" selected>全部快开</option>
			</select>
      <?php endif; ?>
		</td>
		<th>
			结算状态
		</th>
		<td class="r">
			<select id='z'>
				<option value="0" selected>未结算</option>
				<option value="1">已结算</option>
                <option value="7">无效注单</option>
				<option value="2">全部</option>
			</select>
		</td>
		<th>
			刷新
		</th>
		<td class="r">
			<select id="reloadtime">
				<option value="10">10秒</option>
				<option value="15">15秒</option>
				<option value="20">20秒</option>
				<option value="30" selected>30秒</option>
				<option value="45">45秒</option>
				<option value="60">60秒</option>
				<option value="90">90秒</option>
				<option value="120">120秒</option>
			</select>
			<label class="time"></label>秒<input class="btn1 btnf" type="button" value="刷新" id="reload"/><input class="btn1 btnf" type="button" value="暂停" id="zanting"/>
		</td>
		<th>
			每页显示
		</th>
		<td class="r">
			<select id="psize">
				<option value="50">50条</option>
				<option value="100" selected>100条</option>
				<option value="200">200条</option>
				<option value="500">500条</option>
			</select>
		</td>
	
	</tr>
	<tr>	<th>
			分类选择
		</th>
		<td class="r" >
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
			</select>
		</td>
		<th  class="hide">
			查询方式
		</th>
		<td class="r hide">
			<input type="radio" value="0" name="fs" checked/>
    按日期
			<input type="radio" value="1" name="fs"/>
    按期数&nbsp;&nbsp;
		</td>
		<th>
			日期选择
		</th>
			<td class="r" colspan="3">
			<input class='textb' id="start" value='<?php echo $this->_tpl_vars['sdate'][10]; ?>
' size='8'/>
    &nbsp;-&nbsp;
			<input class='textb' id="end" name='end' value='<?php echo $this->_tpl_vars['sdate'][10]; ?>
' size='8'/>
			<input type="button" class="s btnf" D="1" value="今天"/>
			<input type="button" class="s btnf" D="2" value="昨天"/>
			<input type="button" class="s btnf" D="3" value="本星期"/>
			<input type="button" class="s btnf" D="4" value="上星期"/>
             <input type="button" class="s btnf"  d=5 value="本月" />
    <input type="button" class="s btnf"  d=6 value="上月" />

		</td>	<th  class="hide">
			期数选择
		</th>
		<td class="r hide">
			<select class='qishu'>
				<?php $_from = $this->_tpl_vars['qishu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
				<option value="<?php echo $this->_tpl_vars['i']; ?>
"><?php echo $this->_tpl_vars['i']; ?>
期</option>
     <?php endforeach; endif; unset($_from); ?>
			</select>
		</td>	<th>
			详细信息
		</th>
        <td>
        <input type="checkbox" value="1" name="xinfo" />
        </td>
	
	</tr>
	<tr>
		<th>
			会员选择
		</th>
		<td colspan="9" class="r">
			<input type="hidden" value="" id='saveuserid' LAYER="<?php echo $this->_tpl_vars['layer']; ?>
"/>
			<input type="hidden" id="page" value="1"/>
			<input type="hidden" id='topid' value="<?php echo $this->_tpl_vars['topid']; ?>
" LAYER="<?php echo $this->_tpl_vars['layer']; ?>
" username='<?php echo $this->_tpl_vars['username']; ?>
'/>
			<select class='user' LAYER="<?php echo $this->_tpl_vars['layer']+1; ?>
">
				<option value="">选择公司</option>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['topuser']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<option value="<?php echo $this->_tpl_vars['topuser'][$this->_sections['i']['index']]['userid']; ?>
" wid='<?php echo $this->_tpl_vars['topuser'][$this->_sections['i']['index']]['wid']; ?>
'><?php echo $this->_tpl_vars['topuser'][$this->_sections['i']['index']]['username']; ?>
</option>
<?php endfor; endif; ?>
			</select>
		</td>
          <td colspan="2" class="hide"> <input class="btn1 btnf query" type="button" value="注单查询"  style="margin:1px;" /></td>
	</tr>
	</table>
    <input type="hidden" class='sort' orderby='time' sorttype='DESC' page='1' xtype='2' con='' />
	<table class="einfo nowtb wd100" style='margin-top:10px;background:#fff;'>
	<tr class="bt">
<th><input type="checkbox" class="clickall" /></th>
  <th>操作</th>
		<th>
			彩种
		</th>
		<th>
			期数
		</th>
		<th>
			交易号
		</th>
		<th>
			状态
		</th>
		<th>
			<select class='xtype'><option value='3' selected>全部</option><option value='0'>下注</option><option value='1'>内补</option><option value='2'>外补</option></select>
		</th>
		<th>
			类别
		</th>
		<th>
			大盘
		</th>
		<th>
			小盘
		</th>
		<th>
			内容
		</th>
		<th>
			<a href='javascript:void(0)' class='je'>金额<img src="<?php echo $this->_tpl_vars['globalpath']; ?>
img/down.gif" s='up' /></a>
		</th>
		<th>
			赔率
		</th>
		<th>
			退水%
		</th>
		<th>
			会员
		</th>
		<th>
			<a href='javascript:void(0)' class="time">时间<img src="<?php echo $this->_tpl_vars['globalpath']; ?>
img/down.gif" s='up' /></a>
		</th>
	</tr>
	</table>
</div>
<div id='test'>
</div>
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
layernames['w<?php echo $this->_tpl_vars['layername'][$this->_sections['i']['index']]['wid']; ?>
'] = [];
<?php $_from = $this->_tpl_vars['layername'][$this->_sections['i']['index']]['layer']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['j']):
?>
layernames['w<?php echo $this->_tpl_vars['layername'][$this->_sections['i']['index']]['wid']; ?>
'][<?php echo $this->_tpl_vars['key']; ?>
] = '<?php echo $this->_tpl_vars['j']; ?>
';
<?php endforeach; endif; unset($_from); ?>
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