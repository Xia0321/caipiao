<?php /* Smarty version 2.6.18, created on 2026-03-09 21:09:58
         compiled from mch.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style type="text/css">
.mch_list_wrap { padding: 20px 24px; }
.mch_list_wrap .top_info { margin-bottom: 16px; }
.mch_list_wrap .data_table { width: 100%; border-collapse: collapse; font-size: 14px; background: #fff; border: 1px solid #e8e8e8; border-radius: 4px; overflow: hidden; }
.mch_list_wrap .data_table thead { background: #f5f5f5; }
.mch_list_wrap .data_table th { padding: 12px 16px; text-align: left; font-weight: 600; color: #333; border-bottom: 1px solid #e8e8e8; }
.mch_list_wrap .data_table td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #eee; }
.mch_list_wrap .data_table tbody tr:last-child td { border-bottom: none; }
.mch_list_wrap .data_table tbody tr:hover { background: #fafafa; }
.mch_list_wrap .data_table .col_id { width: 60px; }
.mch_list_wrap .data_table .col_code { width: 120px; }
.mch_list_wrap .data_table .col_callback { max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.mch_list_wrap .data_table .col_status { width: 80px; }
.mch_list_wrap .data_table .col_time { width: 150px; }
.mch_list_wrap .data_table .col_act { width: 80px; }
.mch_list_wrap .data_table .col_callback[title] { cursor: help; }
.mch_list_wrap .status1 { color: #52c41a; font-weight: 500; }
.mch_list_wrap .status0 { color: #999; }
.mch_list_wrap .edit_mch { color: #1554BE; text-decoration: none; }
.mch_list_wrap .edit_mch:hover { text-decoration: underline; }
.mch_list_wrap .addtop { margin-left: 8px; }
.mch_list_wrap .empty_tip { text-align: center; color: #999; padding: 32px; font-size: 14px; }
</style>
</head>
<body id="topbody">
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='mch';</script>
<div class="xbody1">
    <div class="mch_list_wrap">
        <div class="top_info">
            <span class="title">商户列表</span>
            <a href="javascript:void(0);" class="addtop" onclick="window.location.href=mulu+'mch.php?xtype=add'">添加商户</a>
        </div>
        <table class="data_table mch_tb wd100">
            <thead>
                <tr>
                    <th class="col_id">ID</th>
                    <th class="col_code">商户编码</th>
                    <th class="col_callback">回调地址</th>
                    <th class="col_status">状态</th>
                    <th class="col_time">创建时间</th>
                    <th class="col_act">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                <tr>
                    <td class="col_id"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
</td>
                    <td class="col_code"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['mch_code']; ?>
</td>
                    <td class="col_callback" title="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['callback_url']; ?>
"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['callback_url']; ?>
</td>
                    <td class="col_status"><span class="status<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['status']; ?>
"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['statusz']; ?>
</span></td>
                    <td class="col_time"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['created_at']; ?>
</td>
                    <td class="col_act">
                        <a href="javascript:void(0);" class="edit_mch" mid="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['id']; ?>
">编辑</a>
                    </td>
                </tr>
                <?php endfor; else: ?>
                <tr><td colspan="6" class="empty_tip">暂无商户</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $(".edit_mch").click(function(){
        var id = $(this).attr("mid");
        window.location.href = mulu + "mch.php?xtype=edit&id=" + id;
    });
});
</script>
</body>
</html>