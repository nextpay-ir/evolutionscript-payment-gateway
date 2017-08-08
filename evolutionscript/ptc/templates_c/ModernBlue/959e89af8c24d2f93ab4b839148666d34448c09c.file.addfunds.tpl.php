<?php /* Smarty version Smarty-3.1.13, created on 2017-08-07 14:02:50
         compiled from "/var/www/html/evolutionscript/ptc/templates/ModernBlue/addfunds.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1774826054598833c2353404-11844594%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '959e89af8c24d2f93ab4b839148666d34448c09c' => 
    array (
      0 => '/var/www/html/evolutionscript/ptc/templates/ModernBlue/addfunds.tpl',
      1 => 1493457901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1774826054598833c2353404-11844594',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'countgateway' => 0,
    'gateways' => 0,
    'lang' => 0,
    'settings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_598833c239c561_10683885',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_598833c239c561_10683885')) {function content_598833c239c561_10683885($_smarty_tpl) {?><script type="text/javascript">
	<?php if ($_smarty_tpl->tpl_vars['countgateway']->value!=0){?>
		gateway = Array;
        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['n'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['n']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['name'] = 'n';
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['gateways']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total']);
?>
		gateway[<?php echo $_smarty_tpl->tpl_vars['gateways']->value[$_smarty_tpl->getVariable('smarty')->value['section']['n']['index']]['id'];?>
] = '<?php echo $_smarty_tpl->tpl_vars['gateways']->value[$_smarty_tpl->getVariable('smarty')->value['section']['n']['index']]['min_deposit'];?>
';
        <?php endfor; endif; ?>
    <?php }?>  
function set_gateway(val){
	if(val != ''){
		if(val == 'balance'){
			$("#min_deposit").html('<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['min_deposit'];?>
: <?php echo $_smarty_tpl->tpl_vars['settings']->value['amount_transfer'];?>
 تومان');
		}else{
			$("#min_deposit").html('<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['min_deposit'];?>
: '+gateway[val]+' تومان ');
		}
		$("#min_deposit").show();
	}else{
		$("#min_deposit").hide();
	}
}
function complete_deposit(){
	$("#error_box").hide();
	var gatewayid = $("#gateway_list").val();
	var amount = $("#amount_deposit").val();
	if(isNaN(parseFloat(amount))){
			$("#error_box").html('<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['min_deposit'];?>
: <?php echo $_smarty_tpl->tpl_vars['settings']->value['amount_transfer'];?>
 تومان');
			$("#error_box").fadeIn();
			return false;
	}
	amount = parseFloat(amount);
	if(gatewayid == ''){
		$("#error_box").html('<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['selectmethod'];?>
');
		$("#error_box").fadeIn();
	}else
	if(gatewayid == 'balance'){
		if(amount < <?php echo $_smarty_tpl->tpl_vars['settings']->value['amount_transfer'];?>
){
			$("#error_box").html('<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['min_deposit'];?>
: <?php echo $_smarty_tpl->tpl_vars['settings']->value['amount_transfer'];?>
 تومان');
			$("#error_box").fadeIn();
		}else{
			$("#acc_amount").val(amount);
			$("#addfrm").hide();
			$("#acc_balancefrm").fadeIn();
		}
	}else{
		if(amount < gateway[gatewayid]){
			$("#error_box").html('<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['min_deposit'];?>
: '+gateway[gatewayid]+' تومان ');
			$("#error_box").fadeIn();
		}else{
			$( "#amount"+gatewayid).val(amount);
			$("#addfrm").hide();
			$("#gateway-"+gatewayid).fadeIn();
		}
	}
}
function hide_gateways(){
	$(".gatewayfrm").hide();
	$("#addfrm").fadeIn();
}
</script>
<div class="widget-main-title"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['addfunds'];?>
</div>
<div class="menu-content">
    <div class="error_box" id="error_box" style="display:none"></div>
	<table width="100%" class="widget-tbl" id="addfrm">
    	<tr>
        	<td align="right" width="200"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['method'];?>
:</td>
            <td>
            <select name="gateway" onchange="set_gateway(this.value);" id="gateway_list">
            <option value=""></option>
            <?php if ($_smarty_tpl->tpl_vars['settings']->value['money_transfer']=='yes'){?>
            <option value="balance"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['account_balance'];?>
</option>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['countgateway']->value!=0){?>
                <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['n'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['n']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['name'] = 'n';
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['gateways']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total']);
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['gateways']->value[$_smarty_tpl->getVariable('smarty')->value['section']['n']['index']]['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['gateways']->value[$_smarty_tpl->getVariable('smarty')->value['section']['n']['index']]['name'];?>
</option>
              	<?php endfor; endif; ?>
            <?php }?>
            </select>
            </td>
        </tr>
        <tr>
        	<td align="right"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['amount'];?>
:</td>
            <td><input type="text" name="amount" value="0.00" id="amount_deposit" /> <span style="font-size:10px; color:#0000CC" id="min_deposit"></span>
            </td>
        </tr>
        <tr>
        	<td></td>
            <td><input type="button" name="btn" value="<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['send'];?>
" onclick="complete_deposit();" /></td>
        </tr>
    </table>	
    
    
     <?php if ($_smarty_tpl->tpl_vars['settings']->value['money_transfer']=='yes'){?>
     	<div id="acc_balancefrm" style="display:none" class="gatewayfrm">
            <div class="info_box"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['click_complete_order'];?>
</div>
            <div align="center">
            <form class="formclass" onsubmit="return submitpayment();" id="checkoutform">
            <input type="hidden" name="action" value="buy" />
            <input type="hidden" name="buy" value="purchase_balance" />
            <input type="hidden" id="acc_amount" name="item" />
            <input type="image" src="images/gateways/ab.png" width="100" />
            <div><a href="javascript:void(0);" onclick="hide_gateways();">[<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['return'];?>
]</a></div>
            </form>
            </div>
        </div>
      <?php }?>  
	<?php if ($_smarty_tpl->tpl_vars['countgateway']->value!=0){?>
        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['n'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['n']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['name'] = 'n';
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['gateways']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['n']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['n']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['n']['total']);
?>
        <div id="gateway-<?php echo $_smarty_tpl->tpl_vars['gateways']->value[$_smarty_tpl->getVariable('smarty')->value['section']['n']['index']]['id'];?>
" style="display:none" class="gatewayfrm">
        	<div class="info_box"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['click_complete_order'];?>
</div>
            <div align="center">
			<img src="images/gateways/<?php echo $_smarty_tpl->tpl_vars['gateways']->value[$_smarty_tpl->getVariable('smarty')->value['section']['n']['index']]['id'];?>
.png" width="100" class="pointer" onclick="document.forms['checkout<?php echo $_smarty_tpl->tpl_vars['gateways']->value[$_smarty_tpl->getVariable('smarty')->value['section']['n']['index']]['id'];?>
'].submit();" />
            	 <div><a href="javascript:void(0);" onclick="hide_gateways();">[<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['return'];?>
]</a></div>
		        <span style="display:none"><?php echo $_smarty_tpl->tpl_vars['gateways']->value[$_smarty_tpl->getVariable('smarty')->value['section']['n']['index']]['formvar'];?>
</span>
            </div>
        </div>
        <?php endfor; endif; ?>
    <?php }?> 
    
       
</div>

           


<!-- End Content --><?php }} ?>