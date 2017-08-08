<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height:100%">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$settings.site_title} - {$lang.txt.validatingad}</title>
<link href="templates/{$template_name}/surfbar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript" src="./js/link2progress.js"></script>
<script type="text/javascript" src="./js/ptcevolution.js"></script>
<script type="text/javascript">	
var secs=15;
var tway=1;
var adid = '{$smarty.request.id|escape:'htmlall'}';
var adwait = '{$lang.txt.adwait}';
var adcredited = '{$lang.txt.advalidated}';
$(document).ready(ptcevolution_surfer);
</script>
</head>
<body onload="vshowadbar('{$error_msg}');">

<div class="surfbar">
	<div class="logo"></div>    
	<div id="surfbar"></div>
    <div id="vnumbers" style="display:none" align="center">
    	<table>
        	<tr>
            	<td style="font-size:20px" valign="middle">Click the upside<br />down picture</td>
                <td valign="middle" style="border:1px dashed;">
<map name="Map" id="Map">
<area shape="rect" coords="0,0,50,50" href="javascript:void(0);" onclick="vendprogress(1);" />
<area shape="rect" coords="100,0,50,50" href="javascript:void(0);" onclick="vendprogress(2);" />
<area shape="rect" coords="150,0,50,50" href="javascript:void(0);" onclick="vendprogress(3);" />
<area shape="rect" coords="200,0,50,50" href="javascript:void(0);" onclick="vendprogress(4);" />
<area shape="rect" coords="250,0,50,50" href="javascript:void(0);" onclick="vendprogress(5);" />
<area shape="rect" coords="300,0,50,50" href="javascript:void(0);" onclick="vendprogress(6);" />
</map>                
<img src="modules.php?m=surfer&show=captcha" usemap="#Map" border="0" />                </td>
            </tr>
        </table>
    </div>
     <div class="banner">{if showrotatingbanners()}{/if}</div>
</div>
<iframe src="{$ad_info.url}" id="pgl" class="surfer_frame" frameborder = "0"></iframe>

</body>
</html>