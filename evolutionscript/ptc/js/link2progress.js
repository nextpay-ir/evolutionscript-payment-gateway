	( function($) {
		$.fn.link2progress = function(seconds, callback){
				this.each(function(){
					var $this = jQuery(this);
					progreso = 100;
					timeprogress = seconds*1000;
					seconds2 = 0;
					$this.animate({
						width: progreso+'%'
					}, {
						duration: timeprogress, 
						complete: function(scope, i, elem){
						  if (callback) {
							callback.call(this, i, elem );
						  };
						}
					});
				});
		}
		$.fn.link2pause = function(){
				this.clearQueue();
				this.stop();
		}
		$.fn.link2continue = function(){
					this.clearQueue();
		}
	})(jQuery);