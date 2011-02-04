jQuery(document).ready(function($) {

	$(window).load(function() {
	
	var psLoader = portfolioSlideshowOptions['psLoader'];
	var psHash = portfolioSlideshowOptions['psHash'];
	
	
		if(psLoader=="true") {
			$('div.slideshow-holder').delay(1500).fadeOut('fast', function() 																							
				{$('div.slideshow-wrapper').css('visibility','visible');
			}); } else {
				$('div.slideshow-wrapper').css('visibility','visible');
			};
		
		$("div[id^=portfolio-slideshow]").each(function() {
			var num = this.id.match(/portfolio-slideshow(\d+)/)[1];
			
			psTimeout = psTimeout[num];
			psTrans = psTrans[num];
			psSpeed = psSpeed[num];
			psNoWrap = psNoWrap[num];
									
			$(function() {
					var index = 0, hash = window.location.hash;
					if (hash) {
					index = /\d+/.exec(hash)[0];
					index = (parseInt(index) || 1) - 1; // slides are zero-based
				} 	
				
			$('#portfolio-slideshow'+num).cycle({
					fx: psTrans,
					speed: psSpeed,
					timeout: psTimeout,
					nowrap: psNoWrap,
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
					return '#slides'+num+' li:eq(' + (idx) + ') a'; }
			});
		
	
			$('.slideshow-nav'+num+' a.pause').click(function() { 
				$('#portfolio-slideshow'+num).cycle('pause');
				$('.slideshow-nav'+num+' a.pause').hide();
				$('.slideshow-nav'+num+' a.play').show();
			});
		
			$('.slideshow-nav'+num+' a.play').click(function() { 
				$('#portfolio-slideshow'+num).cycle('resume');
				$('.slideshow-nav'+num+' a.play').hide();
				$('.slideshow-nav'+num+' a.pause').show();
			});
			
			function onBefore(curr,next,opts) {
				var $ht = $(this).height();
				$('#portfolio-slideshow'+num).css("height", $ht);	

				$('#portfolio-slideshow'+num).animate({
					height: $ht
					}, 400, function() {
					// Animation complete.
				});
			}
			
			function onAfter(curr,next,opts) {
			
				if (psNoWrap == true) {
					if (opts.currSlide == 0 ) {
						$('.slideshow-nav' + num + ' .slideshow-prev, .slideshow-nav' + num + ' .sep').addClass('inactive');
					} else {
						$('.slideshow-nav' + num + ' .slideshow-prev, .slideshow-nav' + num + ' .sep').removeClass('inactive');
					}
					if (opts.currSlide == opts.slideCount -1) {
						$('.slideshow-nav' + num + ' .slideshow-next, .slideshow-nav' + num + ' .sep').addClass('inactive');
					} else {
						$('.slideshow-nav' + num + ' .slideshow-next').removeClass('inactive');
					}
				}
			
				if (psHash=='true') { 
					window.location.hash = opts.currSlide + 1;
				};
				var caption = (opts.currSlide + 1) + ' of ' + opts.slideCount;
				$('.slideshow-info'+num).html(caption);
			} }); }); 
	});
});