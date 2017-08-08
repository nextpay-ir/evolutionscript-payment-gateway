<?php
    if (!defined("EvolutionScript")) {
        exit("Hacking attempt...");
    }
    $adminnavmenu = array(
        "صفحه اصلی" => array(
            "link" => "./",
            "class" => (((($input->g['view'] == "" || $input->g['view'] == "loginlog") || $input->g['view'] == "account") || $input->g['view'] == "administrators") ? "current" : ""),
            "menu" => array(
                "داشبرد" => "./",
                "تاریخچه ورود" => "?view=loginlog",
                "اکانت من" => "?view=account",
                "مدیریت مدیران" => "?view=administrators",
                "خروج از مدیریت" => "?view=logout"
            )
        ),
        "کاربران" => array(
            "link" => "?view=members",
            "class" => (((($input->g['view'] == "members" || $input->g['view'] == "addmember") || $input->g['view'] == "massmail") || $input->g['view'] == "massmessage") ? "current" : ""),
            "menu" => array(
                "مدیریت کاربران" => "?view=members",
                "افزودن کاربر جدید" => "?view=addmember",
                "ارسال ایمیل" => "?view=massmail",
                "ارسال پیام برای کاربران" => "?view=massmessage"
            )
        ),
        "تبلیغات" => array(
            "link" => "?view=manageptc",
            "class" => ((((((((((((($input->g['view'] == "manageptc" || $input->g['view'] == "ptcads_settings") || $input->g['view'] == "fads_settings") || $input->g['view'] == "managefad") || $input->g['view'] == "flinks_settings") || $input->g['view'] == "manageflink") || $input->g['view'] == "bannerads_settings") || $input->g['view'] == "ptsu_settings") || $input->g['view'] == "manageptsu") || $input->g['view'] == "ptsu_pending") || $input->g['view'] == "specialpacks_settings") || $input->g['view'] == "loginads_settings") || $input->g['view'] == "manageloginad") ? "current" : ""),
            "menu" => array(
                "پرداخت به ازای کلیک" => array(
                    "تنظیمات" => "?view=ptcads_settings",
                    "مدیریت" => "./?view=manageptc",
                    "ساخت جدید" => "./?view=manageptc&new_ptc=addnew"
                ),
                "آگهی های ویژه" => array(
                    "تنظیمات" => "./?view=fads_settings",
                    "مدیریت" => "./?view=managefad",
                    "ساخت جدید" => "./?view=managefad&new_fad=addnew"
                ),
                "آگهی های لینکی ویژه" => array(
                    "تنظیمات" => "./?view=flinks_settings",
                    "مدیریت" => "./?view=manageflink",
                    "ساخت جدید" => "./?view=manageflink&new_flink=addnew"
                ),
                "تبلیغات بنری" => array(
                    "تنظیمات" => "?view=bannerads_settings",
                    "مدیریت" => "?view=managebannerad",
                    "ساخت جدید" => "./?view=managebannerad&new_bannerad=addnew"
                ),
                "تبلیغات ورود" => array(
                    "تنظیمات" => "?view=loginads_settings",
                    "مدیریت" => "?view=manageloginad",
                    "ساخت جدید" => "?view=manageloginad&new_loginads=addnew"
                ),
                "پرداخت به ازای ثبت نام" => array(
                    "تنظیمات" => "./?view=ptsu_settings",
                    "مدیریت" => "./?view=manageptsu",
                    "منتظر بررسی" => "./?view=ptsu_pending",
                    "ساخت جدید" => "./?view=manageptsu&new_ptsu=addnew"
                ),
                "پکیج ویژه" => "?view=specialpacks_settings"
            )
        ),
        "سفارشات" => array(
            "link" => "?view=orders",
            "class" => ($input->g['view'] == "orders" ? "current" : ""),
            "menu" => array(
                "همه" => "?view=orders",
                "منتظر" => "?view=orders&do=search&status=Pending",
                "تکمیل" => "?view=orders&do=search&status=Completed"
            )
        ),
        "واریز ( شارژ ) حساب" => array(
            "link" => "?view=deposits",
            "class" => (($input->g['view'] == "deposits" || $input->g['view'] == "add_deposit") ? "current" : ""),
            "menu" => array(
                "مشاهده همه" => "?view=deposits",
                "افزودن" => "?view=add_deposit"
            )
        ),
        "تسویه حساب" => array(
            "link" => "?view=withdrawals",
            "class" => ($input->g['view'] == "withdrawals" ? "current" : ""),
            "menu" => array(
                "همه درخواست ها" => "./?view=withdrawals",
                "درخواست های منتظر" => "./?view=withdrawals&do=search&status=Pending",
                "درخواست های پرداخت شده" => "./?view=withdrawals&do=search&status=Completed",
                "درخواست های کنسل شده" => "./?view=withdrawals&do=search&status=Cancelled"
            )
        ),
        "پشتیبانی" => array(
            "link" => "?view=support",
            "class" => (($input->g['view'] == "support_settings" || $input->g['view'] == "support") ? "current" : ""),
            "menu" => array(
                "تنظیمات" => "./?view=support_settings",
                "تیکت های باز" => "./?view=support&do=search&status=1",
                "پاسخ داده شده" => "./?view=support&do=search&status=2",
                "منتظر پاسخ" => "./?view=support&do=search&status=3",
                "بسته" => "./?view=support&do=search&status=4"
            )
        ),
        "مدیریت محتوا" => array(
            "link" => "?view=news",
            "class" => (((($input->g['view'] == "news" || $input->g['view'] == "faq") || $input->g['view'] == "tos") || $input->g['view'] == "sitebanners") ? "current" : ""),
            "menu" => array(
                "اخبار" => "./?view=news",
                "پرسش های متداول" => "./?view=faq",
                "مدیریت بنرها" => "./?view=sitebanners",
                "مدیریت قوانین" => "?view=tos",
                "قالبهای ایمیل" => "?view=email_template"
            )
        ),
        "ابزارها" => array(
            "link" => "?view=blacklist",
            "class" => (((((((((((($input->g['view'] == "addon_modules" || $input->g['view'] == "admin_advertisement") || $input->g['view'] == "blacklist") || $input->g['view'] == "assignreferral") || $input->g['view'] == "linktracker") || $input->g['view'] == "repair_statistics") || $input->g['view'] == "googleanalytics") || $input->g['view'] == "multipleips") || $input->g['view'] == "install_language") || $input->g['view'] == "install_template") || $input->g['view'] == "cheat_logs") || $input->g['view'] == "topdomains") ? "current" : ""),
            "menu" => array(
                "مدیریت افزودنی ها" => "./?view=addon_modules",
                "تبلیغات مدیریت" => "./?view=admin_advertisement",
                "لیست سیاه" => "./?view=blacklist",
                "زیر مجموعه ها" => "?view=assignreferral",
                "کمپین ها" => "?view=linktracker",
                "تعمیر آمار" => "./?view=repair_statistics",
                "پاک کردن کش" => "./?view=clean_cache",
                "آنالیزگر گوگل" => "?view=googleanalytics",
                "تنظیمات آی پی های چندگانه" => "?view=multipleips",
                "دامنه های برتر" => "?view=topdomains",
                "آمار تخلفات" => "?view=cheat_logs",
                "نصب زبان" => "./?view=install_language",
                "نصب قالب" => "./?view=install_template"
            )
        ),
        "تنظیمات سیستم" => array(
            "link" => "?view=general",
            "class" => (((((((((($input->g['view'] == "general" || $input->g['view'] == "captcha") || $input->g['view'] == "automation") || $input->g['view'] == "gateways") || $input->g['view'] == "membership") || $input->g['view'] == "buy_referrals") || $input->g['view'] == "rent_referrals") || $input->g['view'] == "forum_settings") || $input->g['view'] == "language_settings") || $input->g['view'] == "template_settings") ? "current" : ""),
            "menu" => array(
                "تنظیمات عمومی" => "?view=general",
                "تنظیمات امنیتی" => "?view=captcha",
                "تنظیمات اتوماسیون" => "?view=automation",
                "دروازه پرداخت" => "?view=gateways",
                "تنظیمات پلن حساب کاربری" => "?view=membership",
                "تنظیمات خرید زیر مجموعه" => "?view=buy_referrals",
                "تنظیمات اجاره زیر مجموعه" => "?view=rent_referrals",
                "تنظیمات انجمن (فروم)" => "?view=forum_settings",
                "تنظیمات زبان" => "?view=language_settings",
                "تنظیمات قالب" => "?view=template_settings"
            )
        )
    );
    if (!$admin->permissions['manage_members']) {
        unset($adminnavmenu['کاربران']['menu']['مدیریت کاربران']);
    }
    if (!$admin->permissions['add_new_member']) {
        unset($adminnavmenu['کاربران']['menu']['افزودن کاربر جدید']);
    }
    if (!$admin->permissions['send_mail']) {
        unset($adminnavmenu['کاربران']['menu']['ارسال ایمیل']);
    }
    if (!$admin->permissions['send_messages']) {
        unset($adminnavmenu['کاربران']['menu']['ارسال پیام برای کاربران']);
    }
    if (empty($adminnavmenu['کاربران']['menu'])) {
        unset($adminnavmenu['کاربران']);
    }



    if (!$admin->permissions['ptcads']) {
        unset($adminnavmenu['تبلیغات']['menu']["پرداخت به ازای کلیک"]['ساخت جدید']);
    }
    if (!$admin->permissions['ptcads_manager']) {
        unset($adminnavmenu['تبلیغات']['menu']["پرداخت به ازای کلیک"]['تنظیمات']);
        unset($adminnavmenu['تبلیغات']['menu']["پرداخت به ازای کلیک"]['مدیریت']);
    }
    if (empty($adminnavmenu['تبلیغات']['menu']["پرداخت به ازای کلیک"])) {
        unset($adminnavmenu['تبلیغات']['menu']['پرداخت به ازای کلیک']);
    }
    if (!$admin->permissions['featuredads']) {
        unset($adminnavmenu['تبلیغات']['menu']["آگهی های ویژه"]['ساخت جدید']);
    }
    if (!$admin->permissions['featuredads_manager']) {
        unset($adminnavmenu['تبلیغات']['menu']["آگهی های ویژه"]['تنظیمات']);
        unset($adminnavmenu['تبلیغات']['menu']["آگهی های ویژه"]['مدیریت']);
    }
    if (empty($adminnavmenu['تبلیغات']['menu']["آگهی های ویژه"])) {
        unset($adminnavmenu['تبلیغات']['menu']['آگهی های ویژه']);
    }
    if (!$admin->permissions['featuredlinks']) {
        unset($adminnavmenu['تبلیغات']['menu']["آگهی های لینکی ویژه"]['ساخت جدید']);
    }
    if (!$admin->permissions['featuredlinks_manager']) {
        unset($adminnavmenu['تبلیغات']['menu']["آگهی های لینکی ویژه"]['تنظیمات']);
        unset($adminnavmenu['تبلیغات']['menu']["آگهی های لینکی ویژه"]['مدیریت']);
    }
    if (empty($adminnavmenu['تبلیغات']['menu']["آگهی های لینکی ویژه"])) {
        unset($adminnavmenu['تبلیغات']['menu']['آگهی های لینکی ویژه']);
    }
    if (!$admin->permissions['bannerads']) {
        unset($adminnavmenu['تبلیغات']['menu']["تبلیغات بنری"]['ساخت جدید']);
    }
    if (!$admin->permissions['bannerads_manager']) {
        unset($adminnavmenu['تبلیغات']['menu']["تبلیغات بنری"]['تنظیمات']);
        unset($adminnavmenu['تبلیغات']['menu']["تبلیغات بنری"]['مدیریت']);
    }
    if (empty($adminnavmenu['تبلیغات']['menu']["تبلیغات بنری"])) {
        unset($adminnavmenu['تبلیغات']['menu']['تبلیغات بنری']);
    }
    if (!$admin->permissions['loginads']) {
        unset($adminnavmenu['تبلیغات']['menu']["تبلیغات ورود"]['ساخت جدید']);
    }
    if (!$admin->permissions['loginads_manager']) {
        unset($adminnavmenu['تبلیغات']['menu']["تبلیغات ورود"]['تنظیمات']);
        unset($adminnavmenu['تبلیغات']['menu']["تبلیغات ورود"]['مدیریت']);
    }
    if (empty($adminnavmenu['تبلیغات']['menu']["تبلیغات ورود"])) {
        unset($adminnavmenu['تبلیغات']['menu']['تبلیغات ورود']);
    }
    if (!$admin->permissions['ptsuoffers']) {
        unset($adminnavmenu['تبلیغات']['menu']["پرداخت به ازای ثبت نام"]['ساخت جدید']);
    }
    if (!$admin->permissions['ptsuoffers_manager']) {
        unset($adminnavmenu['تبلیغات']['menu']["پرداخت به ازای ثبت نام"]['تنظیمات']);
        unset($adminnavmenu['تبلیغات']['menu']["پرداخت به ازای ثبت نام"]['مدیریت']);
        unset($adminnavmenu['تبلیغات']['menu']["پرداخت به ازای ثبت نام"]['منتظر بررسی']);
    }
    if (empty($adminnavmenu['تبلیغات']['menu']["پرداخت به ازای ثبت نام"])) {
        unset($adminnavmenu['تبلیغات']['menu']['پرداخت به ازای ثبت نام']);
    }
    if (!$admin->permissions['specialpacks']) {
        unset($adminnavmenu['تبلیغات']['menu']['پکیج ویژه']);
    }
    if (empty($adminnavmenu['تبلیغات']['menu'])) {
        unset($adminnavmenu['تبلیغات']);
    }





    if (!$admin->permissions['orders']) {
        unset($adminnavmenu['سفارشات']);
    }
    if (!$admin->permissions['deposits']) {
        unset($adminnavmenu['واریز ( شارژ ) حساب']);
    }
    if (!$admin->permissions['withdrawals']) {
        unset($adminnavmenu['تسویه حساب']);
    }
    if (!$admin->permissions['support']) {
        unset($adminnavmenu['پشتیبانی']['menu']['تنظیمات']);
    }
    if (!$admin->permissions['support_manager']) {
        unset($adminnavmenu['پشتیبانی']['menu']['تیکت های باز']);
        unset($adminnavmenu['پشتیبانی']['menu']['پاسخ داده شده']);
        unset($adminnavmenu['پشتیبانی']['menu']['منتظر پاسخ']);
        unset($adminnavmenu['پشتیبانی']['menu']['بسته']);
    }
    if (empty($adminnavmenu['پشتیبانی']['menu'])) {
        unset($adminnavmenu['پشتیبانی']);
    }
    if (!$admin->permissions['site_content']) {
        unset($adminnavmenu['مدیریت محتوا']);
    }
    if (!$admin->permissions['utilities']) {
        unset($adminnavmenu['ابزارها']);
    }
    if (!$admin->permissions['setup']) {
        unset($adminnavmenu['تنظیمات سیستم']);
    }
    if (!$admin->permissions['administrators']) {
        unset($adminnavmenu['صفحه اصلی']['menu']['مدیریت مدیران']);
    }
    $test = array(
        "setup" => "Able to manage Setup tab content",
        "administrators" => "Able to manage administrators"
    );
?>