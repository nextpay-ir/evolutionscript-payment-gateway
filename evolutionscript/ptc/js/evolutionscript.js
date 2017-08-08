$(function(){
	$("#tabs").tabs();
	$( document ).tooltip();
	checkall(); 
});
/* Virtual Keyboard */
function loginkeyboard(){
	$("input").focus(function(){
		$('input').removeClass('input-focus');
		$(this).addClass('input-focus');
	});
	$('#keyboard li').each(function(){
		$(this).addClass('keyboard-buton');
	}).hover(function(){
		$(this).addClass('keyboard-buton-hover');
	}, function(){
		$(this).removeClass('keyboard-buton-hover')}).click(function(){
			kb( $('input.input-focus'), $(this) );
		});;function kb(ok,ob){var $write = ok,shift = false,capslock = false;var $this = ob,character = $this.html();if ($this.hasClass('left-shift') || $this.hasClass('right-shift')){$('.letter').toggleClass('uppercase');$('.symbol span').toggle();shift = (shift === true) ? false : true;capslock = false;return false;}if ($this.hasClass('capslock')){$('.letter').toggleClass('uppercase');capslock = true;return false;}if ($this.hasClass('delete')){var html = $write.val();$write.val(html.substr(0, html.length - 1));return false;}if ($this.hasClass('symbol')) character = $('span:visible', $this).html();if ($this.hasClass('space')) character = ' ';if ($this.hasClass('tab')) character = "\t";if ($this.hasClass('return')) character = "\n";if ($this.hasClass('uppercase')) character = character.toUpperCase();if (shift === true){$('.symbol span').toggle();if (capslock === false) $('.letter').toggleClass('uppercase');shift = false;}$write.val($write.val() + character);}
}

/* Personal Settings */
function updateemail(act){
	$('#settingsform').l2block();
	var jqxhr = $.post("index.php?view=account&page=settings&a="+act,$("#settingsform").serialize(), function(data) {
	if(data.status == 0){
		$("#settingsform").l2error(data.msg);
		$('html, body').animate({scrollTop:0}, 'slow');
		$("#settingsform").l2unblock();
	}else{
		$("#settingsform").remove();
		if(act == 'restore'){
			$("#message_sent2").show();
		}else{
			$("#message_sent").show();
		}
	}
   }, "json")
	return false;
}
function submitform(id){
	$('#'+id).l2block();
	httplocal = location.href;
	var jqxhr = $.post(httplocal,$("#"+id).serialize(), function(data) {
		if(data.status == 0){
			$("#"+id).l2error(data.msg);
			$("#"+id).l2unblock();
			captchareload();
		}else if(data.status == 1){
			eval(data.msg);
		}else if(data.status == 2){
			eval(data.msg);
			$("#"+id).l2unblock();
			$("#"+id).remove();		
		}else if(data.status == 3){
			$("#"+id).l2success(data.msg);
			$("#"+id).l2unblock();
			document.getElementById(id).reset();	
		}else if(data.status == 4){
			$("#"+id).l2success(data.msg);
			$("#"+id).l2unblock();
		}else if(data.status == 5){
			$("#"+id).l2success(data.msg);
			$("#"+id).l2unblock();
			$("#"+id).remove();			
		}else if(data.status == 6){
			eval(data.msg);
			$("#"+id).l2unblock();	
		}
   }, "json")
	return false;
}

function checkall(){
	$("#checkall").click(function () {
        $(".checkall").attr("checked",this.checked);
    });	
	$("#checkall2").click(function () {
        $(".checkall2").attr("checked",this.checked);
    });	
}

function sowdeletionbar(){
	var referrals=$("input:checked[name^='ref']");
	var refslist=referrals.length;
	var raction = $("#descr").val();
	$("#priceref").hide();
	$("#priceref2").hide();
	$("#paybutton").hide();
	$("#addfunds").hide();
	if(refslist>0){
		$("#rentedbar").show();	
		if(raction == "delete"){
			var price = deletion_price*refslist;
			price = price.toFixed(3);
			var step1 = 'done';
		}
		if(step1 == 'done'){
			if(rental_balance >= price){
				$("#priceref").html(totaltopay+": $"+price);
				$("#priceref").show();
				$("#paybutton").show();
			}else{
				$("#priceref2").html(noenoughfunds);
				$("#priceref2").show();
			}
		}
	}else{
		$("#rentedbar").hide();	
	}
}


function showWindowsModal(){
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#dialog-message" ).dialog({
		modal: true,
		autoOpen: false,
		resizable: false,
		width:600,
	});	
}
function openWindows(vtitle, divid){
	$("#dialog-message").remove();
	var dcontent = $("#"+divid).html();
	$('body').append('<div id="dialog-message" title=""></div>');
	$("#dialog-message").attr('title',vtitle);
	$("#dialog-message").html(dcontent);
	showWindowsModal();
	$("#dialog-message").dialog( "open" );
}

function forum_preview(){
	hideerror('errorbox');
	$('#preview').hide();
	$("#massage_form").l2block("#ffffff", 35);	
	datatosend = $("#massage_form").serialize();
	datatosend += "&preview=1";
	var jqxhr = $.post("includes/bbcode.php",datatosend, function(data) {
	if(data.status == 0){
		$("#massage_form").l2unblock();	
		showerror('errorbox', data.msg);
	}else{
		$("#massage_form").l2unblock();	
		$("#dialog-message").remove();
		$('body').append('<div id="dialog-message" title="Preview"></div>');
		$("#dialog-message").html(data.msg);
        showWindowsModal();
		$("#dialog-message").dialog( "open" );
	}
   }, "json")
	return false;
}

function submitpayment(){
	$('#checkoutform').l2block();
	var jqxhr = $.post("index.php?view=account&page=buy&",$("#checkoutform").serialize(), function(data) {
	if(data.status == 0){
		$("#checkoutform").l2error(data.msg);
		$("#checkoutform").l2unblock();
	}else{
		location.href='index.php?view=account&page=thankyou&order='+data.msg+'';
	}

   }, "json")
	return false;
}

/* LoginOut Process */
function loginoutprocess(actionloginout){
	$("#progressbar").link2progress(10, function(){
	 	if(actionloginout == 'login'){
			location.href = 'index.php?view=account';
		}else{
			location.href = 'index.php';
		}
	});
}
function forum_openclosetopic(topicid){
	$("#error_box").hide();
	httplocal = location.href;
	var jqxhr = $.post(httplocal+"&openclosetopic=do", function(data) {
	if(data.status == 0){
		$("#error_box").html(data.msg);
		$("#error_box").fadeIn();
		$('html, body').animate({scrollTop:0}, 'slow');
	}else{
		location.reload();
	}
   }, "json")
	return false;
}
function adcontrol(ad, action, classe){
	$('#admanagefrm').l2block();
	var dataString = 'id='+ ad +'&action='+ action +'&class='+ classe;
	var jqxhr = $.post("./?view=account&page=adcontrol&a=submit&request=control&",dataString, function(data) {
		if(data.status == 0){
			$("#admanagefrm").l2error(data.msg);
			$("#admanagefrm").l2unblock();
			$('html, body').animate({scrollTop:0}, 'slow');
		}else{
			if(action == 'start'){
				$("#control"+ad).html("<a href=\"javascript:void(0);\" onclick=\"adcontrol('"+ad+"', 'pause', '"+classe+"');\">Pause</a>");
			}else if(action == 'pause'){
				$("#control"+ad).html("<a href=\"javascript:void(0);\" onclick=\"adcontrol('"+ad+"', 'start', '"+classe+"');\">Start</a>");
			}else if(action == 'delete'){
				$("#tbody"+ad).remove();
			}
			$("#admanagefrm").l2unblock();
		}
	   }, "json")
		return false;
}

function createad(classe){
	$('#create_ad').l2block();
	var jqxhr = $.post("./?view=account&page=createad&class="+classe+"&a=submit&",$("#create_ad").serialize(), function(data) {
	if(data.status == 0){
		$("#create_ad").l2error(data.msg);
		$("#create_ad").l2unblock();
		$('html, body').animate({scrollTop:0}, 'slow');
	}else{
		$("#create_ad").remove();
		$("#message_sent").show();
	}
   }, "json")
	return false;
}

function allocatead(classe){
	$('#allocateform').l2block();
	var jqxhr = $.post("index.php?view=account&page=allocate_credits&",$("#allocateform").serialize(), function(data) {
		if(data.status == 0){
			$("#allocateform").l2error(data.msg);
			$("#allocateform").l2unblock();
			$('html, body').animate({scrollTop:0}, 'slow');
		}else{
			$("#allocateform").remove();
			$("#message_sent").show();
		}
	   }, "json")
		return false;
}

/* PTSU Activation */
function ptsuadvaction(rid,adid,myaction){
	httplocal = location.href;
	$('.submitptsu'+rid).l2block();
	$("#details"+rid).toggle();
	$(".message"+rid).val();
		var jqxhr = $.post(httplocal+"&a="+myaction,$("#submitptsu"+rid).serialize(), function(data) {
		if(data.status == 0){
			$(".submitptsu"+rid).l2error(data.msg);
			$('.submitptsu'+rid).l2unblock();
		}else{
			location.reload();
		}
	   }, "json")
		return false;
}
function forum_postdelete(postid){
	var jqxhr = $.post("forum.php?dopost=delete","post="+ postid, function(data) {
	if(data.status == 0){
		showerror('errorbox', data.msg);
	}else{
		$("#postid"+postid).fadeOut(function(){
			$(this).remove();								
		});
	}
   }, "json")
	return false;
}
/* Allocate */
function calculatecredits(value){
	var newvalue = $("#allocate").val()*value;
	$("#creditcost").val(newvalue);	
}
function recalculatecredits(value){
	var newvalue = $("#creditcost").val()/value;
	$("#allocate").val(newvalue);		
}

/* Advertise */
function updatepack(){
	specialid = $("#spackformlist").val();
	specialtext = $("#spackformlist option[value="+specialid+"]").text();
	var packlist = "<strong>"+specialtext+"</strong>";
	packlist += '<ul>';
	for (var k in specialitemsList.specialitems){
		if(specialitemsList.specialitems[k].specialpack == specialid){
			packlist += "<li>"+specialitemsList.specialitems[k].amount+" "+specialitemsList.specialitems[k].title+"</li>";
		}
	}
	packlist += '<ul>';
	$("#specialpackdescr").html(packlist);
}
function prepare_payment(formid){
	selectorid = $("#"+formid+"list").val();
	itemdetails = $("#"+formid+"list").children("[value='"+selectorid+"']").text();
	var productname = $("#"+formid+"product").val();
	//return false;
	$("#itemname").html(itemdetails);
	$("#productname").html(productname);
	$("#ads_list").hide();
	var payment_details = '';
	$.each($('#'+formid).serializeArray(), function(i, field) {
   		payment_details += '<input type="hidden" name="'+field.name+'" id="'+field.name+'" value="'+field.value+'" />';
	});
	$("#payment_details").html(payment_details);
	$("#payment_form").show();
	return false;
}
function cancel_payad(){
	$("#payment_form").hide();
	$("#ads_list").show();
}

/* End Advertise */

function showerror(id, msg){
	$("#"+id).html(msg);
	$("#"+id).fadeIn('slow');
}
function hideerror(id){
	$("#"+id).fadeOut('slow');
}




/* Timer */
function dateTimer(){
	var hours=mydate.getHours();
	var minutes=mydate.getMinutes();
	var seconds=mydate.getSeconds();
	if(hours<10){ hours='0'+hours;}
	if(minutes<10){minutes='0'+minutes; }
	if(seconds<10){ seconds='0'+seconds; }
	fech=hours+":"+minutes+":"+seconds;
	$("#timenow").html(fech);
	mydate.setSeconds(mydate.getSeconds()+1);
	setTimeout("dateTimer()",1000);
}

/* Request Payment */
function requestpayment(id){
	hideerror('errorbox');
	$("#withdrawform-"+id).l2block("#ffffff", 35);	
	var jqxhr = $.post("index.php?view=account&page=withdraw&",$("#withdrawform-"+id).serialize(), function(data) {
	if(data.status == 0){
		$("#withdrawform-"+id).l2unblock();	
		showerror('errorbox', data.msg);
	}else{
		$("#withdrawform-"+id).remove();
		$("#message_sent").show();
	}
   }, "json")
	return false;

}


/* Rent Referrals Options */
function showextensionbar(){
	var rented=$("input:checked[name^='ref']");
	var rentedlist=rented.length;
	var raction = $("#descr").val();
	$("#priceref").hide();
	$("#priceref2").hide();
	$("#paybutton").hide();
	$("#addfunds").hide();
	
	if(rentedlist>0){
		$("#rentedbar").show();	

		if(raction == ''){	
		}else
		if(raction == 'recycle'){
			var price = recycle_price*rentedlist;
			price = price.toFixed(3)							
		}else{
			var days = ref_extension[raction]/30;
			days = days.toFixed(2);
			var price = (renew_price*rentedlist)*days;
			var price = price - ((price*ref_discount[raction])/100);
			price = price.toFixed(3);
		}
		if(rental_balance >= price){
			$("#priceref").html(totaltopay+": $"+price);
			$("#priceref").show();
			$("#paybutton").show();
		}else{
			if(price != null){
			$("#priceref2").html(noenoughfunds);
			}
			$("#priceref2").show();
			$("#addfunds").show();
		}
	}else{
		$("#rentedbar").hide();	
	}
}

/* Message Center Action */
function message_action(){
	hideerror('errorbox');
	$("#msglist").l2block("#ffffff", 35);	
	var jqxhr = $.post("index.php?view=account&page=messages",$("#msglist").serialize(), function(data) {
	if(data.status == 0){
		$("#msglist").l2unblock();	
		showerror('errorbox', data.msg);
	}else{
		location.href='index.php?view=account&page=messages';
	}
   }, "json")
	return false;
}




/* Ads Manager */


/* Ad Validation */
	function ptcevolution_surfer(){
		if (top != self) {
			try { top.location = self.location; }
			catch (err) { self.location = '/FrameDenied.aspx'; }
		}
		$("#surfbar").html("<div class='adwait'>"+adwait+"</div>");
	}
	function vshowadbar(error){
		if(error == ''){
			$(".adwait").fadeOut(1000, function(){
				$("#surfbar").html('<div class="progressbar" id="progress"><div id="progressbar"></div></div>');
				$("#progressbar").link2progress(secs, function(){
					vendprogress('');
				});
			});
		}else{
			$(".adwait").fadeOut(1000, function(){
				$("#surfbar").html("<div class='errorbox'>"+error+"</div>");
				$(".errorbox").fadeIn(1000);
			});
		}
	}
	function vendprogress(masterkey){
	 if(masterkey==''){
		 $("#surfbar").fadeOut('slow', function(){
			$("#vnumbers").fadeIn('slow');										
		});  
		 return false;
	 }else{
		$("#vnumbers").fadeOut('slow', function(){
			$(this).remove();
			$("#surfbar").fadeIn('slow'); 
		});
		
	 }
	 $("#surfbar").html("Please wait...");
	 var dataString = 'action=verify&id='+adid+'&masterkey='+masterkey; 
			$.ajax({
				type: "POST",
				url: "index.php?view=account&page=validate&",
				data: dataString,
				success: function(msg){
					if(msg=='ok'){
						$("#surfbar").html("<div class='successbox'>"+adcredited+"</div>");
						$(".successbox").fadeIn('slow');
						window.opener.location.reload();
						return false;
					}else{
						 $("#surfbar").html("<div class='errorbox'>"+msg+"</div>");
						 $(".errorbox").fadeIn('slow');
					}
				}
			});
	}

/* Ads Page */
function hideAdminAdvertisement(){
	$("#admin_advertisement").remove();
	$(".blockthis").l2unblock();
}
function hideAdvertisement(id){
	$("#"+id).addClass('disabled');
}




function showtemplatebar(){
	$("#current_tpl").hide();
	$("#tpl_selector").show();
}
function restored_template(){
	$("#current_tpl").show();
	$("#tpl_selector").hide();
}
function update_template(){
	var url = $("#tpllist").val();
	location.href = url;	
}

function showlangbar(){
	$("#current_lang").hide();
	$("#lang_selector").show();
}
function restored_language(){
	$("#current_lang").show();
	$("#lang_selector").hide();
}
function update_language(){
	var url = $("#langlist").val();
	location.href = url;
}


function captchareload(){}