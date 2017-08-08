<?php /* Smarty version Smarty-3.1.13, created on 2017-08-08 10:26:24
         compiled from "/var/www/html/evolutionscript/ptc/templates/ModernBlue/summary.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5171833625989528893bf33-47162966%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a6dfbd399c1c91d5fe2f211460d73b59ea89b0c' => 
    array (
      0 => '/var/www/html/evolutionscript/ptc/templates/ModernBlue/summary.tpl',
      1 => 1493457901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5171833625989528893bf33-47162966',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user_info' => 0,
    'lang' => 0,
    'mymembership' => 0,
    'show_advice' => 0,
    'settings' => 0,
    'myclicks' => 0,
    'refclicks' => 0,
    'rentedrefclicks' => 0,
    'autopayclicks' => 0,
    'loginfailure' => 0,
    'initmember' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_59895288a71ce8_14256490',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59895288a71ce8_14256490')) {function content_59895288a71ce8_14256490($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/html/evolutionscript/ptc/includes/plugins/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_replace')) include '/var/www/html/evolutionscript/ptc/includes/plugins/smarty/plugins/modifier.replace.php';
?><?php if ($_smarty_tpl->tpl_vars['user_info']->value['loginads_view']==0){?>
	<?php echo $_smarty_tpl->getSubTemplate ("login_ads.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }?>
<div class="widget-main-title"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['dashboard'];?>
</div>
<div class="widget-content">
    <div class="admin-info">
        <div class="title"><?php echo $_smarty_tpl->tpl_vars['user_info']->value['username'];?>
</div>
        <div><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['membersince'];?>
: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['user_info']->value['signup'],"%d %B %Y");?>
</div>
        <div><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['membership'];?>
: <?php echo $_smarty_tpl->tpl_vars['mymembership']->value['name'];?>
</div>
        <?php if ($_smarty_tpl->tpl_vars['user_info']->value['type']!=1){?>
         <div><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['upgexpires'];?>
: <?php if ($_smarty_tpl->tpl_vars['user_info']->value['upgrade_ends']!=0){?><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['user_info']->value['upgrade_ends'],"%d-%m-%Y");?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['never'];?>
<?php }?></div>
        <?php }?>
    </div>
    <div class="calendar">
        <div class="top corner-top">
        <div style="font-size:12px"><?php echo smarty_modifier_date_format(time(),"%A");?>
</div>
        <?php echo smarty_modifier_date_format(time(),"%e");?>

        </div>
        <div class="bottom corner-bottom"><?php echo smarty_modifier_date_format(time(),"%B");?>
</div>
    </div>
    <div class="clear"></div>
</div>
<?php if ($_smarty_tpl->tpl_vars['show_advice']->value=='yes'){?>
    	<div class="dashboardbox corner-all">
        <?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['lang']->value['txt']['youhaventviewad'],"%clicks",$_smarty_tpl->tpl_vars['settings']->value['clicks_necessary']);?>

        </div>
<?php }?>

<div id="tabs">
	<ul>
    	<li><a href="#tab-1">آمار کلی</a></li>
    	<li><a href="#tab-2">آمار آگهی ها</a></li>
    	<li><a href="#tab-3">نمودار</a></li>
    	<li><a href="#tab-4">ورود های ناموفق</a></li>
    </ul>
    <div id="tab-1">
         <div class="widget-title"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['earningstats'];?>
</div>
        
        <table width="100%" class="tbl-content" cellpadding="4">
            <tr>
                <td width="20"><span class="system-icon money"></span></td>
                <td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['balance'];?>
</strong></td>
                <td><strong><?php echo $_smarty_tpl->tpl_vars['user_info']->value['money'];?>
 تومان</strong></td>
                <td align="right"><a href="/?view=account&page=withdraw">[ <?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['withdraw'];?>
 ]</a></td>
            </tr>
            <tr>
                <td><span class="system-icon creditcards"></span></td>
                <td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['purchasebalance'];?>
</strong></td>
                <td><strong><?php echo $_smarty_tpl->tpl_vars['user_info']->value['purchase_balance'];?>
 تومان</strong></td>
                <td align="right"><a href="/?view=account&page=addfunds">[ <?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['addfunds'];?>
 ]</a></td>
            </tr>
            <tr>
                <td><span class="system-icon hourglass"></span></td>
                <td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['pendingcashout'];?>
</strong></td>
                <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['pending_withdraw'];?>
 تومان</td>
                <td></td>
            </tr>
            <tr>
                <td><span class="system-icon css_valid"></span></td>
                <td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['paymentsdone'];?>
</strong></td>
                <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['withdraw'];?>
 تومان</td>
                <td></td>
            </tr>
            <?php if ($_smarty_tpl->tpl_vars['mymembership']->value['point_enable']==1){?>
            <tr>
                <td><span class="system-icon award_star_gold_3"></span></td>
                <td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['points'];?>
</strong>    </td>
                <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['points'];?>
 امتیاز</td>
                <td align="right"><a href="/?view=account&page=convert_points">[ <?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['convert_points'];?>
 ]</a></td>
            </tr>
            <?php }?>
        </table>
            
        <div class="widget-title"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['refstats'];?>
</div>    
        <table width="100%" class="tbl-account" cellpadding="4">
            <tr>
                <td width="20"><span class="system-icon user"></span></td>
                <td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['directrefs'];?>
</strong></td>
                <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['referrals'];?>
</td>
                <td align="right"><?php if ($_smarty_tpl->tpl_vars['settings']->value['buy_referrals']=='yes'){?><a href="/?view=account&page=buyreferrals">[ <?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['buyrefs'];?>
 ]</a><?php }?></td>
            </tr>
        <?php if ($_smarty_tpl->tpl_vars['settings']->value['rent_referrals']=='yes'){?>
            <tr>
                <td width="20"><span class="system-icon user_red"></span></td>
                <td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['rentedrefs'];?>
</strong></td>
                <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['rented_referrals'];?>
</td>
                <td align="right"><a href="/?view=account&page=rentreferrals">[ <?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['rentrefs'];?>
 ]</a></td>
            </tr>
        <?php }?> 
            <tr>
                <td width="20"><span class="system-icon medal_gold_add"></span></td>
                <td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['refsearned'];?>
</strong></td>
                <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['refearnings'];?>
</td>
                <td align="right"></td>
            </tr>
        </table>
        <div class="widget-title"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['chart1'];?>
</div>
         <table width="100%" class="tbl-account" cellpadding="4">
            <tr>
                <td width="20"><span class="system-icon chart_line"></span></td>
                <td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['yourclicks'];?>
</strong></td>
                <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['clicks'];?>
</td>
            </tr>
            <tr>
                <td width="20"><span class="system-icon chart_curve"></span></td>
                <td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['yourrefclicks'];?>
</strong></td>
                <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['refclicks'];?>
</td>
            </tr>
        </table>  
    </div>
    
    <div id="tab-2">
          <div class="widget-title"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['adbalancestats'];?>
</div>
            <table width="100%" class="tbl-account" cellpadding="4">
                <tr>
                    <td width="20"><span class="system-icon flag_green"></span></td>
                    <td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['adcredits'];?>
</strong></td>
                    <td><strong><?php echo $_smarty_tpl->tpl_vars['user_info']->value['ad_credits'];?>
</strong></td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads">[ مدیریت ]</a></td>
                </tr>
        
                <?php if ($_smarty_tpl->tpl_vars['settings']->value['loginads_available']=='yes'){?>
                <tr>
                    <td width="20"><span class="system-icon flag_orange"></span></td>
                    <td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['loginad_credits'];?>
</strong></td>
                    <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['loginads_credits'];?>
</td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads&class=login_ads">[ مدیریت ]</a></td>
                </tr>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['settings']->value['ptsu_available']=='yes'){?>
                <tr>
                    <td width="20"><span class="system-icon flag_pink"></span></td>
                    <td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['ptsucredits'];?>
</strong></td>
                    <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['ptsu_credits'];?>
</td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads&class=ptsu_offers">[ مدیریت ]</a></td>
                </tr>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['settings']->value['bannerads_available']=='yes'){?>
                <tr>
                    <td width="20"><span class="system-icon flag_blue"></span></td>
                    <td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['bannercredits'];?>
</strong></td>
                    <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['banner_credits'];?>
</td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads&class=banner_ads">[ مدیریت ]</a></td>
                </tr>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['settings']->value['fads_available']=='yes'){?>
                <tr>
                    <td width="20"><span class="system-icon flag_purple"></span></td>
                    <td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['featuredadcredits'];?>
</strong></td>
                    <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['fads_credits'];?>
</td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads&class=featured_ads">[ مدیریت ]</a></td>
                </tr>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['settings']->value['flinks_available']=='yes'){?>
                <tr>
                    <td width="20"><span class="system-icon flag_red"></span></td>
                    <td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['featuredlinkcredits'];?>
</strong></td>
                    <td><?php echo $_smarty_tpl->tpl_vars['user_info']->value['flink_credits'];?>
</td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads&class=featured_link">[ مدیریت ]</a></td>
                </tr>
                <?php }?>
            </table>   
                 
    </div>
	<div id="tab-3">
        <!-- Content -->
        <script language="JavaScript" src="js/FusionCharts.js"></script>
        
        <table width="100%">
            <tr>
                <td width="50%">
        
            <div id="chartdiv" align="center"> 
                FusionCharts. </div>
              <script type="text/javascript">
                   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
                   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['chart1'];?>
' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'><?php echo $_smarty_tpl->tpl_vars['myclicks']->value;?>
</chart>");		   
                   chart.render("chartdiv");
                </script>
         
        
                </td>
        
                <td>
        
        
            <div id="chartdiv2" align="center"> 
                FusionCharts. </div>
              <script type="text/javascript">
                   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
                   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['chart2'];?>
' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'><?php echo $_smarty_tpl->tpl_vars['refclicks']->value;?>
</chart>");		   
                   chart.render("chartdiv2");
                </script>
         
        
                </td>
            </tr>
            
           <?php if ($_smarty_tpl->tpl_vars['settings']->value['rent_referrals']=='yes'){?> 
            <tr>
                <td><br />
        
            <div id="chartdiv3" align="center"> 
                FusionCharts. </div>
              <script type="text/javascript">
                   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
                   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['chart3'];?>
' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'><?php echo $_smarty_tpl->tpl_vars['rentedrefclicks']->value;?>
</chart>");		   
                   chart.render("chartdiv3");
                </script>
                
                </td>
                <td><br />
        
            <div id="chartdiv4" align="center"> 
                FusionCharts. </div>
              <script type="text/javascript">
                   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
                   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['chart4'];?>
' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='4' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'><?php echo $_smarty_tpl->tpl_vars['autopayclicks']->value;?>
</chart>");		   
                   chart.render("chartdiv4");
                </script>
                </td>
        
           </tr>
           <?php }?>
        </table>    
    </div>    
    <div id="tab-4">
        <div class="widget-title">ورود های ناموفق</div>
        <div class="widget-content">
        <?php if (!empty($_smarty_tpl->tpl_vars['loginfailure']->value)){?>
            <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['f'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['f']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['name'] = 'f';
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['loginfailure']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['f']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['f']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['f']['total']);
?>
                <div class="error_login">
                    <div><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['user_agent'];?>
:</strong> <?php echo $_smarty_tpl->tpl_vars['loginfailure']->value[$_smarty_tpl->getVariable('smarty')->value['section']['f']['index']]['agent'];?>
</div>
                    <div><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['ip_address'];?>
:</strong> <?php echo $_smarty_tpl->tpl_vars['loginfailure']->value[$_smarty_tpl->getVariable('smarty')->value['section']['f']['index']]['ip'];?>
</div>
                    <div><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['date'];?>
:</strong> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['loginfailure']->value[$_smarty_tpl->getVariable('smarty')->value['section']['f']['index']]['date'],"%d %B %Y %r");?>
</div>
                </div>
            <?php endfor; endif; ?>
        <?php }else{ ?>
            <?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['noinformationavailable'];?>

        <?php }?>
        </div>
    </div>
</div>



    


    
    



	<div style="width:1px; height:1px; float:left; overflow:hidden;"><?php echo $_smarty_tpl->tpl_vars['initmember']->value;?>
</div><?php }} ?>