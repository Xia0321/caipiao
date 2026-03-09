<?php /* Smarty version 2.6.18, created on 2026-03-08 09:42:48
         compiled from xy.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php if ($this->_tpl_vars['rkey'] == 0): ?>oncontextmenu="return false"<?php endif; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $this->_tpl_vars['title']; ?>
</title>
<script language="javascript" src="/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['mess']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
//alert("<?php echo $this->_tpl_vars['mess'][$this->_sections['i']['index']]['content']; ?>
");
<?php endfor; endif; ?>
</script>
<?php if (( $this->_tpl_vars['status'] != 1 )): ?>
<script type="text/javascript">
    alert("抱歉!你的帐号已被冻结（只限结帐功能可用），请和上级联系。<?php echo $this->_tpl_vars['status']; ?>
");
</script>
<?php endif; ?>
<body class="<?php echo $this->_tpl_vars['skin']; ?>
">
<script type="text/javascript" id="myjs">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';
var js=1;
var sss='xy';
window.location.href='/uxj/top.php';
</script>
</body>
</html>