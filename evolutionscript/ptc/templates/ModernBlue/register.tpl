{include file="header.tpl"}
<!-- Content -->
<div class="PageTitle"><h1>{$lang.txt.registration}</h1></div>
<div class="site_content">
<div style="width:600px; margin:0 auto">

<div style="display:none" id="message_sent">
{if $settings.register_activation=='yes'}
<div align="center">
<h3>{$lang.txt.activationrequired|replace:"%site":$settings.site_name}</h3>
{$lang.txt.registrationmsg}<br />
</div>
{else}
<div align="center">
<h3>{$lang.txt.welcometo|replace:"%site":$settings.site_name}</h3>
{$lang.txt.activationmsg|replace:"%site":$settings.site_name}<br />
</div>
{/if}
</div>          
 <form method="post" id="registerform" onsubmit="return submitform(this.id);">
<input type="hidden" name="token" value="{getToken('register')}" />
<div class="widget-title">{$lang.txt.generalinformation}</div>
<table width="100%" class="widget-tbl">
    <tr>
        <td width="200" align="right">{$lang.txt.fullname}:</td>
        <td><input type="text" name="fullname" /></td>
    </tr>
    <tr>
        <td align="right">{$lang.txt.email}:</td>
        <td><input type="text" name="email" /></td>
    </tr>
    <tr>
        <td align="right">{$lang.txt.confirmemail}:</td>
        <td><input type="text" name="email2" /></td>
    </tr>
    <tr>
        <td align="right">{$lang.txt.username}:</td>
        <td><input type="text" name="username" /></td>
    </tr>
    <tr>
        <td align="right">{$lang.txt.password}:</td>
        <td><input type="password" name="password" id="rpassword" /></td>
    </tr>
    <tr>
        <td align="right">{$lang.txt.confirmpassword}:</td>
        <td><input type="password" name="password2" id="rpassword2" /></td>
    </tr>
    {if !empty($referrer)}
    <tr>
        <td align="right">{$lang.txt.referrer}:</td>
        <td><input type="text" name="referrer" id="referrer" value="{$referrer}" disabled="disabled" /></td>
    </tr>
    {/if}
</table> 
{if $settings.captcha_register=='yes' && $settings.captcha_type != 0}
<div class="widget-title">{$lang.txt.imgverification}</div>
<table width="100%" class="widget-tbl">
	<tr>
    	<td align="center">{$captcha}</td>
	</tr>
</table>
{/if}    
        <div class="widget-title">{$lang.txt.terms}</div>
<table width="100%" class="widget-tbl">
    <tr>
    	<td align="right" width="200">{$lang.txt.terms}</td>
        <td><input type="checkbox" name="terms" /> {$lang.txt.agreeterms|replace:"%site":$settings.site_name}</td>
    </tr>
    <tr>
    	<td></td>
        <td><input type="submit" name="login" value="{$lang.txt.registersend}" /> <input type="hidden" name="a" value="submit" /></td>
    </tr>
</table>
</form>
</div>


</div>
<!-- End Content -->
{include file="footer.tpl"}