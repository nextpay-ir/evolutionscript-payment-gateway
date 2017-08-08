<?php /* Smarty version Smarty-3.1.13, created on 2017-08-08 10:26:24
         compiled from "/var/www/html/evolutionscript/ptc/templates/ModernBlue/login_ads.tpl" */ ?>
<?php /*%%SmartyHeaderCode:74217211659895288a74b13-20428570%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd42766936f04a708a8cb3eba4c229d7a3d791fc3' => 
    array (
      0 => '/var/www/html/evolutionscript/ptc/templates/ModernBlue/login_ads.tpl',
      1 => 1493457901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '74217211659895288a74b13-20428570',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'settings' => 0,
    'lang' => 0,
    'loginads' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_59895288ab1dc4_73418544',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59895288ab1dc4_73418544')) {function content_59895288ab1dc4_73418544($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['settings']->value['loginads_available']=='yes'){?>
 <script>
$(function() {
	$( "#loginads-dialog" ).dialog({
		width: 500,
		resizable: false,
		modal: true,
		buttons: [{
			text: '<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['close'];?>
',
			click: function() {
				$( this ).dialog( "close" );
			}
		}]
	});
});
</script>

<div id="loginads-dialog" title="<?php echo $_smarty_tpl->tpl_vars['settings']->value['site_name'];?>
 News">
<?php if ($_smarty_tpl->tpl_vars['loginads']->value){?>
<div style="padding-bottom:10px;">
<h3><?php echo $_smarty_tpl->tpl_vars['loginads']->value['title'];?>
</h3>
<?php echo $_smarty_tpl->tpl_vars['loginads']->value['message'];?>

</div>
<?php }?>
<p>
<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['foo'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['name'] = 'foo';
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['settings']->value['loginads_max']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total']);
?>
	<div style="padding-bottom:5px; text-align:center"><?php if (showloginads()){?><?php }?></div>
<?php endfor; endif; ?>
</p>
</div>
<?php }?><?php }} ?>