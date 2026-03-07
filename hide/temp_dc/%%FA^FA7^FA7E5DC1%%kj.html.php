<?php /* Smarty version 2.6.18, created on 2026-02-10 05:17:11
         compiled from kj.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link href="/css/default/control.css" rel="stylesheet" type="text/css" />
<link href="/css/default/ball.css" rel="stylesheet" type="text/css" />
<link href="/css/default/betslib.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="../js/My97DatePicker/WdatePicker.js"></script>

<style>
.top_tb th{width:130px;cursor:pointer}
.top_tb th,.top_tb td{height:30px;line-height:30px;text-align:left;}

.top_tb td{padding-left:10px;text-align:left;}
.top_tb th{padding-left:10px;text-align:left;}

.kjtb{margin-bottom:10px;}
.small{width:40px;}
.kjtb img{clear:both}
.txt1{width:30px;}

.txt2{width:85px;}
.txt3{width:105px;}
.txt31{width:145px;}

.txt5{width:80px;}
.txt6{width:60px;}
.wd100{margin-bottom:10px;}

.cmd input{margin-left:5px;}
.cmd select{margin-left:5px;}

.kjjr .ma{font-weight:bold;font-size:16px;}

.editkj{position:absolute;width:1260px;background:#fff;border:2px solid #000;display:none}
.cmd .textb{width:65px;}
body{font-size:11px;}
.xbody1{width:1260px;}
.qiua{margin:0px;float:left;width:26px;height:26px;background:url(../imgn/ball_blue1.png);line-height:26px;text-align:center;margin-right:2px;font-weight:bold}
.qiub{margin:0px;float:left;width:26px;height:26px;background:url(../imgn/ball_red1.png);line-height:26px;text-align:center;margin-right:2px;font-weight:bold}
.pikj{position:absolute;width:630px;background:#fff;border:2px solid #000;display:none;z-index:100}

.xxtb{width:1100px;position:absolute;background:#fff;display:none;border:2px solid #000}
.zes{cursor:pointer; text-decoration:underline;color:#D50000}

	.hm img{margin-right: 5px;}
tr.z1{background:#69F}
a.red{color:red}
.btnf{margin-right:5px;}
	.chu{color:red;font-weight: bold}
	.data_table{margin-bottom:5px;}
	label{color:red}	
</style>
</head>
<body  class="skin_blue">

<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='kj';</script>
<div class="main">
	<div class="top_info">
		<span class="title">开奖管理</span><span class="right"></span>
	</div>
 <table class="data_table">
   <thead>
    <tr>
      <th >彩种选择</th><th>当前期</th><th>自动开奖</th><th>自动开关盘</th><th>特码状态</th><th>正码状态</th>
      </tr></thead>
      <tr> <td><select class="game">
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
" fast='<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['fast']; ?>
'<?php if ($this->_tpl_vars['game'][$this->_sections['i']['index']]['gid'] == $this->_tpl_vars['game'][0]['gid']) { ?> selected="selected"<?php } ?>><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</option>
         <?php endfor; endif; ?>
       </select></td>  <td><label><?php echo $this->_tpl_vars['game'][0]['thisqishu']; ?>
</label></td> <td><img  src='<?php echo $this->_tpl_vars['globalpath']; ?>
img/<?php echo $this->_tpl_vars['game'][0]['autokj']; ?>
.gif'  id="autokj"  class="status"  /></td><td><img src='<?php echo $this->_tpl_vars['globalpath']; ?>
img/<?php echo $this->_tpl_vars['game'][0]['autoopenpan']; ?>
.gif' id="autoopenpan" class="status"  /></td><td><img  src='<?php echo $this->_tpl_vars['globalpath']; ?>
img/<?php echo $this->_tpl_vars['game'][0]['panstatus']; ?>
.gif'  class="status" id="panstatus" /></td><td><img  src='<?php echo $this->_tpl_vars['globalpath']; ?>
img/<?php echo $this->_tpl_vars['game'][0]['otherstatus']; ?>
.gif'  class="status"  id="otherstatus" /></td> </tr>
    </tr>  
 </table>

<?php if ($this->_tpl_vars['game'][0]['guanfang'] == 1): ?>
 <table class="data_table">
<thead>
      <th >自动开奖设置</th><th>控制方式</th><th>开奖模式(庄)</th><th>开始控制金额</th><th>随机数[100-500]</th>
      <th style="display: none;">指定会员输赢</th><th>占成模式</th><th>盈利上限</th>
      <th>
  操作
     </th>
      </tr>
    </thead>
      <tr> 
          <td><?php echo $this->_tpl_vars['game'][0]['gname']; ?>
</td>
          <td>
            <input type="radio"  name='cjmode' value="0" <?php if ($this->_tpl_vars['cs']['cjmode'] == 0): ?>checked<?php endif; ?> />采集官方&nbsp;
            <input type="radio"  name='cjmode' value="1" <?php if ($this->_tpl_vars['cs']['cjmode'] == 1): ?>checked<?php endif; ?> />系统开奖
          </td>
          <td>
            <div style="float: left;width:10%">
            <input type="radio"  name='xtmode' value="0" <?php if ($this->_tpl_vars['cs']['xtmode'] == 0): ?>checked<?php endif; ?> />随机开
            </div>
            <div style="float: left;color: red;width:30%">
            <input type="radio"  name='xtmode' value="3" <?php if ($this->_tpl_vars['cs']['xtmode'] == 3): ?>checked<?php endif; ?> />随机赢&nbsp;
            <input type="radio"  name='xtmode' value="2" <?php if ($this->_tpl_vars['cs']['xtmode'] == 2): ?>checked<?php endif; ?> />赢最多&nbsp;
            <input type="radio"  name='xtmode' value="1" <?php if ($this->_tpl_vars['cs']['xtmode'] == 1): ?>checked<?php endif; ?> />赢最少</div>
            <div style="float: left;color: green;width:30%">
            <input type="radio"  name='xtmode' value="-1" <?php if ($this->_tpl_vars['cs']['xtmode'] == -1): ?>checked<?php endif; ?> />输最少&nbsp;
            <input type="radio"  name='xtmode' value="-2" <?php if ($this->_tpl_vars['cs']['xtmode'] == -2): ?>checked<?php endif; ?> />输最多&nbsp;
            <input type="radio"  name='xtmode' value="-3" <?php if ($this->_tpl_vars['cs']['xtmode'] == -3): ?>checked<?php endif; ?> />随机输&nbsp;
            </div>
            <div style="float: left;color: blue;width:30%">
            <input type="radio"  name='xtmode' value="5" <?php if ($this->_tpl_vars['cs']['xtmode'] == 5): ?>checked<?php endif; ?> />指定中奖率&nbsp;
            <select class="txt2 shenglv" style="width: 70px;">
                <option value="21" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 21 )): ?>selected<?php endif; ?>>2期中1</option>
                <option value="31" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 31 )): ?>selected<?php endif; ?>>3期中1</option>
                <option value="32" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 32 )): ?>selected<?php endif; ?>>3期中2</option>
                <option value="41" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 41 )): ?>selected<?php endif; ?>>4期中1</option>
                <option value="42" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 42 )): ?>selected<?php endif; ?>>4期中2</option>
                <option value="43" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 43 )): ?>selected<?php endif; ?>>4期中3</option>
                <option value="51" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 51 )): ?>selected<?php endif; ?>>5期中1</option>
                <option value="52" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 52 )): ?>selected<?php endif; ?>>5期中2</option>
                <option value="53" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 53 )): ?>selected<?php endif; ?>>5期中3</option>
                <option value="54" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 54 )): ?>selected<?php endif; ?>>5期中4</option>
                <option value="61" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 61 )): ?>selected<?php endif; ?>>6期中1</option>
                <option value="71" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 71 )): ?>selected<?php endif; ?>>7期中1</option>
                <option value="72" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 72 )): ?>selected<?php endif; ?>>7期中2</option>
                <option value="73" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 73 )): ?>selected<?php endif; ?>>7期中3</option>
                <option value="81" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 81 )): ?>selected<?php endif; ?>>8期中1</option>
                <option value="83" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 83 )): ?>selected<?php endif; ?>>8期中3</option>
                <option value="91" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 91 )): ?>selected<?php endif; ?>>9期中1</option>
                <option value="92" <?php if (( $this->_tpl_vars['cs']['shenglv'] == 92 )): ?>selected<?php endif; ?>>9期中2</option>
            </select>
            </div>
          </td>

          <td>
            <input type="number" class="txt2 kongje" value="<?php echo $this->_tpl_vars['cs']['kongje']; ?>
" />元
          </td>
          <td>
            <input type="number" class="txt2 suiji" value="<?php echo $this->_tpl_vars['cs']['suiji']; ?>
" />
          </td>
          <td style="display: none;">
            <input type="radio"  name='zhiding' value="0" <?php if ($this->_tpl_vars['cs']['zhiding'] == 0): ?>checked<?php endif; ?> />不指定&nbsp;
            <input type="radio"  name='zhiding' value="1" <?php if ($this->_tpl_vars['cs']['zhiding'] == 1): ?>checked<?php endif; ?> />赢&nbsp;
            <input type="radio"  name='zhiding' value="-1" <?php if ($this->_tpl_vars['cs']['zhiding'] == -11): ?>checked<?php endif; ?> />输&nbsp;
            <input type="text" class="txt2 zduser" value="<?php echo $this->_tpl_vars['cs']['zduser']; ?>
" />
          </td>
          <td>
            <input type="radio"  name='zcmode' value="0" <?php if ($this->_tpl_vars['cs']['zcmode'] == 0): ?>checked<?php endif; ?> />总额&nbsp;
            <input type="radio"  name='zcmode' value="1" <?php if ($this->_tpl_vars['cs']['zcmode'] == 1): ?>checked<?php endif; ?> />占成
          </td>
          <td>
            <input type="number" class="txt2 ylup" value="<?php echo $this->_tpl_vars['cs']['ylup']; ?>
" />元
          </td>
           <td><input type="button" value="修改" class="editguanfang" /></td>
      </tr>
    </tr>  
    <tr><td colspan="8">随机数越大越精确，但计算速度比较慢!任何模式只要总投注金额小于【起控金额】，都是随机开!当天盈利大于【盈利上限】后随机开,【盈利上限】为0不限制。</td></tr>
 </table>

<?php endif; ?>

<?php if ($this->_tpl_vars['game'][0]['fast'] == 1): ?>
 <table class="data_table kpcs">
<thead>
    <tr>
      <th >开盘参数设置</th>
      <th >首期开盘时间</th><th >开盘时间2</th><th>期数间隔(分)</th><th>提前关盘时间(秒)</th><th>期数</th><th>总期数</th><th>开始日期</th><th>开始期数</th><th>调整期数</th><th>开奖推迟(秒)</th><th>开盘推迟(秒)</th>
     <th>操作</th>
   </tr>
 </thead>
      </tr>
      <tr> <td><?php echo $this->_tpl_vars['game'][0]['gname']; ?>
</td>
        <td><input type="text" class="txt2 starttime" value="<?php echo $this->_tpl_vars['cs']['starttime']; ?>
" /></td>
        <td><input type="text" class="txt2 starttime2" value="<?php echo $this->_tpl_vars['cs']['starttime2']; ?>
" /></td>
        <td><input type="text" class="txt2 qsjg" value="<?php echo $this->_tpl_vars['cs']['qsjg']; ?>
" /></td>
        <td><input type="text" class="txt2 closetime" value="<?php echo $this->_tpl_vars['cs']['closetime']; ?>
" /></td>
        <td><input type="text" class="txt2 qsnums" value="<?php echo $this->_tpl_vars['cs']['qsnums']; ?>
" /></td>
        <td><input type="text" class="txt2 qishunum" value="<?php echo $this->_tpl_vars['cs']['qishunum']; ?>
" /></td>
        <td><input type="text" class="txt2 startdate" value="<?php echo $this->_tpl_vars['cs']['startdate']; ?>
" /></td>
        <td><input type="text" class="txt2 startqs" value="<?php echo $this->_tpl_vars['cs']['startqs']; ?>
" /></td>
        <td><input type="text" class="txt2 tzqs" value="<?php echo $this->_tpl_vars['cs']['tzqs']; ?>
" /></td>
        <td><input type="text" class="txt2 tuichi" value="<?php echo $this->_tpl_vars['cs']['tuichi']; ?>
" /></td>
        <td><input type="text" class="txt2 tuichikp" value="<?php echo $this->_tpl_vars['cs']['tuichikp']; ?>
" /></td>
      <td><input type="button" value="修改" class="editkpcs" /> </td>
      
    </tr>
    <tr>
       <td colspan="13">注:请不要随便修改，不明白咨询管理员!</td>
    </tr>  
 </table>

<?php endif; ?>
 
 <table class="data_table pikj">
   <tr><th><input type="button" class="pikjsend" value="提交数据" /><input type="button" class="pikjclose" value="关闭" style="margin-left:20px;" /><input type="button" class="pikjclear" value="清空" style="margin-left:20px;" /></th></tr>
    <tr><th><textarea class='pikjtxt' cols="70" rows="8"></textarea></th></tr>
  <tr><th style="text-align:left;padding-left:5px;">数据格式：期数+开奖号码，中间用半角逗号(,)隔开,结尾也必须有一个逗号.<BR />
          如重庆时时彩：201201012,6,7,1,2,6,<BR />
          如广东快乐十分：2013011243,19,12,02,08,18,07,17,03,<BR />
  </th></tr>
 </table>

 <?php if ($this->_tpl_vars['game'][0]['fast'] == 0): ?>
   <table class="data_table addkj">
   
     <tr>
      <th>期数</th><th>开盘时间</th><th>关盘时间</th><th>开奖时间</th><th rowspan=2><input type="button" value="增加期数" class="add"   /></th><td rowspan="2" style="display: none;"> 
      日期:<input type="text" class="pdate txt5 date" value="<?php echo $this->_tpl_vars['sdate'][10]; ?>
"  /><BR />
      <input type="button" value="批量增加期数" class="padd"  /></td>
     </tr>
	   
    <tr><td><input type="text" class='qishu txt2' value="<?php echo $this->_tpl_vars['game'][0]['thisqishu']+1; ?>
"   /></td>
         <td><input type="text" class="opendate txt5 date" value="<?php echo $this->_tpl_vars['sdate'][10]; ?>
"  />&nbsp;<input type="text" class="opentime txt6" value="00:00:00"  /></td>
          <td><input type="text" class="closedate txt5 date" value="<?php echo $this->_tpl_vars['sdate'][10]; ?>
" />&nbsp;<input type="text" class="closetime txt6" value="00:00:00" /></td>
      <td><input type="text" value="<?php echo $this->_tpl_vars['sdate'][10]; ?>
"  class="kjdate txt5 date" />&nbsp;<input type="text" value="00:00:00"  class="kjtime txt6" /></td>
     </tr>
  </table>
  <?php endif; ?>
  
  
  
   <table class="data_table cmd">
   <tr><td colspan="2" style="text-align:left">日期:<input class='txt5 date' id="start"  value='<?php echo $this->_tpl_vars['sdate'][10]; ?>
' size='11' />
    &nbsp;—&nbsp;
    <input class='txt5 date' id="end" name='end'  value='<?php echo $this->_tpl_vars['sdate'][10]; ?>
' size='11' />
    <input type="button" class="s"  d=1 value="今天" />
    <input type="button" class="s"  d=2 value="昨天" />
    <input type="button" class="s"  d=3 value="本星期" />
    <input type="button" class="s"  d=4 value="上星期" />
    <input type="button" class="s"  d=5 value="本月" />
    <input type="button" class="s"  d=6 value="上月" />
    <input type="button" value='删除选定日期数据' class="deldate" t=1 />
    <input type="button" value='删除指定日期之前' class="deldate" t=2 />
    <span>选定项:</span>
    <input type="button" value='关闭报表' class="changebaostatus" action='0' /><input type="button" value='打开报表' class="changebaostatus"  action='1' />    
    <input type="button" value="删除报表" class="delbao" /><input type="button" value="删除全部" class="delall" />
    <input type="button" value='更新长龙' class="updatelong"  action='1' style='display: none;' />
    <input type="button" value='期数去重复' class="qsqc"  date='<?php echo $this->_tpl_vars['sdate'][10]; ?>
' />
    </td></tr>
 <tr><td><input type="radio" class="jsstatus" name="jsstatus" value="2" />全部<input type="radio" class="jsstatus" name="jsstatus" value="0" />未结算<input type="radio" class="jsstatus" name="jsstatus" value="1" checked />已结算<input type="button" value="更新开奖" class="updatekj" /><input type="button" value='批量开奖' class="pikjcmd"  /><input type="button" value='批量结算' class="pijs"  /><select class="psize">
 <option value="50">每页50条</option>
 <option value="120" selected>每页120条</option>
 <option value="250">每页250条</option>
 <option value="500">每页500条</option>

 </select><input  type="hidden" value="1" class="page" />
 &nbsp;&nbsp;<input type="checkbox" class="ze" value="1" />有注单&nbsp;&nbsp;共<label class="rcount chu"></label>期
 
 </td>
 
  <td style="width:42%"></td>
  
  </tr>
 </table>
  
  <table class="data_table list kjjr table_ball"></table>
  
   <table class="data_table editkj">
 
    <tr><th>期数</th><th>开盘时间</th><th>关盘时间</th><th>开奖时间</th><th>号码</th>
    <th rowspan="2"><input type="button" value="修改" class="editkjsend"  /><BR /><input type="button" value="关闭" class="editkjclose"  /></th></tr>
    
    <tr><td><label></label></td><td><input type="text" class="txt31 eopentime" /></td><td><input type="text" class="txt31 eclosetime" /></td><td><input type="text" class="txt31 ekjtime" /></td>
    <td class="kjhm"></td></tr>
 </table>
</div>
<table class="data_table list xxtb">
<thead>
 <tr class="bt">
 <th style="width:80px">彩种</th>
  <th>期數</th>  
  <th>類別</th>
  <th><a href="javascript:void(0);" class="je">金額<img src="<?php echo $this->_tpl_vars['globalpath']; ?>
img/down.gif" s='up' /></th>
  <th>赔率</th>
  <th>退水</th>
  <th>會員</th>
  <th><a href="javascript:void(0);" class="time">時間<img src="<?php echo $this->_tpl_vars['globalpath']; ?>
img/down.gif" s='down' /></th>
 </tr></thead>
</table>

<input type="hidden" class='sort' orderby='time' sorttype='DESC' page='1' xtype='2' tztype='0' con='' />
<iframe id='longfrm' style="display:none;"></iframe>
<div id='test' style="clear:both"></div>
<script language="javascript">
sdate=new Array();
<?php $_from = $this->_tpl_vars['sdate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['i']):
?>
sdate[<?php echo $this->_tpl_vars['key']; ?>
]="<?php echo $this->_tpl_vars['i']; ?>
";
<?php endforeach; endif; unset($_from); ?>
var fenlei = <?php echo $this->_tpl_vars['config']['fenlei']; ?>
;
var ngid = <?php echo $this->_tpl_vars['config']['fenlei']; ?>
;
</script>
</body>
</html>