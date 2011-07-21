jQuery(document).ready(function($) {
	$(window).load(function() { //wait until the window loads to start cycle so we can get accurate CSS for fluid-width images
	
		var psLoader = portfolioSlideshowOptions['psLoader'];
		var psHash = portfolioSlideshowOptions['psHash'];
		currSlide = new Array(); 

			if(psLoader=="true") {
				$('div.slideshow-holder').delay(1500).fadeOut('fast', function() 																							
					{$('div.slideshow-wrapper').css('visibility','visible');
				}); } else {
					$('div.slideshow-wrapper').css('visibility','visible');
				};
			   	
			$("div[id^=portfolio-slideshow]").each(function() {
				var num = this.id.match(/portfolio-slideshow(\d+)/)[1];
					
				$(window).resize(function() { //on window resize, force resize of the slideshows
					$('#portfolio-slideshow' + num).css('width','').css('height','');	
					var $h, $w;
					$h = $('#portfolio-slideshow' + num).find('.slideshow-content').eq(currSlide[num]).outerHeight();
					$w = $('#portfolio-slideshow' + num).find('.slideshow-content').eq(currSlide[num]).width();
					$('#portfolio-slideshow' + num).css("height", $h).css("width", $w);
				});		
				
				$(function() {
					var index = 0, hash = window.location.hash;
					if (/\d+/.exec(hash)) {
					index = /\d+/.exec(hash)[0];
					index = (parseInt(index) || 1) - 1; // slides are zero-based
				} 	
					
				$('#portfolio-slideshow'+num).cycle({
						fx: psTrans[num],
						speed: psSpeed[num],
						timeout: psTimeout[num],
						nowrap: psNoWrap[num],
						next: '#slideshow-wrapper' + num + ' a.slideshow-next',
						startingSlide: index,
						prev: '#slideshow-wrapper' + num + ' a.slideshow-prev',
						before:     onBefore,
						after:     onAfter,
						pager:  '#slides'+num,
						slideExpr:	'.slideshow-content',
						manualTrump: true,
						cleartypeNoBg: true,
						slideResize: false,
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
					//this adjusts the height of the slideshow
					var $h, $w;
					$h = $(this).height();
					$w = $(next).width();
					$('#portfolio-slideshow' + num).css("height", $h);
					$('#portfolio-slideshow' + num).css("width", $w);
				}
				
				function onAfter(curr,next,opts) {
					currSlide[num] = opts.currSlide;	
					if (psNoWrap[num] == true) {
						if (opts.currSlide == 0 ) {
							$('.slideshow-nav' + num + ' .slideshow-prev, .slideshow-nav' + num + ' .sep').addClass('inactive');
						} else {
							$('.slideshow-nav' + num + ' .slideshow-prev, .slideshow-nav' + num + ' .sep').removeClass('inactive');
						}
						if (opts.currSlide == opts.slideCount-1) {
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
				} }); 
			}); 
	});
});	