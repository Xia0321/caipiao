<?php /* Smarty version 2.6.18, created on 2026-02-11 22:20:45
         compiled from changpass2.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script language="javascript" src="/js/md5.js"></script>
</head>
<body>
<script language="javascript" id=myjs>var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';
var js=1;var sss='changepass2';
</script>
<div class="main">
<div class="top_info">
<span class="title">变更密码</span>
</div>
<div class="contents">
<table class="data_table info_table">
<tr><th>原始密码</th><td><input id="oldpassword" type="password" class="input" /></td></tr>
<tr><th>新设密码</th><td><input id="password" type="password" class="input" /></td></tr>
<tr><th>确认密码</th><td><input id="ckpassword" type="password" class="input" /></td></tr>
</table>
</div>
<div class="data_footer control">&nbsp;<input class="button qd" type="button" value="确定修改"  />&nbsp;</div>
</div>
</body>
</html>