<!-- Content -->
<script type="text/javascript">
$(document).on('click','input[type=checkbox]', function(){
	sowdeletionbar();
});
var deletion_price = {$ref_deletion};
var rental_balance = {$user_info.purchase_balance};
var noenoughfunds = '{$lang.txt.nofoundspb}';
var totaltopay = '{$lang.txt.totaltopay}';
</script>

<div class="widget-main-title">{$lang.txt.directrefs}</div>
<div class="widget-content">
<form method="post" onsubmit="return submitform(this.id);" id="reflist">
<table width="100%" class="widget-tbl">
<tr class="titles">
        <td align="center">{$paginator->linkorder('username', $lang.txt.ref)}</td>
        <td align="center">{$paginator->linkorder('country', $lang.txt.country)}</td>
        <td align="center">{$paginator->linkorder('signup', $lang.txt.refsince)}</td>
        <td align="center">{$paginator->linkorder('for_reflastclick', $lang.txt.lastclick)}</td>
        <td align="center">{$paginator->linkorder('for_refclicks', $lang.txt.clicks)}</td>
        <td align="center">{$paginator->linkorder('for_refearned', $lang.txt.earned)}</td>
        <td align="center">{$paginator->linkorder('comes_from', $lang.txt.comes_from)}</td>
        {if $settings.ref_deletion == 'yes'}
        <td align="center"><input type="checkbox" id="checkall" /></td>
        {/if}
    </tr>

        {foreach item=item from=$thelist}
            <tr>
                <td align="center">{$item.username}</td>
                <td align="center">{$item.country}</td>
                <td align="center">{$item.signup|date_format:"%e %B %Y %r"}</td>
                <td align="center">{if $item.for_reflastclick == 0}{$lang.txt.never}{else}{$item.for_reflastclick|date_format:"%e %B %Y %r"}{/if}</td>
                <td align="center">{$item.for_refclicks}</td>
                <td align="center">{$item.for_refearned}</td>
                <td align="center">{$item.comes_from}</td>
                {if $settings.ref_deletion == 'yes'}
                <td align="center"><input type="checkbox" name="ref[]" value="{$item.id}" class="checkall" /></td>
                {/if}
            </tr>
        {/foreach}    

        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="10" align="center">{$lang.txt.no_directrefs}</td>
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
    

{if $paginator->totalResults() != 0}
        <div class="widget-ref-option">
        
          {$lang.txt.refquestaction}
          <div style="padding-top:5px">
          <select name="action" id="descr" onchange="sowdeletionbar()" class="primary textbox">
          <option value="">--- {$lang.txt.selectone} ---</option>
          <option value="delete">{$lang.txt.ideletethem}</option>
          </select>
          </div>
          <div id="priceref" style="padding:10px; color:#008000; font-weight:bold; display:none">
          </div>
          <div id="priceref2" style="padding:10px; color:#ff0000; font-weight:bold; display:none">
          </div>
          <input type="submit" name="btn" value="{$lang.txt.doit}" class="orange" id="paybutton" style="display:none" />
        
        </div>
{/if}
</form>


</div>