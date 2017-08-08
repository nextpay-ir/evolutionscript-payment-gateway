var wf=0;
var adex = false;
$(document).ready(function() {	
	ptcevolution_surfer();
	var int=self.setInterval("upp()",100);
	setTimeout("veradinfo()",5000);
});
function upp(){
	var w=window;
	var d=document;

	if( $.browser.opera ){
		var opversion = window.opera.version ();
		if(opversion < 11){
			$(d).focus(function(){lwf=1}).blur(function(){lwf=0});
		}else{
			$(w).focus(function(){wo=1}).blur(function(){wo=0});
			lwf=((typeof w.hasFocus!='undefined'?w.hasFocus():wo)?1:0);
		}
		
	}else{
		lwf=((typeof d.hasFocus!='undefined'?d.hasFocus():wf)?1:0);
	}
	if(lwf == 1){
			$("#focusoff").remove();
			if(adloaded !== true){
				$(".adwait").show();
			}
			$("#progress").show();
			$(".errorbox").show();
			$(".successbox").show();
			$("#progressbar").link2progress(secs, function(){
				endprogress('');
			});			
			
	}else{
		$("#progressbar").link2pause();
		$(".adwait").hide();
		$("#progress").hide();
		$(".errorbox").hide();
		$(".successbox").hide();
		if($("#focusoff").length <=0){
			$("#surfbar").append('<div id="focusoff">You need to keep this advert on focus to get credit<br /><a href=javascript:void(0); style=font-size:13px>Please click here to continue</a></span></div>');
		}
	}
}
function veradinfo(){
	if($(".adwait").css('display') != 'none'){
		showadbar(errormsg);
	}
}

function executead(error){
	if(adex == false){
	adex = true;
	showadbar(error);
	}
}


var adloaded = false;
function showadbar(error){
	if(adloaded === false){
		adloaded = true;
		adex = true;
		if(adex == true){
		$("#pgl").removeAttr("onload");
		if(error == ''){
			$(".adwait").fadeOut(1000, function(){
			$("#surfbar").html('<div class="progressbar" id="progress"><div id="progressbar"></div></div>');
			$("#progressbar").link2progress(secs, function(){
			endprogress('');
			});
			});
			}else{
			$(".adwait").fadeOut(1000, function(){
			$("#surfbar").html("<div class='errorbox'>"+error+"</div>");
			$(".errorbox").fadeIn(1000);
			});
			}	
		}
	}
}
/*	 End Surf Bar */
	function endprogress(masterkey){
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
	 var dataString = 'action=validate&t='+adtk+'&masterkey='+masterkey; 
			$.ajax({
				type: "POST",
				url: "index.php?view=surfer&",
				data: dataString,
				success: function(msg){
					if(msg=='ok'){
						$("#surfbar").html("<div class='successbox'>"+adcredited+"</div>");
						$(".successbox").fadeIn('slow');
						if(adtk == 'YWRtaW5hZHZlcnRpc2VtZW50'){
							window.opener.hideAdminAdvertisement();
						}else{
							window.opener.hideAdvertisement(adtk);
						}
						return false;
					}else{
						
						 $("#surfbar").html("<div class='errorbox'>"+msg+"</div>");
						 $(".errorbox").fadeIn('slow');
					}
				}
			});
	}	
	function ptcevolution_surfer(){
		if (top != self) {
			try { top.location = self.location; }
			catch (err) { self.location = '/FrameDenied.aspx'; }
		}
		 $("#surfbar").html("<div class='adwait'>"+adwait+"</div>");
	}

var errormsg = '';
	function looknrun(secs, error_msg){
		errormsg = error_msg;
		if(secs > 0){
			secsr = secs*1000;
			window.setTimeout("showadbar('"+error_msg+"')",secsr);
		}
		$("#pgl").load(function (){
			showadbar(error_msg);			  
		});
	}