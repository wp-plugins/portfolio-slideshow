$=jQuery.noConflict();
this.vtip=function(){this.xOffset=-10;this.yOffset=10;$(".vtip").unbind().hover(function(e){this.t=this.title;this.title='';this.top=(e.pageY+yOffset);this.left=(e.pageX+xOffset);$('body').append('<p id="vtip"><img id="vtipArrow" />'+this.t+'</p>');$('p#vtip').css("top",this.top+"px").css("left",this.left+"px").fadeIn("slow");},function(){this.title=this.t;$("p#vtip").fadeOut("slow").remove();}).mousemove(function(e){this.top=(e.pageY+yOffset);this.left=(e.pageX+xOffset);$("p#vtip").css("top",this.top+"px").css("left",this.left+"px");});};jQuery(document).ready(function($){vtip();})

$(function() {
	$( "#tabs" ).tabs();
});