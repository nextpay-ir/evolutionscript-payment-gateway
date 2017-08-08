<!-- Content -->
<div class="widget-main-title">{$page_title} - {$lang.txt.allocatecredits}</div>
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
<form class="formclass" method="post" id="allocateform" onsubmit="return allocatead();">
<input type="hidden" name="adid" value="{$aditem.id}" id="adid" />
<input type="hidden" name="class" value="{$page_id}" />
<input type="hidden" name="a" value="submit" />
<table class="widget-tbl" width="100%">
{if $page_id == 'ads'}
           
            	<tr>
                	<td align="right" width="50%">{$lang.txt.ad}:</td>
                    <td>{$aditem.title}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.mycredits}:</td>
                    <td>{$user_info.ad_credits}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.creditstoallocate}:</td>
                    <td><input type="text" value="0" onkeyup="calculatecredits('{$creditcost}');" name="allocate" id="allocate" /></td>
                </tr>
                
                <tr>
                	<td align="right">{$lang.txt.cost} (Value = {$advalue} تومان = {$creditcost} credits):</td>
                    <td><input type="text" value="0" id="creditcost" name="creditcost" onkeyup="recalculatecredits('{$creditcost}');" disabled  /></td>
                </tr>
            
{elseif $page_id == 'banner_ads'}
            	<tr>
                	<td align="right" width="50%">{$lang.txt.ad}:</td>
                    <td>{$aditem.title|stripslashes}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.mycredits}:</td>
                    <td>{$user_info.banner_credits}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.creditstoallocate}:</td>
                    <td><input type="text" value="0" name="allocate" id="allocate" /></td>
                </tr>
                
                

{elseif $page_id == 'featured_ads'}
            	<tr>
                	<td align="right" width="50%">{$lang.txt.ad}:</td>
                    <td>{$aditem.title|stripslashes}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.mycredits}:</td>
                    <td>{$user_info.fads_credits}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.creditstoallocate}:</td>
                    <td><input type="text" value="0" name="allocate" id="allocate" /></td>
                </tr>


{elseif $page_id == 'featured_link'}
            	<tr>
                	<td align="right" width="50%">{$lang.txt.ad}:</td>
                    <td>{$aditem.title|stripslashes}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.mycredits}:</td>
                    <td>{$user_info.flink_credits}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.creditstoallocate}:</td>
                    <td><input type="text" value="0" name="allocate" id="allocate" /></td>
                </tr>
{elseif $page_id == 'login_ads'}
            	<tr>
                	<td align="right" width="50%">{$lang.txt.ad}:</td>
                    <td>{$aditem.title}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.mycredits}:</td>
                    <td>{$user_info.loginads_credits}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.creditstoallocate}:</td>
                    <td><input type="text" value="0" name="allocate" id="allocate" /></td>
                </tr>
{elseif $page_id == 'ptsu_offers'}
            	<tr>
                	<td align="right" width="50%">{$lang.txt.ad}:</td>
                    <td>{$aditem.title|stripslashes}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.mycredits}:</td>
                    <td>{$user_info.ptsu_credits}</td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.creditstoallocate}:</td>
                    <td><input type="text" value="0" onkeyup="calculatecredits('{$creditcost}');" name="allocate" id="allocate" /></td>
                </tr>
                <tr>
                	<td align="right">{$lang.txt.cost}  ({$creditcost} {$lang.txt.credits} = 1 {$lang.txt.slots})</td>
                    <td><input type="text" value="0" id="creditcost" name="creditcost" onkeyup="recalculatecredits('{$creditcost}');" disabled  /></td>
                </tr>
{/if}

<tr>
	<td colspan="2" align="center">

<input type="submit" name="btn" class="orange" value="{$lang.txt.allocatecredits}" />
<input type="button" name="btn" class="buttonblue" value="{$lang.txt.cancel}" onclick="location.href='{$referrer}'" />

	</td>
</tr>    
</table>
</form>
</div>
<!-- End Content -->