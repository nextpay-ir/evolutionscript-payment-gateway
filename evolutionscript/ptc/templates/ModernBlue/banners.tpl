<div class="widget-main-title">{$settings.site_name} {$lang.txt.banners}</div>
<div class="info_box">{$lang.txt.yourreflink}: {$settings.site_url}?ref={$user_info.username}</div>
{if $total_banners >= 1}
<div id="tabs">
	<ul>
    {for $foo=1 to $total_banners}
    <li><a href="#tab-{$foo}">Banner {$foo}</a></li>
    {/for}
	</ul>
    {section name=n loop=$banner} 
    <div id="tab-{$smarty.section.n.index+1}">
    	<div class="widget-content" align="center"><img src="{$banner[n].url}" /></div>
        <div><strong>{$lang.txt.bannerurl}</strong></div>
        <div class="widget-content">
        {$banner[n].url}
        </div>
        
        <div><strong>HTML</strong></div>
        <div class="widget-content">&lt;a target=&quot;_blank&quot;   href=&quot;{$settings.site_url}?ref={$user_info.username}&quot;&gt;&lt;img   src=&quot;{$banner[n].url}&quot;   border=&quot;0&quot; width=&quot;{$banner[n].width}&quot; height=&quot;{$banner[n].height}&quot; /&gt;&lt;/a&gt;</div>
        
        <div><strong>BBCode</strong></div>
        <div class="widget-content">[url={$settings.site_url}?ref={$user_info.username}][img]{$banner[n].url}[/img][/url]</div>
    </div>
    {/section}
</div>
{/if}

<!-- End Content -->