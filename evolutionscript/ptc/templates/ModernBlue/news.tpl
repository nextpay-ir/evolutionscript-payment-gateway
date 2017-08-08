{include file="header.tpl"}
<!-- Content -->
<div class="site_title">{$lang.txt.lastestnews}</div>

<div class="site_content">
        	{section name=n loop=$news}
        	<div class="widget-news-title">{$news[n].title}</div>
            <div class="widget-news-date">{$lang.txt.published|replace:"%date":$news[n].date}</div>
            <div class="widget-news-content">{$news[n].message}</div>
            {/section}
</div>

<!-- End Content -->
{include file="footer.tpl"}