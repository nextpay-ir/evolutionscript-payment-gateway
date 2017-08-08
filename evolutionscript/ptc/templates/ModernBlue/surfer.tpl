<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height:100%">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$settings.site_title} - {$lang.txt.surfertitle}</title>
<link href="templates/{$template_name}/surfbar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="js/link2progress.js"></script>
{if $settings.force_viewads == 1}
<script type="text/javascript" src="js/nsurfer.js"></script>
{else}
<script type="text/javascript" src="js/surfer.js"></script>
{/if}
<script type="text/javascript">	
$(function(){
	looknrun('{$settings.autoloadad_secs}', '{$error_msg}');
});
{if $ad_info.time == ''}
var secs=0;
{else}
var secs={$ad_info.time};
{/if}
var tway=1;
var activeWindowsAd = 'true';
var activeFrameAd = 'true';
var adtk = '{$smarty.request.t|escape:'htmlall'}';
var sitename = '{$settings.site_name}';
var siteurl = '{$ad_info.url}';
var adwait = '{$lang.txt.adwait}';
var adcredited = '{$lang.txt.accountcredited}';
</script>
</head>
<body>
<div class="surfbar">
	<div class="logo"></div>    
	<div id="surfbar"></div>
    
    <div id="vnumbers" style="display:none">
    	<table>
        	<tr>
				<td style="font-size:16px" valign="middle">Click the upside<br />down picture</td>
                <td valign="middle">

<map name="Map" id="Map">
<area shape="rect" coords="0,0,50,50" href="javascript:void(0);" onclick="endprogress(1);" />
<area shape="rect" coords="100,0,50,50" href="javascript:void(0);" onclick="endprogress(2);" />
<area shape="rect" coords="150,0,50,50" href="javascript:void(0);" onclick="endprogress(3);" />
<area shape="rect" coords="200,0,50,50" href="javascript:void(0);" onclick="endprogress(4);" />
<area shape="rect" coords="250,0,50,50" href="javascript:void(0);" onclick="endprogress(5);" />
<area shape="rect" coords="300,0,50,50" href="javascript:void(0);" onclick="endprogress(6);" />
</map>                
<img src="modules.php?m=surfer&show=captcha" usemap="#Map" border="0" />
                </td>
            </tr>
        </table> 
    </div>
    
    <div class="banner">{if showrotatingbanners()}{/if}</div>
</div>

<iframe src="{$ad_info.url}" id="pgl" class="surfer_frame" frameborder = "0"></iframe>
  <div id="mask"></div>
</div>
</body>
</html>