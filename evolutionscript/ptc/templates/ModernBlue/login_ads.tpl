{if $settings.loginads_available == 'yes'}
 <script>
$(function() {
	$( "#loginads-dialog" ).dialog({
		width: 500,
		resizable: false,
		modal: true,
		buttons: [{
			text: '{$lang.txt.close}',
			click: function() {
				$( this ).dialog( "close" );
			}
		}]
	});
});
</script>

<div id="loginads-dialog" title="{$settings.site_name} News">
{if $loginads}
<div style="padding-bottom:10px;">
<h3>{$loginads.title}</h3>
{$loginads.message}
</div>
{/if}
<p>
{section name=foo loop=$settings.loginads_max}
	<div style="padding-bottom:5px; text-align:center">{if showloginads()}{/if}</div>
{/section}
</p>
</div>
{/if}