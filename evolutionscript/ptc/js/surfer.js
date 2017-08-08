$(document).ready(function() {	
	ptcevolution_surfer();
});
var adloaded = false;
function showadbar(error){
	if(adloaded === false){
		adloaded = true;
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
	
	function looknrun(secs, error_msg){
		if(secs > 0){
			secsr = secs*1000;
			window.setTimeout("showadbar('"+error_msg+"')",secsr);
		}
		$("#pgl").load(function (){
			showadbar(error_msg);			  
		});
	}