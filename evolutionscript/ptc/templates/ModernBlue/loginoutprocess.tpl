{include file="header.tpl"}
<div class="site_content">
<!-- Content -->
<script type="text/javascript" src="js/link2progress.js"></script>	
	<script>
	var actionloginout = '{$loginout_process}';
	{literal}
		$(function(){
			loginoutprocess(actionloginout);
		});
	{/literal}
</script>
<div style="width:500px; margin:0 auto">
<div class="widget-main-title">
{if $loginout_process == 'login'}
	{$lang.txt.welcome_back} <strong>{$user_info.username}</strong>!
{else}
	{$lang.txt.good_bye}
{/if}
</div>
<div class="widget-content">
<div align="center">
{if $loginout_process == 'login'}
	{$lang.txt.please_wait_login}
{else}
	{$lang.txt.please_wait_logout}
{/if}
</div>

<table align="center">
	<tr><td>
                        <div class="progressbar" id="progress">
                            <div id="progressbar"></div>
                        </div>
	</td></tr>
</table>
</div>   
</div>
<!-- End Content -->


</div>
{include file="footer.tpl"}