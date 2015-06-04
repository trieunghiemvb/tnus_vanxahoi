/**
 * jQuery.scrollerota
 * Version 1.0
 * Copyright (c) 2011 c.bavota - http://bavotasan.com
 * Dual licensed under MIT and GPL.
 * Date: 02/04/2011
**/
(function($){
	$.fn.scrollerota = function(options) {
		// setting the defaults
		// $("#scrollerota").scrollerota({ width: 500, height: 333, padding: 10, speed: 2000, timer: 5000, slideshow: true, easing: "easeInOutQuart" });
		var defaults = {
			width: 500,
			height: 333,
			padding: 10,	
			speed: 2000,
			timer: 5000,
			slideshow: true,
			easing: 'easeInOutQuart'
		};	
		var options = $.extend(defaults, options);
		
		// and the plugin begins
		return this.each(function() {
			// initializing the variables
			var obj, images, texts, first, last, imglimit, txtlimit, itemNum, totalWidth, totalHeight, txtTop, imgLeft, txtMove, imgMove;
	
			// creating the object variable
			obj = $(this);
			images = obj.find("ul.images");
			texts = obj.find("ul.text");
			
			// cloning the first and last item to create the infinite scrolling
 			first = images.find("li:first");
			last = images.find("li:last");
 			first.clone().appendTo(images);
 			last.clone().prependTo(images);
			
 			first = texts.find("li:first");
			last = texts.find("li:last");
 			first.clone().appendTo(texts);
 			last.clone().prependTo(texts);
			
			// figuring out the total width and height for the images and the text boxes
			itemNum = images.find("li").length;
			totalWidth = itemNum * options.width;
			totalHeight = itemNum * options.height;
			imglimit = -((itemNum - 1) * options.width);
			txtlimit = -((itemNum - 1) * options.height);
 
 			// setting the CSS for the image elements
			images
				.css({
					width: totalWidth+"px",
					height: options.height+"px",
					left: -options.width+"px"
				})
				.find("li").css({ width: options.width+"px", height: options.height+"px" })
				.end()
				.find("li img").css({ width: options.width+"px", height: options.height+"px" });
			
			// setting the CSS for the text elements
			texts
				.css({
					height: totalHeight+"px",
					top: -options.height+"px"
				})			
				.find("li").css({ height: (options.height-(options.padding*2))+"px", padding: options.padding+"px" });
				
			// slideshow functionality
			if(options.slideshow) {
				// creating the loop for the slideshow
				loop = setTimeout(function() { autoScroll(1,1); }, options.timer);
				
				// adding the controls for the slideshow
				obj.append('<div class="controls"><a href="javascript:void(0)" class="prev"></a> <a href="javascript:void(0)" class="play"></a> <a href="javascript:void(0)" class="pause"></a> <a href="javascript:void(0)" class="next"></a></div>')
				
				// the pause click function
				obj.find(".pause").live("click", function() {
					clearTimeout(loop);
					obj.find(".play, .pause").toggle();
	
				});
	
				// the play click function
				obj.find(".play").live("click", function() {
					loop = setTimeout(function() { autoScroll(1, 1); }, options.timer);
					obj.find(".play, .pause").toggle();
	
				});
			} else {
				// adding the next and previous controls
				obj.append('<div class="controls"><a href="javascript:void(0)" class="prev"></a> <a href="javascript:void(0)" class="next"></a></div>')
			}

			// the next and previous click function
			obj.find(".next, .prev").live("click", function() {
				if($(this).hasClass("next")) {
					autoScroll(1,0);
				} else {
					autoScroll(0,0);
				}
				if(options.slideshow) {
					clearTimeout(loop);
					obj.find(".play").show();
					obj.find(".pause").hide();
				}
			});

			// the autoScroll function
			function autoScroll(next, auto) {
				txtTop = texts.css('top').replace("px", "");
				imgLeft = images.css('left').replace("px", "");
				txtMove = (next) ? txtTop - options.height : parseInt(txtTop) + parseInt(options.height);
				imgMove = (next) ? imgLeft - options.width : parseInt(imgLeft) + parseInt(options.width);
				
				// animating the text
				texts.not(':animated').animate({ top: txtMove+"px" }, options.speed, options.easing, function() {
						// check if we have reach the end in either direction of the scroll
						if(txtMove==txtlimit) { texts.css({ top: -options.height+"px" }); }
						if(txtMove==0) { texts.css({ top: (txtlimit+options.height)+"px" }); }
					});

				// animating the images
				images.not(':animated').animate({ left: imgMove+"px" }, options.speed, options.easing, function() {
						// check if we have reach the end in either direction of the scroll
						if(imgMove==imglimit) { images.css({ left: -options.width+"px" }); }
						if(imgMove==0) { images.css({ left: (imglimit+options.width)+"px" }); }
					});				

				// continuing the loop if the slideshow is activated
				if(auto && options.slideshow) { loop = setTimeout(function() { autoScroll(1,1); }, options.timer); }
			}
		});
	};
})(jQuery);