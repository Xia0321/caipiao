<?php /* Smarty version 2.6.18, created on 2026-02-10 05:16:57
         compiled from slibnew.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link href="/css/default/control.css" rel="stylesheet" type="text/css" />
<link href="/css/default/ball.css" rel="stylesheet" type="text/css" />
<link href="/css/default/betslib.css" rel="stylesheet" type="text/css" />
<!--<link href="/css/default/uitz.css" rel="stylesheet" type="text/css" />-->
<style type="text/css">
.flys{background:yellow}
.black{color:#000}
.red{color:Red !important}
.lv{color:green !important} 
.warn{background:#F2F2F2}
.now th.bred{background:#0284c3;color:#eee}
.now td.bover{}
input.small{width:45px;}
td.byellow{background:#FC3}
td.bc{background:#DDE1FD}
.onepeilvtb{position:absolute;display:none;background:#fff;width:180px;}
.xxtb{position:absolute;display:none;background:#fff;width:1000px;}
.flytb{position:absolute;display:none;background:#fff;width:1000px;}
.sendtb td{text-align:center}
.atts{font-weight:normal;cursor: pointer;}
.downs{margin-right: 5px;color: red;}
.ups{margin-left:5px;color: green;}
</style>
</head><body class="skin_blue">
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';
var js=1;var sss='slibnew';
function json_encode_js(aaa) {
	var i, s, a, aa = [];
	if (typeof(aaa) != "object") {
		alert("ERROR json");
		return
	}
	for (i in aaa) {
		s = aaa[i];
		a = '"' + i + '":';
		if (typeof(s) == 'object') {
			a += json_encode_js(s)
		} else {
			a += '"' + s + '"'
		}
		aa[aa.length] = a
	}
	return "{" + aa.join(",") + "}"
}
function getResult(num, n) {
	return Math.round(num * Math.pow(10, n)) / Math.pow(10, n)
}
function getresult(num, n) {
	return num.toString().replace(new RegExp("^(\\-?\\d*\\.?\\d{0," + n + "})(\\d*)$"), "$1") + 0
}
function strlen(sString) {
	var sStr, iCount, i, strTemp;
	iCount = 0;
	sStr = sString.split("");
	for (i = 0; i < sStr.length; i++) {
		strTemp = escape(sStr[i]);
		if (strTemp.indexOf("%u", 0) == -1) {
			iCount = iCount + 1
		} else {
			iCount = iCount + 2
		}
	}
	return iCount
}
function rhtml(str) {
	return str.match(/<a\b[^>]*>[\s\S]*?<\/a>/ig)
}
</script>

<div class="main">
<div class="top_info">
<span id="drawNumber" class="title">&nbsp;</span>
<div class="op">
<select id='qishu'><?php $_from = $this->_tpl_vars['qishu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?><option value="<?php echo $this->_tpl_vars['i']; ?>
"><?php echo $this->_tpl_vars['i']; ?>
期</option><?php endforeach; endif; unset($_from); ?></select>
<input type="button" class="btn1 btnf" value="刷新" id='reload' />
<label class="time" style='color:red'></label>
<span>秒</span><input type="button" class="btn3 btnf" value="暂停" id='zanting' />
<select id='reloadtime'>
     <option value="10">10秒</option>
     <option value="15">15秒</option>
     <option value="20">20秒</option>
     <option value="30">30秒</option>
     <option value="45">45秒</option>
     <option value="60" selected>60秒</option>
    </select>
 <label class="bresult">今日输赢：<span id="bresult"><?php echo $this->_tpl_vars['jrsy']; ?>
</span></label>
<select id="abcd">
<option value="">全部</option>
<option value="A">A盘</option><option value="B">B盘</option><option value="C">C盘</option><option value="D">D盘</option></select>
    <select id=ab  style="display:none">
     <option value="0">AB盤</option>
     <option value="A">A</option>
     <option value="B">B</option>
    </select>
    <select id='xsort'>
     <option value="ks" selected>按盈亏排</option>
     <option value="zje">按虚货排</option>
     <option value="zc">按实货排</option>
     <option value="zs">按注数</option>
    </select>
    <select id='userid' layer='<?php echo $this->_tpl_vars['layer']; ?>
'  style="display:none">
     <option value="">選擇下線</option>
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
"><?php echo $this->_tpl_vars['topuser'][$this->_sections['i']['index']]['username']; ?>
</option>
<?php endfor; endif; ?>
</select>
<select id="amountMode" class='libstyle'>
<option value="0" v='zc'>实占</option>
<option value="1" v='zje'>虚注</option>
<option value="2" v='fly'>补货</option>
</select>

    <input type="button" class="btn3 btnf hide" value="赔率設置" id='pset' />
<span>显示:</span>
<select class="plmode">
<option value="1" selected>当前赔率</option>
<option value="4">默认赔率</option>
</select>
    <input type="button" class="btn3 btnf moren" value="写入默認"  ac='writemoren' />
    <input type="button" class="btn3 btnf moren" value="恢复默認"  ac='resetmoren' />
   
<select id="selectitem">
<option value="">选择类型</option>
<option value="全部">全部</option>
<option value="数字">数字</option>
<option value="双面">双面</option>
<?php if ($this->_tpl_vars['fenlei'] == 103): ?>
<option value="四季">四季</option>
<option value="五行">四季</option>
<option value="方位">方位</option>
<option value="中发白">中发白</option>
<?php endif; ?>
</select>
     <select id='psettype'  style="display: none;">
     <option value="0">減</option>
     <option value="1">加</option>
    </select>
    <input type="number" style="width:60px;" value="0.1" id='psetattvalue' />
    <input type="button" class="btn1 btnf" value="加/減" id='psetatt'  style="display: none;" />
    <input type="button" class="btn1 btnf" value="送出" id='psetsend' />
    <input type="button" class="btn1 btnf" value="提交" id='psetpost' />
    <input type="button" class="btn3 btnf" value="取消" id='psetcancel' />
           
 
    
   
     <input type="button" class="btnf"  value="设置所有双面" id='pism' />
  
       <input type="button" class="btnf"  value="同步<?php echo $this->_tpl_vars['flname']; ?>
" id='yiwotongbu' />
       
    
<input type="button" class="btn1 downfast btnf" value="下载未结算"  />


</div>
<div class="right">

</div>
</div>

<div id="totals"><table class="data_table now"><thead><tr>
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
   <th bid='<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
'  class="n<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
"><?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
</th>
<?php endfor; endif; ?> 
</tr> </thead><tbody>
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
   <td bid='<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
' bname='<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
' class="n nx<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
"></td>
<?php endfor; endif; ?> 
</tbody></table></div>

<div id="main" class="contents">
<table class="layout list lib T<?php echo $this->_tpl_vars['fenlei']; ?>
">
<tbody></tbody>
</table>

</td>
</tr>
</tbody></table></div>

<div class="control data_footer input_panel">
<span id="backControl" ><!--<span>快速补货：</span><input type="text" class="input">    <label><input type="radio" name="backAmountType" value="0" checked="checked">平均盈亏</label>
    <label><input type="radio" name="backAmountType" value="1">占成金额</label>

<input type="button" class="calc button" value="计算补货"> -->

         <select id='fly' class='hide'><option value="2">外补</option></select><input type="button" class='button hide' id='pfly' value="批量补货" />
 </span>
</div>
</div>

		<table class="xxs data_table list xxtb">
		<thead>
		<tr>
			<th>
				注单号
			</th>
			<th>
            <a href="javascript:void(0);" class="time">投注时间<img src="<?php echo $this->_tpl_vars['globalpath']; ?>
img/down.gif" s='down' /></a>				
			</th>
			<th>
				种类
			</th>
            <th> 
               类型
            </th>
			<th>
				账号
			</th>
			<th>
				投注内容
			</th>
			<th>
				<a href="javascript:void(0);" class="je">金額<img src="<?php echo $this->_tpl_vars['globalpath']; ?>
img/down.gif" s='up' /></a>
			</th>
			<th>
				退水(%)
			</th>
     		<th>
				本级占成
			</th>
			<th>
				占成明细
			</th>
		</tr>
		</thead>
        <tbody>
        </tbody>

		</table>



		<table class="xxs data_table list flytb">
		<thead>
		<tr>
			<th>
				注单号
			</th>
			<th>
            <a href="javascript:void(0);" class="time">投注时间<img src="<?php echo $this->_tpl_vars['globalpath']; ?>
img/down.gif" s='down' /></a>				
			</th>
			<th>
				投注种类
			</th>
			<th>
				账号
			</th>
			<th>
				投注内容
			</th>
			<th>
				<a href="javascript:void(0);" class="je">金額<img src="<?php echo $this->_tpl_vars['globalpath']; ?>
img/down.gif" s='up' /></a>
			</th>
			<th>
				退水(%)
			</th>
		</tr>
		</thead>
        <tbody>
        </tbody>
		</table>


<table class="data_table onepeilvtb T<?php echo $this->_tpl_vars['fenlei']; ?>
 mms">
<thead><th colspan="2">赔率设置</th></thead>
 <tr>
  <th>項目</th>
  <TD></td>
 </tr>
 <TR>
  <th>赔率</th>
  <TD><input type='text' class="txt1" style="width:50px;"  /></TD>
 </TR>
 <TR>
  <td colspan="2" align="center"><input type="button" class="btn1 btnf onesend"  value='提交'  />
   <input type="button"  value='关闭' class="btn1 btnf oneclose" style="margin-left:10px;" /></td>
 </TR>
</table>

<table class="tinfo sendtb">

</table>
<input type="hidden" class='sort' orderby='time' sorttype='DESC' page='1' xtype='5' con='' />

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable mx" tabindex="-1" role="dialog" aria-describedby="shares" aria-labelledby="ui-id-1" style="position: absolute; height: 152.28px; width: 360px; top: 257px; left: 564.5px; display:none; right: auto; bottom: auto;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">2988316036# 占成明细</span><button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close"><span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">Close</span></button></div><div id="shares" class="popdiv ui-dialog-content ui-widget-content" style="display: block; width: auto; min-height: 96px; max-height: 346px; height: auto;"><table class="data_table">
<thead><tr><th>类型</th><th>账号</th><th>占成</th><th>退水</th><th>赔率</th></tr></thead><tbody></tbody>
</table></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>




<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable sendtb" tabindex="-1"  style="display:none;position:absolute; height: auto; width: 600px; top: 100px; left: 300px;  z-index: 101;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">补货明细（请确认注单）</span><button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close"><span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">Close</span></button></div><div id="betsBox" class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 0px; max-height: none; height: auto;"><div class="betList"><table class="data_table list"><thead><tr><th>序号</th><th>号码</th><th>赔率</th><th class='tpoints'>退水</th><th>金额</th><th>删除</th></tr></thead><tbody id="betlist"></tbody></table></div><div class="bottom"><span id="bcount"></span><span id="btotal"></span></div><div><label class='plts'><input style="display:none;" type="checkbox" id="ignoreOdds">如赔率变化，按最新赔率投注，成功后提示赔率变化</label><label style="display:none;" class='cgts red'>请[点击左上角]或[按回车键]关闭本窗口中,5秒后自动关闭本窗口!</label></div></div><div class="ui-dialog-buttonset" style="text-align: center"><button type="button" class="ui-button qr" ><span class="ui-button-text">确定</span></button><button type="button" class="ui-button close" role="button"><span class="ui-button-text">取消</span></button></div></div>

<div class="ui-widget-overlay ui-front ui-fronts" style="z-index: 100;display:none;"></div>
<iframe id="downfastfrm" style="display:none;" ></iframe>
<input id='test2' type="text" class="hide"  />
<div id='test'></div>

<script language="javascript">
layername= new Array();
<?php $_from = $this->_tpl_vars['layername']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['i']):
?>
layername[<?php echo $this->_tpl_vars['key']; ?>
]="<?php echo $this->_tpl_vars['i']; ?>
";
<?php endforeach; endif; unset($_from); ?>
var maxlayer = layername.length;
var layer =<?php echo $this->_tpl_vars['layer']; ?>
 ;
var pself = 0;
var ifexe = 0;
<?php if ($this->_tpl_vars['layer'] == 1): ?>
ifexe =<?php echo $this->_tpl_vars['ifexe']; ?>
 ;
pself =<?php echo $this->_tpl_vars['pself']; ?>
 ;
<?php endif; ?>
var style = '<?php echo $this->_tpl_vars['class']; ?>
';
var ngid= <?php echo $this->_tpl_vars['gid']; ?>
;
var fenlei= <?php echo $this->_tpl_vars['fenlei']; ?>
;
sma =new Array();
sma['g101'] = new Array();
sma['g101'][0] = new Array();
sma['g101'][0]['name'] = "单";
sma['g101'][0]['ma'] = new Array(1,3,5,7,9);
sma['g101'][1] = new Array();
sma['g101'][1]['name'] = "双";
sma['g101'][1]['ma'] = new Array(0,2,4,6,8);
sma['g101'][2] = new Array();
sma['g101'][2]['name'] = "大";
sma['g101'][2]['ma'] = new Array(5,6,7,8,9);
sma['g101'][3] = new Array();
sma['g101'][3]['name'] = "小";
sma['g101'][3]['ma'] = new Array(0,1,2,3,4);
sma['g101'][4] = new Array();
sma['g101'][4]['name'] = "质";
sma['g101'][4]['ma'] = new Array(1,2,3,5,7);
sma['g101'][5] = new Array();
sma['g101'][5]['name'] = "合";
sma['g101'][5]['ma'] = new Array(0,2,4,6,7);
sma['g103'] = new Array();
sma['g103'][0] = new Array();
sma['g103'][0]['name'] = "单";
sma['g103'][0]['ma'] = new Array(1,3,5,7,9,11,13,15,17,19);
sma['g103'][1] = new Array();
sma['g103'][1]['name'] = "双";
sma['g103'][1]['ma'] = new Array(2,4,6,8,10,12,14,16,18,20);
sma['g103'][2] = new Array();
sma['g103'][2]['name'] = "大";
sma['g103'][2]['ma'] = new Array(11,12,13,14,15,16,17,18,19,20);
sma['g103'][3] = new Array();
sma['g103'][3]['name'] = "小";
sma['g103'][3]['ma'] = new Array(1,2,3,4,5,6,7,8,9,10);
sma['g103'][4] = new Array();
sma['g103'][4]['name'] = "合单";
sma['g103'][4]['ma'] = new Array(1,3,5,7,9,10,12,14,16,18);
sma['g103'][5] = new Array();
sma['g103'][5]['name'] = "合双";
sma['g103'][5]['ma'] = new Array(2,4,6,8,11,13,15,17,19,20);
sma['g103'][6] = new Array();
sma['g103'][6]['name'] = "尾大";
sma['g103'][6]['ma'] = new Array(5,6,7,8,9,15,16,17,18,19);
sma['g103'][7] = new Array();
sma['g103'][7]['name'] = "尾小";
sma['g103'][7]['ma'] = new Array(1,2,3,4,10,11,12,13,14,20);
sma['g103'][8] = new Array();
sma['g103'][8]['name'] = "春";
sma['g103'][8]['ma'] = new Array(1,2,3,4,5);
sma['g103'][9] = new Array();
sma['g103'][9]['name'] = "夏";
sma['g103'][9]['ma'] = new Array(6,7,8,9,10);
sma['g103'][10] = new Array();
sma['g103'][10]['name'] = "秋";
sma['g103'][10]['ma'] = new Array(11,12,13,14,15);
sma['g103'][11] = new Array();
sma['g103'][11]['name'] = "冬";
sma['g103'][11]['ma'] = new Array(16,17,18,19,20);
sma['g103'][12] = new Array();
sma['g103'][12]['name'] = "金";
sma['g103'][12]['ma'] = new Array(1,6,11,16);
sma['g103'][13] = new Array();
sma['g103'][13]['name'] = "木";
sma['g103'][13]['ma'] = new Array(2,7,12,17);
sma['g103'][14] = new Array();
sma['g103'][14]['name'] = "水";
sma['g103'][14]['ma'] = new Array(3,8,13,18);
sma['g103'][15] = new Array();
sma['g103'][15]['name'] = "火";
sma['g103'][15]['ma'] = new Array(4,9,14,19);
sma['g103'][16] = new Array();
sma['g103'][16]['name'] = "土";
sma['g103'][16]['ma'] = new Array(5,10,15,20);

sma['g103'][17] = new Array();
sma['g103'][17]['name'] = "东";
sma['g103'][17]['ma'] = new Array(1,5,9,13,17);
sma['g103'][18] = new Array();
sma['g103'][18]['name'] = "南";
sma['g103'][18]['ma'] = new Array(2,6,10,14,18);
sma['g103'][19] = new Array();
sma['g103'][19]['name'] = "西";
sma['g103'][19]['ma'] = new Array(3,7,11,15,19);
sma['g103'][20] = new Array();
sma['g103'][20]['name'] = "北";
sma['g103'][20]['ma'] = new Array(4,8,12,16,20);

sma['g103'][21] = new Array();
sma['g103'][21]['name'] = "中";
sma['g103'][21]['ma'] = new Array(1,2,3,4,5,6,7);
sma['g103'][22] = new Array();
sma['g103'][22]['name'] = "发";
sma['g103'][22]['ma'] = new Array(8,9,10,11,12,13,14);
sma['g103'][23] = new Array();
sma['g103'][23]['name'] = "白";
sma['g103'][23]['ma'] = new Array(15,16,17,18,19,20);

sma['g105'] = new Array();
sma['g105'][0] = new Array();
sma['g105'][0]['name'] = "单";
sma['g105'][0]['ma'] = new Array(1,3,5,7,9);
sma['g105'][1] = new Array();
sma['g105'][1]['name'] = "双";
sma['g105'][1]['ma'] = new Array(2,4,6,8,10);
sma['g105'][2] = new Array();
sma['g105'][2]['name'] = "大";
sma['g105'][2]['ma'] = new Array(6,7,8,9,10);
sma['g105'][3] = new Array();
sma['g105'][3]['name'] = "小";
sma['g105'][3]['ma'] = new Array(1,2,3,4,5);

sma['g107'] = new Array();
sma['g107'][0] = new Array();
sma['g107'][0]['name'] = "单";
sma['g107'][0]['ma'] = new Array(1,3,5,7,9);
sma['g107'][1] = new Array();
sma['g107'][1]['name'] = "双";
sma['g107'][1]['ma'] = new Array(2,4,6,8,10);
sma['g107'][2] = new Array();
sma['g107'][2]['name'] = "大";
sma['g107'][2]['ma'] = new Array(6,7,8,9,10);
sma['g107'][3] = new Array();
sma['g107'][3]['name'] = "小";
sma['g107'][3]['ma'] = new Array(1,2,3,4,5);
sma['g107'][4] = new Array();
sma['g107'][4]['name'] = "质";
sma['g107'][4]['ma'] = new Array(1,2,3,5,7);
sma['g107'][5] = new Array();
sma['g107'][5]['name'] = "合";
sma['g107'][5]['ma'] = new Array(4,6,8,9,10);

</script>
<iframe name=sfrm id=sfrm style="display:none;"  ></iframe>
</body>
</html>