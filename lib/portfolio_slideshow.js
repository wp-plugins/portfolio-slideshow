jQuery(document).ready(function($) {
	
	$(window).load(function() {
	
	//if($ps_showloader=="true" will need to set this up)
	
			$('div.slideshow-holder').delay(1500).fadeOut('fast', function() 																							
				{$('div.slideshow-wrapper').css('visibility','visible');
			});
		
			$(function() {
				var index = 0, hash = window.location.hash;
				if (hash) {
				index = /\d+/.exec(hash)[0];
				index = (parseInt(index) || 1) - 1; // slides are zero-based
			} 	
			
		$(this).cycle({
				fx: psTrans,
				speed: psSpeed,
				timeout: psTimeout,
				next: '#slideshow-wrapper' + num + ' a.slideshow-next',
				startingSlide: index,
				prev: '#slideshow-wrapper' + num + ' a.slideshow-prev',
				before:     onBefore,
				after:     onAfter,
				pager:  '#slides'+num,
				manualTrump: false,
				cleartypeNoBg: true,
				pagerAnchorBuilder: function(idx, slide) {
				// return sel string for existing anchor
				return '#slides'+num ' li:eq(' + (idx) + ') a'; }
		});
	

		$('.slideshow-nav'+num ' a.pause').click(function() { 
			$('#portfolio-slideshow'+num).cycle('pause');
			$('.slideshow-nav'+num ' a.pause').hide();
			$('.slideshow-nav'+num ' a.play').show();
		});
	
		$('.slideshow-nav'+num ' a.play').click(function() { 
			$('#portfolio-slideshow'+num).cycle('resume');
			$('.slideshow-nav'+num ' a.play').hide();
			$('.slideshow-nav'+num ' a.pause').show();
		});
		
		function onBefore(curr,next,opts) {
			$("p.slideshow-caption, p.slideshow-title, p.slideshow-description", this).css("visibility", "hidden");
		}
		
		function onAfter(curr,next,opts) {
			
			var $ht = $("img",this).attr("height");
			if ($("p.slideshow-caption", this).length ) { 
				var $oht = $("p.slideshow-caption", this).outerHeight('true');
			} else {
    			var $oht = 0;
			}
			if ($("p.slideshow-description", this).length ) {
				var $pht = $("p.slideshow-description", this).outerHeight('true');
			} else {
			var $pht = 0;
    			
			}
			if ($("p.slideshow-title", this).length ) { 
				var $qht = $("p.slideshow-title", this).outerHeight('true'); 
			} else {
    			var $qht = 0;
			} 
			$('#portfolio-slideshow'+num).css("height", $ht + $oht + $pht + $qht);
						
			$("p.slideshow-caption, p.slideshow-title, p.slideshow-description", this).css("visibility", "visible");
					
			//if ($ps_showhash=="true") { if (is_page() || is_single()) { set this up via js
			
	  		window.location.hash = opts.currSlide + 1;
			
	 		var caption = (opts.currSlide + 1) + ' of ' + opts.slideCount;
				$('#slideshow-info'+num ').html(caption);
			} }); }); });</script>'; 