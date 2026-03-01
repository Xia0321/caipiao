<?php /* Smarty version 2.6.18, created on 2026-02-11 20:21:32
         compiled from suserpointsnew.html */ ?>
<div class="top_info">
    <span class="title"><?php echo $this->_tpl_vars['layername']; ?>
 <span><?php echo $this->_tpl_vars['username']; ?>
（<?php echo $this->_tpl_vars['name']; ?>
）</span> -&gt; 退水</span>
    <span class="right"><a class="back close" types="<?php if ($this->_tpl_vars['ifagent'] == 1): ?>ag<?php else: ?>u<?php endif; ?>">返回</a></span>
</div>
<div class="warning_panel" style="display: none;">
    快开彩在【<?php echo $this->_tpl_vars['editstart']; ?>
--<?php echo $this->_tpl_vars['editend']; ?>
】修改以下参数才能生效，低频彩开盘期间修改以下参数不会生效！ 当天未投注用户修改参数可以立即生效！
</div>




<ul class="tab">
    <li class="tab_title02">
        <a href="javascript:void(0);" class="infoset">基本资料</a>
        <a href="javascript:void(0);" class="selected">退水设定</a>
    </li>
</ul>



<table class="data_table info_table user_panel pointstb">
    <thead style="display: none;"><th colspan="2" uid='<?php echo $this->_tpl_vars['uid']; ?>
' fid='<?php echo $this->_tpl_vars['fid']; ?>
' class="uid">【<label><?php echo $this->_tpl_vars['username']; ?>
</label>】退水设定</th></thead>
    <tbody style='display:none;'>
    <td colspan="2">
    <!--统一设置赚取退水:    <select name="liushui" id="liushui">
    
         <?php $_from = $this->_tpl_vars['liushui']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
          <option value="<?php echo $this->_tpl_vars['i']; ?>
"><?php echo $this->_tpl_vars['i']; ?>
</option>
         <?php endforeach; endif; unset($_from); ?><option value="all">全部</option>
               </select>%&nbsp;&nbsp;-->
    <input type="button" class="btn1 btnf setpointssend" value="提交修改" />
     <input type="button" class="btn1 btnf close" value="关闭窗口" /></td>
   </tr>
   <TR >
    <Td colspan="2" style="text-align:left;padding-left:10px;font-size:13px;color:#0063e3">注意事项:<BR />一、如果修改代理级的退水，<Br />
      1、改动后的退水大于改动前退水，该用户所有下线的退水维持不变。<BR />
      2、改动后的退水[P]小于改动前的退水，所有下线的退水和[P]作比较，大于[P]，下线的退水为[P]，小于[P]，下线退水不变<BR />
      二、如果修改占成，该用户的所有下级占成将归0。<BR />
      三、如果修改代理级的单注最大限额，<Br />
      1、改动后的值大于改动前值，该用户所有下线的（单注最大限额）维持不变。<BR />
      2、改动后的值[M]小于改动前的值，所有下线的（单注最大限额）和[M]作比较，大于[M]，下线的(单注最大限额)为[M]，小于[M]，下线(单注最大限额)不变<br />
      四、如果修改用户的（单注最低限额），该用户的所有下线的（单注最低限额）和该用户相同。 </Td>
   </TR>


   </tbody>
  </table>
  

<div class="game_tab_class">
<?php if ($this->_tpl_vars['config']['fasttype'] == 1): ?>
<a id="tab_0" href="javascript:;" class="selected on">快开彩</a>
<div id="cmcontrol" style="margin-right:20px;">赚取退水：<input> <input class="fastbtn" type="button" value="确定"></div>
<?php endif; ?>
<?php if ($this->_tpl_vars['config']['slowtype'] == 1): ?>
<a id="tab_0" href="javascript:;" class="selected on">低频彩</a>
<div id="cmcontrol">赚取退水：<input> <input type="button" class="slowbtn" value="确定"></div>
<?php endif; ?>
</div>   
   <div class="contents param_panel input_panel tab_panel data_panel">
   
<table class="data_table quick" <?php if ($this->_tpl_vars['config']['fasttype'] == 0): ?>style='display:none;'<?php endif; ?>>
<thead><tr>
<th>快开彩快速设置</th>
    <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
    <th><?php echo $this->_tpl_vars['i']; ?>
盘%</th>
    <?php endforeach; endif; unset($_from); ?>
<th>注单限额</th>
<th>单期限额</th>


<th>操作</th></tr></thead>
<tbody>
<tr class="t_BALL"><th class="color">号码类（球号、车号、正码等）</th>
    <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
    <td><input name="{$i}" class="commission"></td>
    <?php endforeach; endif; unset($_from); ?>
	<td><input class="amount"></td>
	<td><input class="amount"></td>

	


	<td><input type="button" value="修改"></td>
</tr>
<tr class="t_LM"><th class="color">两面类（大小、单双、龙虎、三军等）</th>
    <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
    <td><input name="{$i}" class="commission"></td>
    <?php endforeach; endif; unset($_from); ?>
	<td><input class="amount"></td>
	<td><input class="amount"></td>


	<td><input type="button" value="修改"></td>
</tr>
<tr class="t_ITEM"><th class="color">多项类（方位、中发白、总和过关等）</th>
    <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
    <td><input name="{$i}" class="commission"></td>
    <?php endforeach; endif; unset($_from); ?>
	<td><input class="amount"></td>
	<td><input class="amount"></td>


	<td><input type="button" value="修改"></td>
</tr>
<tr class="t_MP"><th class="color">连码类（任选二、任选三、前二组选等）</th>
    <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
    <td><input name="{$i}" class="commission"></td>
    <?php endforeach; endif; unset($_from); ?>
	<td><input class="amount"></td>
	<td><input class="amount"></td>


	<td><input type="button" value="修改"></td>
</tr>
<tr class="t_"><th class="color">其它（冠亚和、前中后三等）</th>
    <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
    <td><input name="{$i}" class="commission"></td>
    <?php endforeach; endif; unset($_from); ?>
	<td><input class="amount"></td>
	<td><input class="amount"></td>


	<td><input type="button" value="修改"></td>
</tr>
<tr class="tf_"  style="display: none;"><th class="color">番摊</th>
    <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
    <td><input name="{$i}" class="commission"></td>
    <?php endforeach; endif; unset($_from); ?>
  <td><input class="amount"></td>
  <td><input class="amount"></td>


  <td><input type="button" value="修改"></td>
</tr>
</tbody>
</table>

</div>
 <div class="data_footer control">快开彩在【<?php echo $this->_tpl_vars['editstart']; ?>
--<?php echo $this->_tpl_vars['editend']; ?>
】修改以下参数才能生效！ 当天未投注用户修改参数可以立即生效！<input type="button" value="保存" class="button setpointssend" /> <input type="button" value="取消"  class="close button"></div>
<style type="text/css">
.param_panel .data_table td {
	text-align:center;
}
.param_panel .data_table input {
	text-align:center;
}
.param_panel .layout th.color{height:45px;}
</style>