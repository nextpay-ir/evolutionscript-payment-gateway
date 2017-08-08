{include file="header.tpl"}
    <link rel="stylesheet" href="./js/nivoslider/themes/default/default.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./js/nivoslider/themes/light/light.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./js/nivoslider/themes/bar/bar.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="./js/nivoslider/nivo-slider.css" type="text/css" media="screen" />
    <script type="text/javascript" src="./js/nivoslider/jquery.nivo.slider.js"></script>
<div class="site_content" style="margin:0">
    <div class="slider-wrapper theme-light">
        <div id="slider" class="nivoSlider">
            <img src="./templates/ModernBlue/css/images/slider1.jpg" data-thumb="/templates/ModernBlue/css/images/slider1.jpg" alt="" />
            <img src="./templates/ModernBlue/css/images/slider2.jpg" data-thumb="/templates/ModernBlue/css/images/slider2.jpg" alt="" />
        </div>
        <div id="htmlcaption" class="nivo-html-caption">
            <strong>This</strong> is an example of a <em>HTML</em> caption with <a href="#">a link</a>. 
        </div>
    </div>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider();
    });
    </script>
    
{if $settings.site_stats == 'yes'}
<div class="statistics">
	{$lang.txt.stats3}: <strong>{$statistics.cashout} تومان</strong> &nbsp;&nbsp;&nbsp;
    {$lang.txt.stats1}: <strong>{$statistics.members}</strong> &nbsp;&nbsp;&nbsp;
    {$lang.txt.stats2}: <strong>{$statistics.members_today}</strong>
	{if $settings.usersonline}&nbsp;&nbsp;&nbsp; {$lang.txt.stats4}: <strong>{$statistics.usersonline}</strong>{/if}
</div>
{/if}
    
<div class="boxhome-box">    
    
<div class="home-box" style="float:left;">
    <div class="title">کاربران</div>
        <img src="./templates/ModernBlue/css/images/members.png" align="right" />
        <div style="text-align: justify;padding: 10px;">
        اگه شما کاربر اینترنت هستید و دنبال راحت ترین روش کسب درآمد می گردید من به شما سامانه کسب درآمد هیت باکس را معرفی می کنم با کمترین زمان ممکن حداکثر درآمد ممکن را کسب کنید فقط کافی در سایت عضو شوید و بخش راهنما را مطالعه نمایید . همین الان آغاز کنید !</div>
</div>


<div class="home-box" style="float:right;">
    <div class="title">تبلیغ کنندگان</div>
    <img src="./templates/ModernBlue/css/images/advertisers.png" align="right" />
    <div style="text-align: justify;padding: 10px;margin-top: -15px;">
    اگه به دنبال معرفی سایت و یا محصول خود به میلیون ها کاربر فارسی زبان اینترنت هستید بهترین گزینه هیت باکس هست شما می توانید در چندین مرحله و با امکانات متنوع از کاربر بخواهید تبلیغات شما را نشان دهند و دوستان خود را دعوت به نمایش کنند تا درآمد کسب کنند شک نکنید که بهترین جا برای یک سرمایه گذاری درست همین جا می باشد ما نمی گوییم , کاربران چنین می گویند .
    </div>
</div>

</div>

<div class="clear"></div>

<div style="background:url(./templates/ModernBlue/css/images/briefcase.png) no-repeat scroll 50% 0px;min-height: 130px;">
{if $settings.fads_available == 'yes'}
	<div class="featured_ads" style="float:left;">
        <div class="title">{$lang.txt.featuredad}</div>
        <div class="fcontent">
            <ul class="featured-ads">
                {if getfeaturedad()}{/if}
            </ul>               
        </div>
	</div>
{/if}
{if $settings.flinks_available == 'yes'}
	<div class="featured_ads">
        <div class="title2">{$lang.txt.featuredlink}</div>
        <div class="fcontent">
            <ul class="featured-ads">
                {if getfeaturedlink()}{/if}
            </ul>               
        </div>
	</div>
{/if}
<div class="clear"></div>
</div>




<div class="clear"></div>

</div>
<!-- End Content -->
{include file="footer.tpl"}