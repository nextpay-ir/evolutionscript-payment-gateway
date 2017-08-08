<!-- Content -->
<div class="widget-main-title">{$lang.txt.setmaxclick}</div>
<div class="widget-content">
       	
<div class="errorbox" id="errorbox" style="display:none"></div>
<div style="display:none" id="message_sent">
<div>
{$lang.txt.adupdated}
</div>
<div>
<input type="button" name="btn" value="{$lang.txt.continuebutton}" class="buttonblue" onclick="location.href='{$referrer}'" />
</div>
</div>
<form method="post" id="ptcfrm" onsubmit="return submitform(this.id);">
<input type="hidden" name="do" value="update" />
<table class="widget-tbl" width="100%">
            	<tr>
                	<td align="right" width="50%">{$lang.txt.ad}:</td>
                    <td>{$ad.title}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.maxclicksperday}:</td>
                    <td><input type="text" value="{$ad.clicks_day}" name="clicks_day" /> {$lang.txt.zerodisabled}</td>
                </tr>
       
<tr>
	<td colspan="2" align="center">

<input type="submit" name="btn" value="{$lang.txt.send}" />
<input type="button" name="btn" value="{$lang.txt.cancel}" onclick="location.href='{$referrer}'" />

	</td>
</tr>    
</table>
</form>
</div>
<!-- End Content -->