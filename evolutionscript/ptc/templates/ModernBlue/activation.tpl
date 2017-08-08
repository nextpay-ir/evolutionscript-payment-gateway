{include file="header.tpl"}
<!-- Content -->
<div class="widget-main-title">{$lang.txt.activationtitle}</div>
<div class="widget-content">

    <div style="display:none" id="message_sent" align="center">
    <h3>{$lang.txt.welcometo|replace:"%site":$settings.site_name}</h3>
    {$lang.txt.activationmsg|replace:"%sitename":$settings.site_name}
    </div> 



<form method="post" id="activationform" onsubmit="return submitform(this.id);">
<input type="hidden" name="a" value="submit" />
   <table cellpadding="4" width="400" align="center">
   	<tr>
    	<td align="right">{$lang.txt.username}:</td>
        <td><input type="text" name="username" id="ausername" class="primary textbox" value="
{$smarty.request.username|escape:'htmlall'}" style="width:100%" /></td>
    </tr>
   	<tr>
    	<td align="right">{$lang.txt.activationid}:</td>
        <td><input type="text" name="code" id="aid" class="primary textbox" value="{$smarty.request.i|escape:'htmlall'}" style="width:100%" /></td>
    </tr>
    <tr>
    	<td></td>
    	<td>
        <input type="submit" name="send" value="{$lang.txt.send}" class="orange" />
        </td>
    </tr>
   </table>


</form>    

				</div>               

<!-- End Content -->
{include file="footer.tpl"}