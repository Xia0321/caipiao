<?php /* Smarty version 2.6.18, created on 2026-02-12 16:34:39
         compiled from sysconfig.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style>

.infotb th{text-align:right;padding-right:5px;width:160px;line-height:150%;height:30px;}
.infotb td{text-align:left;padding-left:5px;line-height:150%;height:30px;}
.infotb td.mid{text-align:center}
label {	color:#D50000}
</style>
</head><body>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='sysconfig';</script>
<div class="main">
	<div class="top_info">
		<span class="title">参数设置</span><span class="right"></span>
	</div>
 <input type="hidden" name="xtype" value="setsys" />
 <table class="data_table list infotb">
 <tr> 
    <th>网站开关</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['ifopen'] == 1): ?>checked<?php endif; ?> class="ifopen" /></td>
    <th>密码修改提示</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['passtime']; ?>
' class="passtime txt1" />天</td>
 </tr>
 <tr>
    <th>登陆停留</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['livetime']; ?>
' class="livetime txt1" />分</td>
    <th>两次投注间隔</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['tzjg']; ?>
' class="tzjg txt1" />秒</td>
 </tr>
 <tr>
    <th>超级密码</th><td><input type='text' class="txt1 supass"  /></td>
    <th>网站类型</th><td><select class="moneytype">
   <OPTION value="0" <?php if ($this->_tpl_vars['config']['moneytype'] == '0'): ?>selected<?php endif; ?>>信用网</OPTION>
   <OPTION value="1" <?php if ($this->_tpl_vars['config']['moneytype'] == '1'): ?>selected<?php endif; ?>>现金网</OPTION>
   </select></td>
 </tr>
  <tr>
    <th>转账密码</th><td><?php if (( $this->_tpl_vars['moneypassflag'] == 1 )): ?>已绑定<?php else: ?>未绑定<?php endif; ?>&nbsp;&nbsp;<a href="javascript:void(0)" class='czmoneypass'>重置</a></td>
    <th></th><td></td>
 </tr>
     <tr>
         <th>盈利限制倍数</th><td><input type='text' class="txt1 yingxz" value='<?php echo $this->_tpl_vars['config']['yingxz']; ?>
'   /></td>
         <th>盈利限制金额</th><td><input type='text' class="txt1 yingxzje" value='<?php echo $this->_tpl_vars['config']['yingxzje']; ?>
'   /></td>
     </tr>
 <tr>
    <!--<th>自动报码</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['autobaoma'] == 1): ?>checked<?php endif; ?> class="autobaoma" /></td>-->
    <th>PK10牛牛庄家选位</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['pk10num']; ?>
' class="pk10num txt3" /></td>
    <th>最高派彩</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['maxpc']; ?>
' class="maxpc txt1" /></td>
 </tr>
 <tr>
    <!--<th>自动报码</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['autobaoma'] == 1): ?>checked<?php endif; ?> class="autobaoma" /></td>-->
    <th>PK10无牛通杀点数</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['pk10ts']; ?>
' class="pk10ts txt3" /></td>
    <th>PK10牛牛玩法开关</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['pk10niu'] == 1): ?>checked<?php endif; ?> class="pk10niu" /></td>
 </tr>
 <tr>
   <th>快开信用额度恢复</th><td><select class="reseted">
   <OPTION value="week" <?php if ($this->_tpl_vars['config']['reseted'] == 'week'): ?>selected<?php endif; ?>>每周</OPTION>
   <OPTION value="day" <?php if ($this->_tpl_vars['config']['reseted'] == 'day'): ?>selected<?php endif; ?>>每天</OPTION>
   </select></td>
   <th>自动恢复赔率</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['autoresetpl'] == 1): ?>checked<?php endif; ?>  class="autoresetpl" /></td>
    
 </tr>
 <tr style="display: none;">
    <th>退水修改开始时间</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['editstart']; ?>
' class="editstart txt1" /></td>
    <th>退水修改结束时间</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['editend']; ?>
' class="editend txt1" /></td>
 </tr>
 <tr>
    <th>第一级运营自动降倍</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['comattpeilv'] == 1): ?>checked<?php endif; ?>  class="comattpeilv" /></td>
    <th>运营商自动补货</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['flyflag'] == 1): ?>checked<?php endif; ?>  class="flyflag" /></td>
 </tr>
 <tr>
  <th>运营商修改占成</th>   <td><input type="checkbox" <?php if ($this->_tpl_vars['config']['editzc'] == 1): ?>checked<?php endif; ?> class="editzc" /></td> 
  <th>运营商删除用户</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['deluser'] == 1): ?>checked<?php endif; ?> class="deluser" /></td>
 </tr>
 <tr>
  <th>自动降倍双面联动</th>   <td><input type="checkbox" <?php if ($this->_tpl_vars['config']['autold'] == 1): ?>checked<?php endif; ?> class="autold" /> *如:降双同时降单</td> <th>自动识别手机/PC</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['loginfenli'] == 1): ?>checked<?php endif; ?>  class="loginfenli" /></td>
   
 </tr>
 <tr><th>自动降倍赔率还原</th><td><select class="plresetfs">
   <OPTION value="now" <?php if ($this->_tpl_vars['config']['plresetfs'] == 'now'): ?>selected<?php endif; ?>>开出即还原</OPTION>
   <OPTION value="next" <?php if ($this->_tpl_vars['config']['plresetfs'] == 'next'): ?>selected<?php endif; ?>>停留一期还原</OPTION>
   </select></td>
   <th>占成模式</th>
   <td>
<select class="zcmode">
   <OPTION value="0" <?php if ($this->_tpl_vars['config']['zcmode'] == 0): ?>selected<?php endif; ?>>默认</OPTION>
   <OPTION value="1" <?php if ($this->_tpl_vars['config']['zcmode'] == 1): ?>selected<?php endif; ?>>按彩种</OPTION>   
   </select>
   </td>
  </tr>
  <tr> 
   <th>赔率差设置</th>
   <td>
<select class="plc">
   <OPTION value="0" <?php if ($this->_tpl_vars['config']['plc'] == 0): ?>selected<?php endif; ?>>关闭</OPTION>
   <OPTION value="1" <?php if ($this->_tpl_vars['config']['plc'] == 1): ?>selected<?php endif; ?>>开启</OPTION>  
   </select> 
   </td>
    <th>单注最小</th>
    <td><input type="text" class='minje txt1' value='<?php echo $this->_tpl_vars['config']['minje']; ?>
' ></td>
  </tr>
 <tr>      


 </table>
      <div class="control">
          <input type="button" value="保存" class="button editall" />
		</div>
</div>
<div id='test'></div>
</body>
</html>