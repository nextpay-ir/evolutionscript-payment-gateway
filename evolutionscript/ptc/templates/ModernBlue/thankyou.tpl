<!-- Content -->
<div class="widget-main-title">{$lang.txt.thankyoutitle}</div>

<div class="widget-content">
{if $smarty.get.type == 'funds'}
	{$lang.txt.fundsreceived}
{elseif $smarty.get.type == 'upgrade'}
	{$lang.txt.accountupgraded}
{else}
{$lang.txt.thankyoupurchase}
    <p>{$lang.txt.orderreceived|replace:"%ordern":$order.id|replace:"%product":$order.name}</p>
    {if $order.status=='Pending'}
        <p>{$lang.txt.orderpending}</p>
    {elseif $order.status=='Completed'}
        <p style="color:green"><strong>{$lang.txt.orderdone}</strong></p>
    {/if}
{/if}
</div>
          
<!-- End Content -->
