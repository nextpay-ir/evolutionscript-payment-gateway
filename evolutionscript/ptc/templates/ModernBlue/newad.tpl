<!-- Content -->
<script>
function textCounter(field,field2,maxlimit)
{
 var countfield = document.getElementById(field2);
 if ( field.value.length > maxlimit ) {
  field.value = field.value.substring( 0, maxlimit );
  return false;
 } else {
  charleft = field.value.length;
  $("#"+field2).html(charleft);
 }
}
</script>
<div class="widget-main-title">{$page_title} {$lang.txt.creationform}</div>
<div class="menu-content">

<table width="100%" cellpadding="5">
    <tr>
    	<td>
<div style="display:none" id="message_sent">
    <div>{$lang.txt.adcreated}</div>
    <div style="margin-top:1px;">
    <input type="button" name="btn" value="{$lang.txt.continuebutton}" onclick="location.href='{$referrer}';">
    </div>
</div>



{if $page_id == 'ads'}
{if $user_info.ad_credits > 0}


<form class="formclass" method="post" id="create_ad" onsubmit="return createad('{$page_id}');">
<table width="100%" class="widget-tbl">
	<tr>
    	<td align="right" width="150">{$lang.txt.title}*</td>
        <td><input name="title" type="text" id="title" maxlength="{$settings.ptc_chars_title}" onkeyup="textCounter(this,'counter',{$settings.ptc_chars_title});" /> <span id="counter">0</span>/{$settings.ptc_chars_title} کاراکتر</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.subtitle}</td>
        <td><input name="subtitle" type="text" id="subtitle" maxlength="{$settings.ptc_chars_descr}" onkeyup="textCounter(this,'counter2',{$settings.ptc_chars_descr});" /> <span id="counter2">0</span>/{$settings.ptc_chars_descr} کاراکتر</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.targeturl}*</td>
        <td><input style="direction:ltr !important;" name="url" type="text" id="url" value="http://" maxlength="200" onkeyup="textCounter(this,'counter3',200);" /> <span id="counter3">0</span>/200 کاراکتر</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.imageurl}</td>
        <td><input style="direction:ltr !important;" name="imgurl" type="text" id="imgurl" value="http://" maxlength="200" onkeyup="textCounter(this,'counter4',200);" /> <span id="counter4">0</span>/200 کاراکتر</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.maxclicksperday}</td>
        <td><input name="clicks_day" type="text" maxlength="11" value="0" /> {$lang.txt.zerodisabled}</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.premiumonly}*</td>
        <td>
        	<div id="radio">
           <input type="radio" name="premium" value="yes" id="premium_yes" /> <label for="premium_yes">{$lang.txt.yes}</label>
           <input type="radio" name="premium" value="no" id="premium_no" checked /> <label for="premium_no">{$lang.txt.no}</label>
           </div>
        </td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.advalue}*</td>
        <td>
           <select name="advalue" id="advalue" style="width:300px;">
           {foreach item=advalue from=$listvalue}
           		<option value="{$advalue.id}">{$lang.txt.perclick} {$advalue.value} تومان - {$advalue.time} {$lang.txt.seconds}</option>
           {/foreach}
          </select>
        </td>
    </tr>
    <tr>
    	<td align="right" style="display:none !important;">{$lang.txt.targetcountry}</td>
        <td style="display:none !important;">
        
        <div class="celltop"><input type="checkbox" name="country_all" value="all" id="checkall" checked="checked"/>{$lang.txt.allcountries}</div>
        <div style="overflow:auto; height:100px">
        {foreach item=country from=$countrylist}
	        {if $country.name != '-'}
    	    <input type="checkbox" name="country[]" value="{$country.country}" class="checkall" checked="checked"/> {$country.country}<br />
            {/if}            
        {/foreach}
        </div>


        </td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.terms}*</td>
        <td><input type="checkbox" id="terms" name="terms" /><label for="terms">{$lang.txt.tosad}</label>
        </td>    
    </tr>
    <tr>
    	<td colspan="2" align="right">
        <input type="submit" name="btn" value="{$lang.txt.send}" class="orange" />
		<input type="button" name="btn" value="{$lang.txt.cancel}" onclick="location.href='{$referrer}'" />
        </td>
    </tr>
</table>  
</form>
{else}
    <div class="padding5">
    {$lang.txt.no_credits}
    </div>
<div class="padding5">
<input type="button" name="btn" value="{$lang.txt.continuebutton}" onclick="location.href='{$referrer}';">
</div>
{/if}


{elseif $page_id == 'banner_ads'}
{if $user_info.banner_credits > 0}
<form class="formclass" method="post" id="create_ad" onsubmit="return createad('{$page_id}');">
<table width="100%" class="widget-tbl">

	<tr>
    	<td align="right" width="150">{$lang.txt.title}*</td>
        <td><input name="title" type="text" id="title" maxlength="{$settings.bannerad_chars_title}" onkeyup="textCounter(this,'counter',{$settings.bannerad_chars_title});" /> <span id="counter">0</span>/{$settings.bannerad_chars_title} chars</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.targeturl}*</td>
        <td><input name="url" type="text" id="url" value="http://" maxlength="200" onkeyup="textCounter(this,'counter2',200);" /> <span id="counter2">0</span>/200 chars</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.bannerurl}*</td>
        <td><input name="banner" type="text" id="banner" value="http://" maxlength="200" onkeyup="textCounter(this,'counter3',200);" /> <span id="counter3">0</span>/200 chars</td>
    </tr>

    <tr>
    	<td align="right">{$lang.txt.terms}*</td>
        <td><input type="checkbox" id="terms" name="terms" /><label for="terms">{$lang.txt.tosad}</label>
        </td>    
    </tr>
    <tr>
    	<td colspan="2" align="right">
        <input type="submit" name="btn" value="{$lang.txt.send}" class="orange" />
		<input type="button" name="btn" value="{$lang.txt.cancel}" onclick="location.href='{$referrer}'" />
        </td>
    </tr>
</table>  
</form>
{else}
    <div class="padding5">
    {$lang.txt.no_credits}
    </div>
<div class="padding5">
<input type="button" name="btn" value="{$lang.txt.continuebutton}" onclick="location.href='{$referrer}';">
</div>
{/if}


{elseif $page_id == 'featured_ads'}
{if $user_info.fads_credits > 0}
<form class="formclass" method="post" id="create_ad" onsubmit="return createad('{$page_id}');">
<table width="100%" class="widget-tbl">
	<tr>
    	<td align="right" width="150">{$lang.txt.title}*</td>
        <td><input name="title" type="text" id="title"  maxlength="{$settings.featuredad_chars_title}" onkeyup="textCounter(this,'counter',{$settings.featuredad_chars_title});" /> <span id="counter">0</span>/{$settings.featuredad_chars_title} chars</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.targeturl}*</td>
        <td><input name="url" type="text" id="url" value="http://" maxlength="200" onkeyup="textCounter(this,'counter3',200);" /> <span id="counter3">0</span>/200 chars</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.featuredad}*</td>
        <td><input type="text" name="featuredad" id="featuredad" maxlength="{$settings.featuredad_chars_descr}" onkeyup="textCounter(this,'counter2',{$settings.featuredad_chars_descr});" /> <span id="counter2">0</span>/{$settings.featuredad_chars_descr} chars</td>
    </tr>

    <tr>
    	<td align="right">{$lang.txt.terms}*</td>
        <td><input type="checkbox" id="terms" name="terms" /><label for="terms">{$lang.txt.tosad}</label>
        </td>    
    </tr>
    <tr>
    	<td colspan="2" align="right">
        <input type="submit" name="btn" value="{$lang.txt.send}" class="orange" />
		<input type="button" name="btn" value="{$lang.txt.cancel}" onclick="location.href='{$referrer}'" />
        </td>
    </tr>
</table>  
</form>
{else}
    <div class="padding5">
    {$lang.txt.no_credits}
    </div>
<div class="padding5">
<input type="button" name="btn" value="{$lang.txt.continuebutton}" onclick="location.href='{$referrer}';">
</div>
{/if}


{elseif $page_id == 'featured_link'}
{if $user_info.flink_credits > 0}
<form class="formclass" method="post" id="create_ad" onsubmit="return createad('{$page_id}');">
<table width="100%" class="widget-tbl">
	<tr>
    	<td align="right" width="150">{$lang.txt.title}*</td>
        <td><input name="title" type="text" id="title" maxlength="{$settings.featuredlink_chars_title}" onkeyup="textCounter(this,'counter',{$settings.featuredlink_chars_title});" /> <span id="counter">0</span>/{$settings.featuredlink_chars_title} chars</td>
    </tr>
    
    <tr>
    	<td align="right">{$lang.txt.targeturl}*</td>
        <td><input name="url" type="text" id="url" value="http://" maxlength="200" onkeyup="textCounter(this,'counter2',200);" /> <span id="counter2">0</span>/200 chars</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.terms}*</td>
        <td><input type="checkbox" id="terms" name="terms" /><label for="terms">{$lang.txt.tosad}</label>
        </td>    
    </tr>
    <tr>
    	<td colspan="2" align="right">
        <input type="submit" name="btn" value="{$lang.txt.send}" class="orange" />
		<input type="button" name="btn" value="{$lang.txt.cancel}" onclick="location.href='{$referrer}'" />
        </td>
    </tr>
</table>  
</form>
{else}
    <div class="padding5">
    {$lang.txt.no_credits}
    </div>
<div class="padding5">
<input type="button" name="btn" value="{$lang.txt.continuebutton}" onclick="location.href='{$referrer}';">
</div>
{/if}


{elseif $page_id == 'login_ads'}
{if $user_info.loginads_credits > 0}
<form class="formclass" method="post" id="create_ad" onsubmit="return createad('{$page_id}');">
<table width="100%" class="widget-tbl">
	<tr>
    	<td align="right" width="150">{$lang.txt.title}*</td>
        <td><input name="title" type="text" id="title" maxlength="{$settings.loginad_chars_title}" onkeyup="textCounter(this,'counter',{$settings.loginad_chars_title});" /> <span id="counter">0</span>/{$settings.loginad_chars_title} chars</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.targeturl}*</td>
        <td><input name="url" type="text" id="url" value="http://" maxlength="200" onkeyup="textCounter(this,'counter2',200);" /> <span id="counter2">0</span>/200 chars</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.bannerurl}*</td>
        <td><input name="banner" type="text" id="banner" value="http://" maxlength="200" onkeyup="textCounter(this,'counter3',200);" /> <span id="counter3">0</span>/200 chars</td>
    </tr>
    <tr>
    	<td align="right">{$lang.txt.terms}*</td>
        <td><input type="checkbox" id="terms" name="terms" /><label for="terms">{$lang.txt.tosad}</label>
        </td>    
    </tr>
    <tr>
    	<td colspan="2" align="right">
        <input type="submit" name="btn" value="{$lang.txt.send}" class="orange" />
		<input type="button" name="btn" value="{$lang.txt.cancel}" onclick="location.href='{$referrer}'" />
        </td>
    </tr>
</table>  
</form>
{else}
    <div class="padding5">
    {$lang.txt.no_credits}
    </div>
<div class="padding5">
<input type="button" name="btn" value="{$lang.txt.continuebutton}" onclick="location.href='{$referrer}';">
</div>
{/if}



{elseif $page_id == 'ptsu_offers'}
{if $user_info.ptsu_credits > 0}
<form class="formclass" method="post" id="create_ad" onsubmit="return createad('{$page_id}');">
<table width="100%" class="widget-tbl">
	<tr>
    	<td align="right" width="150">{$lang.txt.title}*</td>
        <td><input name="title" type="text" id="title" maxlength="{$settings.ptsu_chars_title}" onkeyup="textCounter(this,'counter',{$settings.ptsu_chars_title});" /> <span id="counter">0</span>/{$settings.ptsu_chars_title} chars</td>
    </tr>

	<tr>
    	<td align="right" width="150">{$lang.txt.subtitle}*</td>
        <td><input name="subtitle" type="text" id="subtitle" maxlength="{$settings.ptsu_chars_descr}" onkeyup="textCounter(this,'counter2',{$settings.ptsu_chars_descr});" /> <span id="counter2">0</span>/{$settings.ptsu_chars_descr} chars</td>
    </tr>
    
	<tr>
    	<td align="right" width="150">{$lang.txt.instructions}</td>
        <td><textarea name="instructions" id="instructions" style="height:100px; width:95%"></textarea></td>
    </tr>
    
	<tr>
    	<td align="right" width="150">{$lang.txt.targeturl}*</td>
        <td><input name="url" type="text" id="url" value="http://" /></td>
    </tr>
    
	<tr>
    	<td align="right" width="150">{$lang.txt.advalue}*</td>
        <td>
           <select name="advalue" id="advalue">
           {foreach item=advalue from=$listvalue}
           		<option value="{$advalue.id}">{$advalue.value} تومان</option>
           {/foreach}
          </select>
        </td>
    </tr>
    
	<tr>
    	<td align="right" width="150">{$lang.txt.premiumonly}*</td>
        <td>
   		<div id="radio">
           <input type="radio" name="premium" value="yes" id="premium_yes" /> <label for="premium_yes">{$lang.txt.yes}</label>
           <input type="radio" name="premium" value="no" id="premium_no" checked /> <label for="premium_no">{$lang.txt.no}</label>
        </div>
        </td>
    </tr>
    
	<tr>
    	<td align="right" width="150">{$lang.txt.targetcountry}</td>
        <td>
        <div class="celltop"><input type="checkbox" name="country_all" value="all" id="checkall" />{$lang.txt.allcountries}</div>
        <div style="overflow:auto; height:100px">
        {foreach item=country from=$countrylist}
	        {if $country.name != '-'}
    	    <input type="checkbox" name="country[]" value="{$country.country}" class="checkall" /> {$country.country}<br />
            {/if}            
        {/foreach}
        </div>
        </td>
    </tr>
    
	<tr>
    	<td align="right" width="150">{$lang.txt.terms}</td>
        <td><input type="checkbox" id="terms" name="terms" /><label for="terms">{$lang.txt.tosad}</label></td>
    </tr>

    <tr>
    	<td colspan="2" align="right">
        <input type="submit" name="btn" value="{$lang.txt.send}" class="orange" />
		<input type="button" name="btn" value="{$lang.txt.cancel}" onclick="location.href='{$referrer}'" />
        </td>
    </tr>
</table>  
</form>
{else}
    <div class="padding5">
    {$lang.txt.no_credits}
    </div>
    
<div class="padding5">
<input type="button" name="btn" value="{$lang.txt.continuebutton}" onclick="location.href='{$referrer}';">
</div>
{/if}

{/if}

</td>
</tr>
</table>


</div>
<!-- End Content -->