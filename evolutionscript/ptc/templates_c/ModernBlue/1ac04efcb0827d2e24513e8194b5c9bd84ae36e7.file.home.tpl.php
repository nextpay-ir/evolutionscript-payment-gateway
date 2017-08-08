<?php /* Smarty version Smarty-3.1.13, created on 2017-08-08 05:56:17
         compiled from "/var/www/html/evolutionscript/ptc/templates/ModernBlue/home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3029770705989528182a141-63244178%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1ac04efcb0827d2e24513e8194b5c9bd84ae36e7' => 
    array (
      0 => '/var/www/html/evolutionscript/ptc/templates/ModernBlue/home.tpl',
      1 => 1493457901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3029770705989528182a141-63244178',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'settings' => 0,
    'lang' => 0,
    'statistics' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_59895281956e38_03968105',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59895281956e38_03968105')) {function content_59895281956e38_03968105($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

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
    
<?php if ($_smarty_tpl->tpl_vars['settings']->value['site_stats']=='yes'){?>
<div class="statistics">
	<?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['stats3'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['statistics']->value['cashout'];?>
 تومان</strong> &nbsp;&nbsp;&nbsp;
    <?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['stats1'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['statistics']->value['members'];?>
</strong> &nbsp;&nbsp;&nbsp;
    <?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['stats2'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['statistics']->value['members_today'];?>
</strong>
	<?php if ($_smarty_tpl->tpl_vars['settings']->value['usersonline']){?>&nbsp;&nbsp;&nbsp; <?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['stats4'];?>
: <strong><?php echo $_smarty_tpl->tpl_vars['statistics']->value['usersonline'];?>
</strong><?php }?>
</div>
<?php }?>
    
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
<?php if ($_smarty_tpl->tpl_vars['settings']->value['fads_available']=='yes'){?>
	<div class="featured_ads" style="float:left;">
        <div class="title"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['featuredad'];?>
</div>
        <div class="fcontent">
            <ul class="featured-ads">
                <?php if (getfeaturedad()){?><?php }?>
            </ul>               
        </div>
	</div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['settings']->value['flinks_available']=='yes'){?>
	<div class="featured_ads">
        <div class="title2"><?php echo $_smarty_tpl->tpl_vars['lang']->value['txt']['featuredlink'];?>
</div>
        <div class="fcontent">
            <ul class="featured-ads">
                <?php if (getfeaturedlink()){?><?php }?>
            </ul>               
        </div>
	</div>
<?php }?>
<div class="clear"></div>
</div>




<div class="clear"></div>

</div>
<!-- End Content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>