<!-- Content -->
<div class="widget-main-title">{$lang.txt.personalsettings}</div>
<div class="widget-content">
<div id="errorbox" class="errorbox" style="display:none"></div>
{if !empty($user_info.new_email)}        
<div style="display:none" class="success_box" id="message_sent">{$lang.txt.personalsaved}</div>
<div style="display:none" class="success_box" id="message_sent2">{$lang.txt.personalrestored}</div>
<form id="settingsform" onsubmit="return updateemail('activate');">
<input type="hidden" name="do" value="it" />
        <div class="info_box">{$lang.txt.personalvalidatemsg|replace:"%email":$user_info.new_email}</div>
<table cellpadding="4" width="100%" class="widget-tbl">
	<tr>
    	<td align="right">{$lang.txt.activationid}:</td>
        <td><input type="text" name="code" id="aid" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="center">
        	<input type="submit" name="btn" value="{$lang.txt.send}" class="orange" />
            <input type="button" name="btn" value="{$lang.txt.cancel}" class="buttonblue" onclick="updateemail('restore')" />
        </td>
    </tr>
</table>  
</form>       
{else}
<form id="settingsform" onsubmit="return submitform(this.id);">
<input type="hidden" name="a" value="submit" />
<table cellpadding="4" width="100%" align="center" class="widget-tbl">
	<tr>
    	<td class="widget-title">{$lang.txt.personaldata}</td>
    </tr>
    <tr>
    	<td>
                <table cellpadding="4" width="100%">
                <tr>
                    <td align="right" width="50%">{$lang.txt.email}:</td>
                    <td><input type="text" name="email" id="email" value="{$user_info.email}" /></td>
                </tr>
                <tr>
                    <td align="right">{$lang.txt.acceptemail}:</td>
                    <td>
                            <input type="radio" name="aemail" value="yes" id="aemail_1" {if $user_info.acceptmails=='yes'}checked{/if} /><label for="aemail_1">{$lang.txt.yes}</label>
                            <input type="radio" name="aemail" value="no" id="aemail_2" {if $user_info.acceptmails=='no'}checked{/if} /><label for="aemail_2">{$lang.txt.no}</label>
                    </td>            
                </tr>
                </table>
    </td>
    </tr>
    {if $settings.message_system == 'yes'}
	<tr>
    	<td class="widget-title">{$lang.txt.message_system}</td>
    </tr> 
     <tr>
    	<td>
                <table cellpadding="4" width="100%">
                    <td align="right" width="50%">{$lang.txt.msgsystem_enabled}:</td>
                    <td>
                            <input type="radio" name="personal_msg" value="yes" id="msg_system_1" {if $user_info.personal_msg=='yes'}checked{/if} /><label for="msg_system_1">{$lang.txt.yes}</label>
                            <input type="radio" name="personal_msg" value="no" id="msg_system_2" {if $user_info.personal_msg=='no'}checked{/if} /><label for="msg_system_2">{$lang.txt.no}</label>
                    </td>            
                </tr>
                </table>
    </td>
    </tr>       
    {/if}
	<tr>
    	<td class="widget-title">Payment Method</td>
    </tr>
    <tr>
    	<td>
                <table cellpadding="4" width="100%">
                {section name=g loop=$gateway}
                <tr>
                    <td align="right" width="50%">  شماره کارت / شماره حساب برای تسویه حساب از طریق
                    {$gateway[g].name}:
                    </td>
                    <td><input type="text" name="gatewayid[{$gateway[g].id}]" value="{section name=n loop=$usrgateway}{if $usrgateway[n].id == $gateway[g].id}{$usrgateway[n].account}{/if}{/section}{$gateway[g].member}" /></td>
                </tr>
                {/section}
                </table>
    </td>
    </tr>

    
	<tr>
    	<td class="widget-title">{$lang.txt.updpassword}</td>
    </tr>
    <tr>
    	<td>
                <table cellpadding="4" width="100%">
                <tr>
                    <td align="right" width="50%">{$lang.txt.newpassword}:</td>
                    <td><input type="password" name="newpassword" id="newpassword" /></td>
                </tr>
                <tr>
                    <td align="right">{$lang.txt.newpasswordconfirm}:</td>
                    <td><input type="password" name="newpassword2" id="newpassword2" /></td>
                </tr>
                </table>
    </td>
    </tr>
    
	<tr>
    	<td class="widget-title">{$lang.txt.send}</td>
    </tr>
    <tr>
    	<td>
        <div class="info_box">{$lang.txt.newpasswordmsg}</div>
        <div class="padding5 " align="center"><input type="password" name="password" id="password" /></div>
        <div align="center" class="padding5 " style="margin-top:1px">
        <input type="submit" name="btn" value="{$lang.txt.send}" class="orange" />
        </div>
        </td>
    </tr>
</table>  
</form>

{/if}

</div>
<!-- End Content -->