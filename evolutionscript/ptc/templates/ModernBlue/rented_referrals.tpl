<script>
$(document).on('click','input[type=checkbox]', function(){
	showextensionbar();
});
var recycle_price = {$recycle_price};
var renew_price = {$renew_price};
var rental_balance = {$user_info.purchase_balance};
var noenoughfunds = '{$lang.txt.nofoundspb}';
var totaltopay = '{$lang.txt.totaltopay}';

var ref_discount = new Array();
var ref_extension = new Array();
{section name=d loop=$ref_discount}                  
ref_discount[{$ref_discount[d].id}] = {$ref_discount[d].discount};
ref_extension[{$ref_discount[d].id}] = {$ref_discount[d].days};
{/section}
</script>

<!-- Content -->
{literal}
<script>
function changepage(){
	var rpage = $('#pageselector').val();
{/literal}
{literal}	
	location.href = 'index.php?view=account&page=rented_referrals&sortby={$sortby}&tsort={$tsort}&p='+rpage;
}
</script>
{/literal}
<div class="widget-main-title">{$lang.txt.rentedrefs}</div>
<div class="widget-content">
	<div class="info_box">{$lang.txt.autopay}:
{if $user_info.autopay == 'no'}
<span style="color:red; font-weight:bold">{$lang.txt.disable}</span>, <a href="index.php?view=account&page=rented_referrals&autopay=on">{$lang.txt.clickheretochange}</a>
{else}
<span style="color:green; font-weight:bold">{$lang.txt.enable}</span>, <a href="index.php?view=account&page=rented_referrals&autopay=off">{$lang.txt.clickheretochange}</a>
{/if}</div>

<form method="post" onsubmit="return submitform(this.id);" id="reflist">
<input type="hidden" name="do" value="rentact" />
<table width="100%" class="widget-tbl">
<tr class="titles">
        <td align="center">{$paginator->linkorder('id', $lang.txt.ref)}</td>
        <td align="center">{$paginator->linkorder('rented_time', $lang.txt.refsince)}</td>
        <td align="center">{$paginator->linkorder('rented_expires', $lang.txt.expires)}</td>
        <td align="center">{$paginator->linkorder('rented_lastclick', $lang.txt.lastclick)}</td>
        <td align="center">{$paginator->linkorder('rented_clicks', $lang.txt.clicks)}</td>
        <td align="center">{$paginator->linkorder('rented_earned', $lang.txt.earned)}</td>
        <td align="center">{$paginator->linkorder('rented_avg', $lang.txt.avarage)}</td>
        <td align="center"><input type="checkbox" id="checkall" /></td>
    </tr>

        {foreach item=item from=$thelist}
            <tr>
                <td align="center">R{$item.id}</td>
                <td align="center">{$item.rented_time|date_format:"%e %B %Y %r"}</td>
                <td align="center">{$item.days_left} days</td>
                <td align="center">{if $item.rented_lastclick != 0}{$item.rented_lastclick|date_format:"%e %B %Y %r"}{else}{$lang.txt.never}{/if}</td>
                <td align="center">{$item.rented_clicks}</td>
                <td align="center">{$item.rented_earned}</td>
                <td align="center">{$item.avarage}</td>
                <td align="center"><input type="checkbox" name="ref[]" value="{$item.id}" class="checkall" /></td>
            </tr>
        {/foreach}    

        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="10" align="center">{$lang.txt.no_rentedrefs}</td>
        </tr>
        {/if}
</table>

<div style="margin-top:10px">
    <input type="button" value="&larr; {$lang.txt.prev_page}" {if $paginator->totalPages() == 1 || $paginator->getPage()==1}disabled class="btn-disabled"{else}onclick="location.href='{$paginator->prevpage()}';"{/if} />

    <input type="button" value="{$lang.txt.next_page} &rarr;" {if $paginator->totalPages() == 0 || $paginator->totalPages() == $paginator->getPage()}disabled class="btn-disabled"{else}onclick="location.href='{$paginator->nextpage()}';"{/if} />
    	{if $paginator->totalPages() > 1}
        <div style="float:right">
        {$lang.txt.jump_page}: 
        <select name="p" style="min-width:inherit;" id="pagid" onchange="gotopage(this.value)">
           {for $i=1 to $paginator->totalPages()}
				{if $i == $paginator->getPage()}
                	<option selected value="{$paginator->gotopage($i)}">{$i}</option>
				{else}
					<option value="{$paginator->gotopage($i)}">{$i}</option>
				{/if}
            {/for}
        </select> 
        <script type="text/javascript">
			function gotopage(pageid){
				location.href=pageid;
			}
		</script>
        </div> 
        <div class="clear"></div>
        {/if}
    </div>
    <div id="rentedbar" style="display:none" align="center">
    
      {$lang.txt.refquestaction}
      <div style="padding-top:5px">
      <select name="action" id="descr" onchange="showextensionbar()">
        <option value="">--- {$lang.txt.selectone} ---</option>
        <option value="recycle">{$lang.txt.irecycle}</option>
        {section name=d loop=$ref_discount}
        <option value="{$ref_discount[d].id}">{$lang.txt.iwantextendmoredays|replace:"%days%":$ref_discount[d].days}{if $ref_discount[d].discount != 0} - {$lang.txt.percentageoff|replace:"%number%":$ref_discount[d].discount}{/if}</option>
        {/section}
      </select>
      </div>
      <div id="priceref" style="padding:10px; color:#008000; font-weight:bold; display:none">
      </div>
      <div id="priceref2" style="padding:10px; color:#ff0000; font-weight:bold; display:none">
      </div>
      <input type="submit" id="paybutton" name="btn" value="{$lang.txt.doit}" class="orange" />
      <input type="button" name="btn" value="{$lang.txt.addfunds}" class="buttonblue" onclick="location.href='./?view=account&page=addfunds';" />
      
    
    </div>
</form>
</div>




<!-- End Content -->