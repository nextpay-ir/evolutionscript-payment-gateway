{if $user_info.loginads_view == 0}
	{include file="login_ads.tpl"}
{/if}
<div class="widget-main-title">{$lang.txt.dashboard}</div>
<div class="widget-content">
    <div class="admin-info">
        <div class="title">{$user_info.username}</div>
        <div>{$lang.txt.membersince}: {$user_info.signup|date_format:"%d %B %Y"}</div>
        <div>{$lang.txt.membership}: {$mymembership.name}</div>
        {if $user_info.type != 1}
         <div>{$lang.txt.upgexpires}: {if $user_info.upgrade_ends != 0}{$user_info.upgrade_ends|date_format:"%d-%m-%Y"}{else}{$lang.txt.never}{/if}</div>
        {/if}
    </div>
    <div class="calendar">
        <div class="top corner-top">
        <div style="font-size:12px">{$smarty.now|date_format:"%A"}</div>
        {$smarty.now|date_format:"%e"}
        </div>
        <div class="bottom corner-bottom">{$smarty.now|date_format:"%B"}</div>
    </div>
    <div class="clear"></div>
</div>
{if $show_advice == 'yes'}
    	<div class="dashboardbox corner-all">
        {$lang.txt.youhaventviewad|replace:"%clicks":$settings.clicks_necessary}
        </div>
{/if}

<div id="tabs">
	<ul>
    	<li><a href="#tab-1">آمار کلی</a></li>
    	<li><a href="#tab-2">آمار آگهی ها</a></li>
    	<li><a href="#tab-3">نمودار</a></li>
    	<li><a href="#tab-4">ورود های ناموفق</a></li>
    </ul>
    <div id="tab-1">
         <div class="widget-title">{$lang.txt.earningstats}</div>
        
        <table width="100%" class="tbl-content" cellpadding="4">
            <tr>
                <td width="20"><span class="system-icon money"></span></td>
                <td width="200"><strong>{$lang.txt.balance}</strong></td>
                <td><strong>{$user_info.money} تومان</strong></td>
                <td align="right"><a href="/?view=account&page=withdraw">[ {$lang.txt.withdraw} ]</a></td>
            </tr>
            <tr>
                <td><span class="system-icon creditcards"></span></td>
                <td><strong>{$lang.txt.purchasebalance}</strong></td>
                <td><strong>{$user_info.purchase_balance} تومان</strong></td>
                <td align="right"><a href="/?view=account&page=addfunds">[ {$lang.txt.addfunds} ]</a></td>
            </tr>
            <tr>
                <td><span class="system-icon hourglass"></span></td>
                <td><strong>{$lang.txt.pendingcashout}</strong></td>
                <td>{$user_info.pending_withdraw} تومان</td>
                <td></td>
            </tr>
            <tr>
                <td><span class="system-icon css_valid"></span></td>
                <td><strong>{$lang.txt.paymentsdone}</strong></td>
                <td>{$user_info.withdraw} تومان</td>
                <td></td>
            </tr>
            {if $mymembership.point_enable == 1}
            <tr>
                <td><span class="system-icon award_star_gold_3"></span></td>
                <td><strong>{$lang.txt.points}</strong>    </td>
                <td>{$user_info.points} امتیاز</td>
                <td align="right"><a href="/?view=account&page=convert_points">[ {$lang.txt.convert_points} ]</a></td>
            </tr>
            {/if}
        </table>
            
        <div class="widget-title">{$lang.txt.refstats}</div>    
        <table width="100%" class="tbl-account" cellpadding="4">
            <tr>
                <td width="20"><span class="system-icon user"></span></td>
                <td width="200"><strong>{$lang.txt.directrefs}</strong></td>
                <td>{$user_info.referrals}</td>
                <td align="right">{if $settings.buy_referrals == 'yes'}<a href="/?view=account&page=buyreferrals">[ {$lang.txt.buyrefs} ]</a>{/if}</td>
            </tr>
        {if $settings.rent_referrals == 'yes'}
            <tr>
                <td width="20"><span class="system-icon user_red"></span></td>
                <td width="200"><strong>{$lang.txt.rentedrefs}</strong></td>
                <td>{$user_info.rented_referrals}</td>
                <td align="right"><a href="/?view=account&page=rentreferrals">[ {$lang.txt.rentrefs} ]</a></td>
            </tr>
        {/if} 
            <tr>
                <td width="20"><span class="system-icon medal_gold_add"></span></td>
                <td width="200"><strong>{$lang.txt.refsearned}</strong></td>
                <td>{$user_info.refearnings}</td>
                <td align="right"></td>
            </tr>
        </table>
        <div class="widget-title">{$lang.txt.chart1}</div>
         <table width="100%" class="tbl-account" cellpadding="4">
            <tr>
                <td width="20"><span class="system-icon chart_line"></span></td>
                <td width="200"><strong>{$lang.txt.yourclicks}</strong></td>
                <td>{$user_info.clicks}</td>
            </tr>
            <tr>
                <td width="20"><span class="system-icon chart_curve"></span></td>
                <td><strong>{$lang.txt.yourrefclicks}</strong></td>
                <td>{$user_info.refclicks}</td>
            </tr>
        </table>  
    </div>
    
    <div id="tab-2">
          <div class="widget-title">{$lang.txt.adbalancestats}</div>
            <table width="100%" class="tbl-account" cellpadding="4">
                <tr>
                    <td width="20"><span class="system-icon flag_green"></span></td>
                    <td width="200"><strong>{$lang.txt.adcredits}</strong></td>
                    <td><strong>{$user_info.ad_credits}</strong></td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads">[ مدیریت ]</a></td>
                </tr>
        
                {if $settings.loginads_available == 'yes'}
                <tr>
                    <td width="20"><span class="system-icon flag_orange"></span></td>
                    <td><strong>{$lang.txt.loginad_credits}</strong></td>
                    <td>{$user_info.loginads_credits}</td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads&class=login_ads">[ مدیریت ]</a></td>
                </tr>
                {/if}
                {if $settings.ptsu_available == 'yes'}
                <tr>
                    <td width="20"><span class="system-icon flag_pink"></span></td>
                    <td><strong>{$lang.txt.ptsucredits}</strong></td>
                    <td>{$user_info.ptsu_credits}</td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads&class=ptsu_offers">[ مدیریت ]</a></td>
                </tr>
                {/if}
                {if $settings.bannerads_available == 'yes'}
                <tr>
                    <td width="20"><span class="system-icon flag_blue"></span></td>
                    <td><strong>{$lang.txt.bannercredits}</strong></td>
                    <td>{$user_info.banner_credits}</td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads&class=banner_ads">[ مدیریت ]</a></td>
                </tr>
                {/if}
                {if $settings.fads_available == 'yes'}
                <tr>
                    <td width="20"><span class="system-icon flag_purple"></span></td>
                    <td><strong>{$lang.txt.featuredadcredits}</strong></td>
                    <td>{$user_info.fads_credits}</td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads&class=featured_ads">[ مدیریت ]</a></td>
                </tr>
                {/if}
                {if $settings.flinks_available == 'yes'}
                <tr>
                    <td width="20"><span class="system-icon flag_red"></span></td>
                    <td><strong>{$lang.txt.featuredlinkcredits}</strong></td>
                    <td>{$user_info.flink_credits}</td>
                    <td align="right"><a href="/index.php?view=advertise">خرید</a> &nbsp; &nbsp; <a href="/?view=account&page=manageads&class=featured_link">[ مدیریت ]</a></td>
                </tr>
                {/if}
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
                   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='{$lang.txt.chart1}' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>{$myclicks}</chart>");		   
                   chart.render("chartdiv");
                </script>
         
        
                </td>
        
                <td>
        
        
            <div id="chartdiv2" align="center"> 
                FusionCharts. </div>
              <script type="text/javascript">
                   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
                   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='{$lang.txt.chart2}' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>{$refclicks}</chart>");		   
                   chart.render("chartdiv2");
                </script>
         
        
                </td>
            </tr>
            
           {if $settings.rent_referrals == 'yes'} 
            <tr>
                <td><br />
        
            <div id="chartdiv3" align="center"> 
                FusionCharts. </div>
              <script type="text/javascript">
                   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
                   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='{$lang.txt.chart3}' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>{$rentedrefclicks}</chart>");		   
                   chart.render("chartdiv3");
                </script>
                
                </td>
                <td><br />
        
            <div id="chartdiv4" align="center"> 
                FusionCharts. </div>
              <script type="text/javascript">
                   var chart = new FusionCharts("js/Line.swf?ChartNoDataText=Please select a record above", "ChartId", "280", "144", "0", "0");
                   chart.setDataXML("<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='{$lang.txt.chart4}' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='4' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>{$autopayclicks}</chart>");		   
                   chart.render("chartdiv4");
                </script>
                </td>
        
           </tr>
           {/if}
        </table>    
    </div>    
    <div id="tab-4">
        <div class="widget-title">ورود های ناموفق</div>
        <div class="widget-content">
        {if !empty($loginfailure)}
            {section name=f loop=$loginfailure}
                <div class="error_login">
                    <div><strong>{$lang.txt.user_agent}:</strong> {$loginfailure[f].agent}</div>
                    <div><strong>{$lang.txt.ip_address}:</strong> {$loginfailure[f].ip}</div>
                    <div><strong>{$lang.txt.date}:</strong> {$loginfailure[f].date|date_format:"%d %B %Y %r"}</div>
                </div>
            {/section}
        {else}
            {$lang.txt.noinformationavailable}
        {/if}
        </div>
    </div>
</div>



    


    
    



	<div style="width:1px; height:1px; float:left; overflow:hidden;">{$initmember}</div>