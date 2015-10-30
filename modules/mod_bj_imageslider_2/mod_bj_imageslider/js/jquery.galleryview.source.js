/**
 * jQuery.timers - Timer abstractions for jQuery
 * Written by Blair Mitchelmore (blair DOT mitchelmore AT gmail DOT com)
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/).
 * Date: 2009/02/08
 *
 * @author Blair Mitchelmore
 * @version 1.1.2
 *
 **/

jQuery.fn.extend({
	everyTime: function(interval, label, fn, times, belay) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, times, belay);
		});
	},
	oneTime: function(interval, label, fn) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, 1);
		});
	},
	stopTime: function(label, fn) {
		return this.each(function() {
			jQuery.timer.remove(this, label, fn);
		});
	}
});

//jQuery.event.special

jQuery.extend({
	timer: {
		global: [],
		guid: 1,
		dataKey: "jQuery.timer",
		regex: /^([0-9]+(?:\.[0-9]*)?)\s*(.*s)?$/,
		powers: {
			// Yeah this is major overkill...
			'ms': 1,
			'cs': 10,
			'ds': 100,
			's': 1000,
			'das': 10000,
			'hs': 100000,
			'ks': 1000000
		},
		timeParse: function(value) {
			if (value == undefined || value == null)
				return null;
			var result = this.regex.exec(jQuery.trim(value.toString()));
			if (result[2]) {
				var num = parseFloat(result[1]);
				var mult = this.powers[result[2]] || 1;
				return num * mult;
			} else {
				return value;
			}
		},
		add: function(element, interval, label, fn, times, belay) {
			var counter = 0;
			
			if (jQuery.isFunction(label)) {
				if (!times) 
					times = fn;
				fn = label;
				label = interval;
			}
			
			interval = jQuery.timer.timeParse(interval);

			if (typeof interval != 'number' || isNaN(interval) || interval <= 0)
				return;

			if (times && times.constructor != Number) {
				belay = !!times;
				times = 0;
			}
			
			times = times || 0;
			belay = belay || false;
			
			var timers = jQuery.data(element, this.dataKey) || jQuery.data(element, this.dataKey, {});
			
			if (!timers[label])
				timers[label] = {};
			
			fn.timerID = fn.timerID || this.guid++;
			
			var handler = function() {
				if (belay && this.inProgress) 
					return;
				this.inProgress = true;
				if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
					jQuery.timer.remove(element, label, fn);
				this.inProgress = false;
			};
			
			handler.timerID = fn.timerID;
			
			if (!timers[label][fn.timerID])
				timers[label][fn.timerID] = window.setInterval(handler,interval);
			
			this.global.push( element );
			
		},
		remove: function(element, label, fn) {
			var timers = jQuery.data(element, this.dataKey), ret;
			
			if ( timers ) {
				
				if (!label) {
					for ( label in timers )
						this.remove(element, label, fn);
				} else if ( timers[label] ) {
					if ( fn ) {
						if ( fn.timerID ) {
							window.clearInterval(timers[label][fn.timerID]);
							delete timers[label][fn.timerID];
						}
					} else {
						for ( var fn in timers[label] ) {
							window.clearInterval(timers[label][fn]);
							delete timers[label][fn];
						}
					}
					
					for ( ret in timers[label] ) break;
					if ( !ret ) {
						ret = null;
						delete timers[label];
					}
				}
				
				for ( ret in timers ) break;
				if ( !ret ) 
					jQuery.removeData(element, this.dataKey);
			}
		}
	}
});

jQuery(window).bind("unload", function() {
	jQuery.each(jQuery.timer.global, function(index, item) {
		jQuery.timer.remove(item);
	});
});

(function($) {
	$.fn.customFadeIn = function(speed, callback) {
		$(this).fadeIn(speed, function() {
			if(!$.support.opacity)
				$(this).get(0).style.removeAttribute('filter');
			if(callback != undefined)
				callback();
		});
	};
	$.fn.customFadeOut = function(speed, callback) {
		$(this).fadeOut(speed, function() {
			if(!$.support.opacity)
				$(this).get(0).style.removeAttribute('filter');
			if(callback != undefined)
				callback();
		});
	};
	$.fn.customFadeTo = function(speed,to,callback) {
		return this.animate({opacity: to}, speed, function() {
			if (to == 1 && jQuery.browser.msie)
				this.style.removeAttribute('filter');
			if (jQuery.isFunction(callback))
				callback();
		});
	};
})(jQuery);

/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	}
});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2001 Robert Penner
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
 */
 
/*  ===================================== START BJ IMAGE_SLIDER
	BJ IMAGESLIDER : GALLERY VIEW FOR JOOMLA
	Author:			Ha Doan Ngoc
	Version:		2.1 (February 2010)
	Documentation:
	License:		Commercial
	
OTHER LICENSE

	GalleryView - jQuery Content Gallery Plugin
	Author: 		Jack Anderson
	Version:		2.0 (May 5, 2009)
	Documentation: 	http://www.spaceforaname.com/galleryview/
	
	Please use this development script if you intend to make changes to the
	plugin code.  For production sites, please use jquery.galleryview-2.0-pack.js.
	
	See CHANGELOG.txt for a review of changes and LICENSE.txt for the applicable
	licensing information.

	
*/

//Global variable to check if window is already loaded
//Used for calling GalleryView after page has loaded
var window_loaded = false;
			
(function($){
	$.fn.galleryView = function(options) {
		var opts = options;// $.extend($.fn.galleryView.defaults,options);
		var id;
		var iterator = 0;
		var item_count = 0;
		var item_flag_index = 0; // mark the left index of strip_wrapper
		var slide_method;
		var theme_path;
		var paused = false;
		var controller_show = false; // check whether controller is showed or not
		var filmstrip_hide = true; // check whether filmstrip is showed or not
		var first_image_loaded = false;
		var gallery_built = false;
		
		//Element dimensions
		var gallery_width;
		var gallery_height;
		var pointer_height;
		var pointer_width;
		var strip_width;
		var strip_height;
		var wrapper_width;
		var f_frame_width;
		var f_frame_height;
		var frame_caption_size = 20;
		var gallery_padding;
		var filmstrip_margin;
		var filmstrip_orientation;
		
		//Arrays used to scale frames and panels
		var frame_img_scale = new Object();
		var panel_img_scale = new Object();
		var img_h = new Object();
		var img_w = new Object();
		
		//Flag indicating whether to scale panel images
		var scale_panel_images = true;
		
		var panel_nav_displayed = false;
		
		//Define jQuery objects for reuse
		var j_gallery;
		var j_filmstrip;
		var j_frames;
		var j_frame_img_wrappers;
		var j_panels;
		var j_pointer;
		
		var timeoutID;
		var mouseX,mouseY;
/************************************************/
/*	Plugin Methods								*/
/************************************************/	

	//Transition from current item to item 'i'
		function showItem(i) {	
			
			//Disable next/prev buttons until transition is complete
			$('.nav-next-overlay',j_gallery).unbind('click');
			$('.nav-prev-overlay',j_gallery).unbind('click');
			$('.nav-next',j_gallery).unbind('click');
			$('.nav-prev',j_gallery).unbind('click');
			j_frames.unbind('click');
			
			//Fade out all frames while fading in target frame
			if(opts.show_filmstrip) {
				j_frames.removeClass('current').find('img').stop().animate({
					'opacity':opts.frame_opacity
				},opts.transition_speed);
				j_frames.eq(i).addClass('current').find('img').stop().animate({
					'opacity':1.0
				},opts.transition_speed);
			}
			
			if(opts.show_panels && opts.fade_panels) {
				if(opts.show_captions){
					switch(opts.text_transition_effect){
						case 'blur':
							var html = $('.panel-overlay',j_panels.eq(i%item_count)).html();
							$('.overlay-content-panel',j_gallery).html("");
							$('.overlay-content-panel',j_gallery).html("<div style='display:none' class='content'>"+html+"</div>");
							$('.content',$('.overlay-content-panel',j_gallery)).fadeIn(opts.transition_speed);
							break;
					}
				}
				var func = function(){
					if(!opts.show_filmstrip) {
						$('.nav-prev-overlay',j_gallery).click(showPrevItem);
						$('.nav-next-overlay',j_gallery).click(showNextItem);
						$('.nav-prev',j_gallery).click(showPrevItem);
						$('.nav-next',j_gallery).click(showNextItem);		
					}
				};

				switch(opts.image_transition_effect){
					case 'blur':
						j_panels.fadeOut(opts.transition_speed).eq(i%item_count).fadeIn(opts.transition_speed,func);
						break;
				}
			}
			
			//If gallery has a filmstrip, handle animation of frames
			if(opts.show_filmstrip) {
				// Change slide_method depends on current pointer position
				if(item_flag_index == 0)
				{
					if(i < strip_size - 1) slide_method = 'pointer'; else slide_method = 'strip';
				} else if(item_flag_index == item_count - strip_size){
					if(i > item_flag_index) slide_method = 'pointer'; else slide_method = 'strip';
				} else {
					if(i > item_flag_index && i < item_flag_index + strip_size - 1) slide_method = 'pointer'; else slide_method = 'strip';
				}				
				//Slide either pointer or filmstrip, depending on transition method
				if(slide_method=='strip') {
					//Stop filmstrip if it's currently in motion
					j_filmstrip.stop();
					if(filmstrip_orientation=='horizontal') {
						//Determine distance between pointer (eventual destination) and target frame
						var distance = 0;
						var pointer_distance = 0;
						
						if(i > iterator)
						{
							if(i < item_count - 1)
							{
								distance = (getPos(j_frames[item_flag_index+strip_size-1]).left) - getPos(j_frames[i-1]).left;
								pointer_distance = (getPos(j_frames[item_flag_index+strip_size-2]).left-(pointer_width/2)+(f_frame_width/2));
							} 
							else 
							{							
								distance = getPos(j_frames[i]).left - (getPos(j_frames[item_flag_index+strip_size-1]).left);
								pointer_distance = (getPos(j_frames[item_flag_index+strip_size-1]).left-(pointer_width/2)+(f_frame_width/2));
							}
						}
						else
						{
							if(i > 0)
							{
								distance = getPos(j_frames[i-1]).left - (getPos(j_frames[item_flag_index]).left);
								pointer_distance = (getPos(j_frames[item_flag_index+1]).left-(pointer_width/2)+(f_frame_width/2));
							} else 
							{
								distance = getPos(j_frames[0]).left - (getPos(j_frames[item_flag_index]).left);
								pointer_distance = (getPos(j_frames[item_flag_index]).left-(pointer_width/2)+(f_frame_width/2));
							}
						}
						
						
						var diststr = (distance>=0?'-=':'+=')+Math.abs(distance)+'px';
						
						//Animate pointer
						j_pointer.animate({
							'left':(pointer_distance+'px')
						},opts.transition_speed,'swing',function(){});
						
						//Animate filmstrip and slide target frame under pointer
						j_filmstrip.animate({
							'left':diststr
						},opts.transition_speed,'swing',function(){							
							//Always ensure that there are a sufficient number of hidden frames on either
							//side of the filmstrip to avoid empty frames
							if(i < iterator)
								if(i==0) item_flag_index = 0;
								else item_flag_index = i - 1;
							else
								if(i < item_count - 1) item_flag_index = i + 2 - strip_size;
								else item_flag_index = item_count - strip_size;
							
							iterator = i;
							
							if(!opts.fade_panels) {
								j_panels.hide().eq(i%item_count).show();
							}
							
							//Enable navigation now that animation is complete
							$('.nav-prev-overlay',j_gallery).click(showPrevItem);
							$('.nav-next-overlay',j_gallery).click(showNextItem);
							$('.nav-prev',j_gallery).click(showPrevItem);
							$('.nav-next',j_gallery).click(showNextItem);
							enableFrameClicking();
						}
						);
					} else {
						//Determine distance between pointer (eventual destination) and target frame
						var distance = getPos(j_frames[i]).top - (getPos(j_pointer[0]).top+(pointer_height)-(f_frame_height/2));
						var diststr = (distance>=0?'-=':'+=')+Math.abs(distance)+'px';
						
						//Animate filmstrip and slide target frame under pointer
						j_filmstrip.animate({
							'top':diststr
						},opts.transition_speed,'swing',function(){
							//Always ensure that there are a sufficient number of hidden frames on either
							//side of the filmstrip to avoid empty frames
							var old_i = i;
							if(i>item_count) {
								i = i%item_count;
								iterator = i;
								j_filmstrip.css('top','-'+((f_frame_height+opts.frame_gap)*i)+'px');
							} else if (i<=(item_count-strip_size)) {
								i = (i%item_count)+item_count;
								iterator = i;
								j_filmstrip.css('top','-'+((f_frame_height+opts.frame_gap)*i)+'px');
							}
							//If the target frame has changed due to filmstrip shifting,
							//Make sure new target frame has 'current' class and correct size/opacity settings
							if(old_i != i) {
								j_frames.eq(old_i).removeClass('current').find('img').css({
									'opacity':opts.frame_opacity
								});
								j_frames.eq(i).addClass('current').find('img').css({
									'opacity':1.0
								});
							}
							if(!opts.fade_panels) {
								j_panels.hide().eq(i%item_count).show();
							}
							
							//Enable navigation now that animation is complete
							$('.nav-prev-overlay',j_gallery).click(showPrevItem);
							$('.nav-next-overlay',j_gallery).click(showNextItem);
							$('.nav-prev',j_gallery).click(showPrevItem);
							$('.nav-next',j_gallery).click(showNextItem);
							enableFrameClicking();
						});
					}
				} else if(slide_method=='pointer') {
					//Stop pointer if it's currently in motion
					j_pointer.stop();
					//Get position of target frame
					var pos = getPos(j_frames[i]);
					
					if(filmstrip_orientation=='horizontal') {
						//Slide the pointer over the target frame
						j_pointer.animate({
							'left':(pos.left+(f_frame_width/2)-(pointer_width/2)+'px')
						},opts.transition_speed,'swing',function(){
							iterator = i;
							if(!opts.fade_panels) {
								j_panels.hide().eq(i%item_count).show();
							}	
							$('.nav-prev-overlay',j_gallery).click(showPrevItem);
							$('.nav-next-overlay',j_gallery).click(showNextItem);
							$('.nav-prev',j_gallery).click(showPrevItem);
							$('.nav-next',j_gallery).click(showNextItem);
							enableFrameClicking();
						});
					} else {//Slide the pointer over the target frame
						j_pointer.animate({
							'top':(pos.top+(f_frame_height/2)-(pointer_height)+'px')
						},opts.transition_speed,'swing',function(){	
							if(!opts.fade_panels) {
								j_panels.hide().eq(i%item_count).show();
							}	
							$('.nav-prev-overlay',j_gallery).click(showPrevItem);
							$('.nav-next-overlay',j_gallery).click(showNextItem);
							$('.nav-prev',j_gallery).click(showPrevItem);
							$('.nav-next',j_gallery).click(showNextItem);
							enableFrameClicking();
						});
					}
				}
			
			} else {
				if(i < iterator)
					if(i==0) item_flag_index = 0;
					else item_flag_index = i - 1;
				else
					if(i < item_count - 1) item_flag_index = i + 2 - strip_size;
					else item_flag_index = item_count - strip_size;
				
				iterator = i;
			}
		};
		
	//Find padding and border widths applied to element
	//If border is non-numerical ('thin','medium', etc) set to zero
		function extraWidth(el) {
			if(!el) return 0;
			if(el.length==0) return 0;
			el = el.eq(0);
			var ew = 0;
			ew += getInt(el.css('paddingLeft'));
			ew += getInt(el.css('paddingRight'));
			ew += getInt(el.css('borderLeftWidth'));
			ew += getInt(el.css('borderRightWidth'));
			return ew;
		}
	//Find padding and border heights applied to element
	//If border is non-numerical ('thin','medium', etc) set to zero
		function extraHeight(el) {
			if(!el) return 0;
			if(el.length==0) return 0;
			el = el.eq(0);
			var eh = 0;
			eh += getInt(el.css('paddingTop'));
			eh += getInt(el.css('paddingBottom'));
			eh += getInt(el.css('borderTopWidth'));
			eh += getInt(el.css('borderBottomWidth'));
			return eh;
		}
		
		function play() {
			var controller = $(".controller",j_gallery);
			if(controller.hasClass('playing')){
				controller.removeClass('playing').addClass('paused');
				controller.attr('src',theme_path+'play.png');
				
				if(!paused) {
					$(document).oneTime(500,"animation_pause",function(){
						$(document).stopTime("transition");
						paused=true;
					});
				}
			} else {
				controller.removeClass('paused').addClass('playing');
				controller.attr('src',theme_path+'pause.png');
				
				$(document).stopTime("animation_pause");
				if(paused) {
					$(document).everyTime(opts.transition_interval,"transition",function(){
						showNextItem();
					});
					paused = false;
				}
			}
		}
		
		function pause() {
			var controller = $(".controller",j_gallery);
			if(controller != null)
			{
				controller.removeClass('playing').addClass('paused');
				controller.attr('src',theme_path+'play.png');
			}
			
			if(!paused) {
				$(document).oneTime(500,"animation_pause",function(){
					$(document).stopTime("transition");
					paused=true;
				});
			}
		}
		
	//Halt transition timer, move to next item, restart timer
		function showNextItem() {
			$(document).stopTime("transition");
			
			if((iterator+1)==item_count) {showItem(0);}
			else showItem(iterator+1);
			if(!paused) {
				$(document).everyTime(opts.transition_interval,"transition",function(){
					showNextItem();
				});
			}
		};
		
	//Halt transition timer, move to previous item, restart timer
		function showPrevItem() {
			$(document).stopTime("transition");
			if(iterator==0) {showItem(item_count-1);}
			else showItem(iterator-1);
			if(!paused) {
				$(document).everyTime(opts.transition_interval,"transition",function(){
					showPrevItem();
				});
			}
		};
		
	//Get absolute position of element in relation to top-left corner of gallery
	//If el=gallery, return position of gallery within browser viewport
		function getPos(el) {
			var left = 0, top = 0;
			var el_id = el.id;
			if(el.offsetParent) {
				do {
					left += el.offsetLeft;
					top += el.offsetTop;
				} while(el = el.offsetParent);
			}
			//If we want the position of the gallery itself, return it
			if(el_id == id) {return {'left':left,'top':top};}
			//Otherwise, get position of element relative to gallery
			else {
				var gPos = getPos(j_gallery[0]);
				var gLeft = gPos.left;
				var gTop = gPos.top;
				
				return {'left':left-gLeft,'top':top-gTop};
			}
		};
		
	//Add onclick event to each frame
		function enableFrameClicking() {
			j_frames.each(function(i){
				//If there isn't a link in this frame, set up frame to slide on click
				//Frames with links will handle themselves
				if($('a',this).length==0) {
					$(this).click(function(){
						$(document).stopTime("transition");
						showItem(i);
						//iterator = i;
						if(!paused) {
							$(document).everyTime(opts.transition_interval,"transition",function(){
								showNextItem();
							});
						}
					});
				}
			});
		};
		
	//Construct gallery panels from '.panel' <div>s
		function buildPanels() {
			j_panels.each(function(i){
				$(this).css({
					'width':(opts.panel_width-extraWidth(j_panels))+'px',
					'height':(opts.panel_height-extraHeight(j_panels))+'px',
					'position':'absolute'
				});
				//$(this).css({'top':gallery_padding+'px','left':gallery_padding+'px'});
				$(this).css({'top':0+'px','left':'0px'});
			});
			
			caption_overlay_padding = extraWidth($('.panel-overlay',j_gallery));
			$('.panel-overlay',j_gallery).css({
				'position':'absolute',
				'zIndex':'999',
				'width':(opts.panel_width-2*caption_overlay_padding)+'px',
				'left':'0',
				'display':'none'
			});
			
			/* OVERLAY BACKGROUND */
			if(opts.show_captions){
				overlayBackground = $("<div>");
				overlayBackground.appendTo(j_gallery);
				overlayBackground.addClass('overlay-background');
				overlayBackground.css({
					'position':'absolute',
					'z-index':5000,
					'opacity':opts.overlay_opacity
				});
				
				overlayContent = $("<div>");
				overlayContent.appendTo(j_gallery);
				overlayContent.addClass('overlay-content-panel');
				overlayContent.css({
					'position':'absolute'
					,'z-index':5001
					,'overflow':'hidden'
				});
				
				if(opts.overlay_position=='right'){
					overlayContent.css('top',extraHeight($('.panel-overlay',j_gallery)));
					overlayBackground.css('top','0px');
					overlayContent.css('right',caption_overlay_padding + 'px');
					overlayBackground.css('right','0px');
					overlayContent.css('left','auto');
					overlayBackground.css('left','auto');
					overlayContent.css('width',opts.caption_thickness-caption_overlay_padding);
					overlayBackground.css('width',opts.caption_thickness);					
					overlayContent.css('height',opts.panel_height-extraHeight($('.panel-overlay',j_gallery)));
					overlayBackground.css('height',opts.panel_height-extraHeight($('.overlay-background',j_gallery)));
					overlayBackground.addClass('overlay-background-left');
				}
			}
		};
		
		function buildNextTitlePanel() {
			/* NEXT TITLE PANELS */
			var next_title = $("<div>");
			next_title.addClass("next_title");
			next_title.appendTo(j_gallery);
			next_title.css({
				'position':'absolute',
				'z-index':5001
			});
			
			switch(opts.filmstrip_position){
				case 'bottom':
					next_title.css({
					'bottom':'0px',
					'padding-bottom':(f_frame_height-16)/2+'px'
					,'padding-left':'23px'
					});
					break;
			};
			final_caption_width = opts.caption_thickness+extraWidth($('.panel-overlay',j_panels));
			switch(opts.overlay_position){
				case 'right':
					next_title.css({
					'right':'0px',
					'width':(final_caption_width-opts.frame_margin-23)+'px',
					'height':f_frame_height+'px',
					'overflow':'hidden'
					});
					break;
			}
		}
		
		// pause-play buttons
		function buildController() {
			
		}
		
	//Construct filmstrip from '.filmstrip' <ul>
		function buildFilmstrip() {
			//Add wrapper to filmstrip to hide extra frames
			j_filmstrip.wrap('<div class="strip_wrapper"></div>');
			if(slide_method=='strip') {
				j_frames.clone().appendTo(j_filmstrip);
				j_frames.clone().appendTo(j_filmstrip);
				j_frames = $('li',j_filmstrip);
			}
			j_filmstrip.css({
				'listStyle':'none',
				'margin':'0',
				'padding':'0',
				'width':strip_width+'px',
				'position':'absolute',
				'zIndex':'900',
				'top':(filmstrip_orientation=='vertical' && slide_method=='strip'?-((f_frame_height+opts.frame_gap)*iterator):0)+'px',
				'left':(filmstrip_orientation=='horizontal' && slide_method=='strip'?-((f_frame_width+opts.frame_gap)*iterator):0)+'px',
				'height':strip_height+'px'
			});
			j_frames.css({
				'float':'left',
				'position':'relative',
				'height':f_frame_height+'px',
				'width':f_frame_width+'px',
				'zIndex':'901',
				'padding':'0',
				'cursor':'pointer'
			});
			switch(opts.filmstrip_position) {
				case 'top': j_frames.css({
								'marginBottom':filmstrip_margin+'px',
								'marginRight':opts.frame_gap+'px'
							}); break;
				case 'bottom': j_frames.css({
								'marginTop':filmstrip_margin/2+'px',
								'marginRight':opts.frame_gap+'px'
							}); break;
				case 'left': j_frames.css({
								'marginRight':filmstrip_margin+'px',
								'marginBottom':opts.frame_gap+'px'
							}); break;
				case 'right': j_frames.css({
								'marginLeft':filmstrip_margin+'px',
								'marginBottom':opts.frame_gap+'px'
							}); break;
			}
			
			$('.img_wrap',j_frames).each(function(i){
				$(this).css({
					'height':opts.frame_height+'px',
					'width':opts.frame_width+'px',
					'position':'relative',
					'left':'0px',
					'overflow':'hidden'
				});
				
				$(this).append("<div class='number'>"+(i+1)+"</div>");
				$(this).hover(function(){
						$(this).addClass('img_wrap_hover');
						if(opts.show_next_item_title)
							showNextTitle(i);
					},
					function(){
						$(this).removeClass('img_wrap_hover');
						if(opts.show_next_item_title)
							showNextTitle(-1);
					});
			});
			$('img',j_frames).css({'marginLeft':'-1000px'});// to hide image, display number 
			$('.number',j_frames).each(function(i){
				$(this).css({'float':'left','margin':'6px 0 0 10px'});
					if(i > 8){
						$(this).css({'margin':'6px 0 0 6px'});
					}
				});
			$('img',j_frames).each(function(i){
				$(this).css({
					'opacity':opts.frame_opacity,
					'height':'0px',
					'width':'0px',
					'position':'relative',
					'top':'0px',
					'left':'0px'
	
				}).mouseover(function(){
					$(this).stop().animate({'opacity':1.0},300);
				}).mouseout(function(){
					//Don't fade out current frame on mouseout
					if(!$(this).parent().parent().hasClass('current')) $(this).stop().animate({'opacity':opts.frame_opacity},300);
				});
			});
			
			$('.strip_wrapper',j_gallery).css({
				'position':'absolute',
				'overflow':'hidden',
				'z-index':5002
			});
			
			var strip_background = $("<div>");
			strip_background.addClass("strip_background");
			strip_background.appendTo(j_gallery);
			strip_background.css({
				'position':'absolute',
				'z-index':5001
				,'opacity':0.5
			});
			
			// final caption thickness
			f_caption_thickness = opts.caption_thickness+extraWidth($('.panel-overlay',j_panels));
				
			if(filmstrip_orientation=='horizontal') {
				$('.strip_wrapper',j_gallery).css({
					'top':(opts.filmstrip_position=='top'?'2px':(opts.panel_height-strip_height)+'px'),
					'width':wrapper_width+'px',
					'height':strip_height+'px'
				});
				
				strip_background.css({
					'top':(opts.filmstrip_position=='top'?'0px':opts.panel_height-strip_height+'px'),
					'left':'0px',
					'width':gallery_width+'px',
					'height':strip_height+'px'
					});
				
				strip_background.addClass('strip_background_'+opts.filmstrip_position);
				
				switch(opts.overlay_position){
					case 'right':
						$('.strip_wrapper',j_gallery).css({
							'right':f_caption_thickness+opts.frame_gap+27+opts.frame_margin+'px'
							});
						break;
				}
			} else {
				$('.strip_wrapper',j_gallery).css({
					'left':(opts.filmstrip_position=='left'?Math.max(0,filmstrip_margin)+'px':gallery_width-strip_width+'px'),
					'top':Math.max(0,opts.frame_gap)+'px',
					'width':strip_width+'px',
					'height':wrapper_height+'px'
				});
			}
			
			if(opts.show_next_item_title)
			buildNextTitlePanel();
			
			var pointer = $('<div></div>');
			pointer.addClass('pointer').appendTo(j_gallery).css({
				 'position':'absolute',
				 'zIndex':'1001',
				 'width':'0px',
				 'fontSize':'0px',
				 'lineHeight':'0%',
				 'borderTopWidth':pointer_height+'px',
				 'borderRightWidth':(pointer_width/2)+'px',
				 'borderBottomWidth':pointer_height+'px',
				 'borderLeftWidth':(pointer_width/2)+'px',
				 'borderStyle':'solid'
			});
			
			//For IE6, use predefined color string in place of transparent (see stylesheet)
			var transColor = $.browser.msie && $.browser.version.substr(0,1)=='6' ? 'pink' : 'transparent';
			
			if(!opts.show_panels) { pointer.css('borderColor',transColor); };
		
				switch(opts.filmstrip_position) {
					case 'bottom': pointer.css({
										'top':(opts.panel_height-(pointer_height*2)-f_frame_height + 0)+'px',
				 						'left':gallery_width-wrapper_width-f_caption_thickness+0-27-opts.frame_gap+((f_frame_width/2)-(pointer_width/2))+'px',
										'borderTopColor':transColor,
										'borderRightColor':transColor,
										'borderLeftColor':transColor
									}); break;
				}
		
			j_pointer = $('.pointer',j_gallery);
			
			//Add navigation buttons
			var navNext = $('<img />');
			navNext.addClass('nav-next').attr('src',theme_path+'/next.png').appendTo(j_gallery).css({
				'position':'absolute',
				'cursor':'pointer',
				'z-index':5002
			}).click(showNextItem);
			var navPrev = $('<img />');
			navPrev.addClass('nav-prev').attr('src',theme_path+'/prev.png').appendTo(j_gallery).css({
				'position':'absolute',
				'cursor':'pointer',
				'z-index':5002
			}).click(showPrevItem);
			if(filmstrip_orientation=='horizontal') {
				navNext.css({					 
					'top':(opts.filmstrip_position=='top'?0+strip_height/2-1:opts.panel_height-strip_height/2+0)-(f_frame_height/2)+'px'
				});
				navPrev.css({
					'top':(opts.filmstrip_position=='top'?0+strip_height/2-1:opts.panel_height-strip_height/2+0)-(f_frame_height/2)+'px'
				 });
				 
				if(opts.overlay_position == 'right')
				{
					navNext.css({
						'right':f_caption_thickness+0+opts.frame_margin+'px'
					});
					navPrev.css({
						'right':f_caption_thickness+0+wrapper_width+27+opts.frame_gap*2+opts.frame_margin+'px'
					 });
				 } 
				 else if(opts.overlay_position == 'left')
				 {
					navNext.css({
						'left':(f_caption_thickness+0+wrapper_width+27+opts.frame_gap*2+opts.frame_margin)+'px'
					});
					navPrev.css({
						'left':(f_caption_thickness+0+opts.frame_gap*2-opts.frame_margin)+'px'
					 });
				 }
			} else {
				navNext.css({					 
					'left':(opts.filmstrip_position=='left'?Math.max(0,filmstrip_margin):opts.panel_width+filmstrip_margin+0-strip_width)+((f_frame_width-22)/2)+13+'px',
					'top':wrapper_height+(Math.max(0,opts.frame_gap)*2)+'px'
				});
				navPrev.css({
					'left':(opts.filmstrip_position=='left'?Math.max(0,filmstrip_margin):opts.panel_width+filmstrip_margin+0-strip_width)+((f_frame_width-22)/2)-13+'px',
					'top':wrapper_height+(Math.max(0,opts.frame_gap)*2)+'px'
				});
			}
			
			// Default hide for the first time
			if(opts.show_filmstrip && opts.auto_hide_filmstrip) {
				filmstrip_hide = true;
				$('.strip_background',j_gallery).animate({top:(opts.filmstrip_position=='top'?(-strip_height)+'px':opts.panel_height+'px')});
				$('.strip_wrapper',j_gallery).animate({top:(opts.filmstrip_position=='top'?(-strip_height-2)+'px':opts.panel_height+'px')});
				$('.nav-next,.nav-prev', j_gallery).animate({top:(opts.filmstrip_position=='top'?(-strip_height):opts.panel_height+strip_height/2)-(f_frame_height/2)+'px'});
				$('.next_title', j_gallery).animate({top:(opts.filmstrip_position=='top'?Math.max(0,filmstrip_margin)+strip_height/2:opts.panel_height-strip_height/2)-(f_frame_height/2)+'px'});
			}
			
			j_filmstrip.removeClass("hide");
		};
		
	//Check mouse to see if it is within the borders of the panel
	//More reliable than 'mouseover' event when elements overlay the panel
		function mouseIsOverGallery(x,y) {
				var pos = getPos(j_gallery[0]);
				var top = pos.top+gallery_padding;
				var left = pos.left+gallery_padding;
				return x > left && x < left+gallery_width+(filmstrip_orientation=='horizontal'?(0*2):0+Math.max(0,filmstrip_margin)) && y > top && y < top+gallery_height+(filmstrip_orientation=='vertical'?(0*2):0+Math.max(0,filmstrip_margin));
		};
		
		function getInt(i) {
			i = parseInt(i,10);
			if(isNaN(i)) { i = 0; }
			return i;	
		}
					
		function buildGallery() {
			if(!gallery_built)
			{
				gallery_built = true;
				
				var gallery_images = opts.show_filmstrip?$('img',j_frames):$('img',j_panels);
		
		/************************************************/
		/*	Apply CSS Styles							*/
		/************************************************/
				j_gallery.parent().css({
					'width':gallery_width+(gallery_padding*2)+'px',
					'height':gallery_height+(gallery_padding*2)+'px',
					'position':'relative'
				});
				
				j_gallery.css({
					'width':gallery_width+'px',
					'height':gallery_height+'px',
					'overflow':'hidden',
					'left':gallery_padding+'px',
					'top':gallery_padding+'px'
				});
				
		/************************************************/
		/*	Build filmstrip and/or panels				*/
		/************************************************/
				if(opts.show_filmstrip) {
					buildFilmstrip();
					enableFrameClicking();
				}
				if(opts.show_controller){
					// build controller
					var controller = $('<img />');
					controller.addClass('controller').addClass('playing').attr('src',theme_path+'pause.png').appendTo(j_gallery).css({
						'position':'absolute',
						'cursor':'pointer',
						'z-index':1001,
						'top':gallery_height/2 - 26,
						'opacity':0
					}).click(play);
					
					if(!opts.show_captions){
						controller.css({
						'left':gallery_width/2 - 26})
					}
					else{
						f_caption_thickness = opts.caption_thickness+extraWidth($('.panel-overlay',j_panels));
						pos = (gallery_width - f_caption_thickness)/2 + 0 - 26; // opts.overlay_position == 'right'
						if(opts.overlay_position == 'left')
						{
							pos = (gallery_width + f_caption_thickness)/2 + 0 - 26
						}				
						controller.css({
							'left': pos + 'px'
						})
					}
					
					controller.wrap("<div></div>");
					controller.parent().css({'zoom':1});
				}

				if(opts.show_panels) {
					buildPanels();
				}

		/************************************************/
		/*	Add events to various elements				*/
		/************************************************/
					$(j_gallery).mouseover(function(){$(this).addClass("mouseover");}).mouseout(function(){if(!mouseIsOverGallery(mouseX,mouseY)){ $(this).removeClass("mouseover");}});
					
					$(document).mousemove(function(e){
						mouseX = e.pageX;
						mouseY = e.pageY;
						if(j_gallery.hasClass("mouseover") && mouseIsOverGallery(e.pageX,e.pageY)) {
							if(opts.show_panels && opts.show_controller) {
								if(!controller_show) {
									controller_show = true;
									$('.controller',j_gallery).animate({opacity:0.5},500);
								}
							}
							if(opts.show_panels && opts.pause_on_hover) {
								if(!paused) {
									$(document).oneTime(500,"animation_pause",function(){
										$(document).stopTime("transition");
										paused=true;
									});
								}
							}
							if(opts.show_panels && !opts.show_filmstrip && !panel_nav_displayed) {
								$('.nav-next-overlay,.nav-prev-overlay,.nav-next,.nav-prev').fadeIn('fast');				panel_nav_displayed = true;
							}
							if(opts.show_filmstrip && opts.auto_hide_filmstrip) {
								if(filmstrip_hide) {									
									filmstrip_hide = false;
									$('.strip_background',j_gallery).animate({top:(opts.filmstrip_position=='top'?(0)+'px':gallery_height+0-strip_height+'px')});
									$('.strip_wrapper',j_gallery).animate({top:(opts.filmstrip_position=='top'?(0+2)+'px':gallery_height+0-strip_height+'px')});
									$('.nav-next,.nav-prev', j_gallery).animate({top:(opts.filmstrip_position=='top'?0+strip_height/2:gallery_height+0-strip_height/2)-f_frame_height/2+'px'});
									$('.next_title', j_gallery).animate({top:(opts.filmstrip_position=='top'?0+strip_height/2:gallery_height+0-strip_height/2-2)-f_frame_height/2+'px'});
								}
							}
						} else {
							if(opts.show_panels && opts.show_controller) {
								if(controller_show) {
									controller_show = false;
									$('.controller',j_gallery).animate({opacity:0},500);
								}
							}
							
							if(opts.show_panels && opts.pause_on_hover) {
								$(document).stopTime("animation_pause");
								if(paused) {
									$(document).everyTime(opts.transition_interval,"transition",function(){
										showNextItem();
									});
									paused = false;
								}
							}
							
							if(opts.show_panels && !opts.show_filmstrip && panel_nav_displayed) {
								$('.nav-next-overlay,.nav-prev-overlay,.nav-next,.nav-prev').fadeOut('fast');
								panel_nav_displayed = false;
							}
							
							if(opts.show_filmstrip && opts.auto_hide_filmstrip) {
								if(!filmstrip_hide) {
									filmstrip_hide = true;
									$('.strip_background,.strip_wrapper',j_gallery).animate({top:(opts.filmstrip_position=='top'?(-strip_height)+'px':opts.panel_height+0*2+'px')});
									$('.nav-next,.nav-prev', j_gallery).animate({top:(opts.filmstrip_position=='top'?(-strip_height/2):opts.panel_height+strip_height/2+2*0)-(f_frame_height/2)+'px'});
									$('.next_title', j_gallery).animate({top:(opts.filmstrip_position=='top'?(-strip_height/2):opts.panel_height+strip_height/2+2*0-2)-(f_frame_height/2)+'px'});
								}
							}
						}
					});
				
				//Hide loading box
				$('.loader',j_gallery).fadeOut('1000',function(){
					//Show the 'first' panel				
					iterator = item_count-1;
					showItem(0);
					//If we have more than one item, begin automated transitions
					if(item_count > 1 && opts.play_at_first) {
						$(document).everyTime(opts.transition_interval,"transition",function(){
							showNextItem();
						});
					} else {
						$('.controller',j_gallery).attr('src',theme_path+'play.png');
						$('.controller',j_gallery).removeClass('playing').addClass('paused');
						paused = true;
					}
				});
				
				$('.panels',j_gallery).css({
					'visibility':'visible'
				});
				j_filmstrip.css('visibility','visible');
			}
		}
		
		function showNextTitle(i){
			if(i > -1){
				window.clearTimeout(timeoutID);
				
				$('.next_title',j_gallery).html($('.overlay-title',j_panels.eq(i%item_count)).html());
				$('.next_title',j_gallery).addClass("next_title_over");
			}
			else {
				timeoutID = window.setTimeout(function(){
					$('.next_title',j_gallery).fadeOut(500).html("").fadeIn(100);
					$('.next_title',j_gallery).removeClass("next_title_over");
					},500);
			}
		}
		
/************************************************/
/*	Main Plugin Code							*/
/************************************************/
		return this.each(function() {
			//Hide <ul>
			$(this).css('visibility','hidden');
			
			//Wrap <ul> in <div> and transfer ID to container <div>
			//Assign filmstrip class to <ul>
			$(this).wrap("<div></div>");
			$(this).parent().wrap("<div class=\"gallery_out\"></div>");
			j_gallery = $(this).parent();
			j_gallery.attr('id',$(this).attr('id')).addClass('gallery').prepend("<div class='panels'></div>");
			j_gallery.parent().attr('id',$(this).attr('id')+'_out');
			gallery_padding = opts.gallery_padding;
			
			// Set temp height to prevent 'jumping' effect
			j_gallery.css({
				//'height':(opts.panel_height+gallery_padding*2)+'px'
				'height':(opts.panel_height)+'px'
				,'padding':'0px'
			});
			
			$('.panels',j_gallery).css({
				'position':'relative'
				,'visibility':'hidden'
			});
			
			$(this).removeAttr('id').addClass('filmstrip');
			
			$(document).stopTime("transition");
			$(document).stopTime("animation_pause");
			
			id = j_gallery.attr('id');
			
			//If there is no defined panel content, we will scale panel images
			scale_panel_images = $('.panel-content',j_gallery).length==0;
			
			//Define dimensions of pointer <div>
			pointer_height = opts.pointer_size;
			pointer_width = opts.pointer_size*2;
			
			//Determine filmstrip orientation (vertical or horizontal)
			//Do not show captions on vertical filmstrips
			filmstrip_orientation = (opts.filmstrip_position=='top'||opts.filmstrip_position=='bottom'?'horizontal':'vertical');
			if(filmstrip_orientation=='vertical') opts.show_captions = false;
			
			filmstrip_margin = (opts.show_panels?6:0);
					
			gallery_width = opts.panel_width;
			gallery_height = opts.panel_height;
			
			/********************************************************/
			/*	PLACE LOADING BOX OVER GALLERY UNTIL IMAGES LOAD	*/
			/********************************************************/
					galleryPos = getPos(j_gallery[0]);
					$('<div>').addClass('loader').css({
						'position':'absolute',
						'zIndex':'32666',
						'opacity':0.7,
						'top':'0px',
						'left':'0px',
						'width':gallery_width+(filmstrip_orientation=='horizontal'?(0*2):0+Math.max(0,filmstrip_margin))+'px',
						'height':gallery_height+(filmstrip_orientation=='vertical'?(0*2):0+Math.max(0,filmstrip_margin))+'px'
					}).appendTo(j_gallery);
			
			//Determine path between current page and plugin images
			//Scan script tags and look for path to GalleryView plugin
			$('script').each(function(i){
				var s = $(this);
				if(s.attr('src') && s.attr('src').match(/jquery\.galleryview/)){
					loader_path = s.attr('src').split('jquery.galleryview')[0];
					theme_path = loader_path+'../../themes/';	
				}
			});
			
			j_filmstrip = $('.filmstrip',j_gallery);
			j_frames = $('li',j_filmstrip);
			j_frames.addClass('frame');
			
			//If the user wants panels, generate them using the filmstrip images
			if(opts.show_panels) {
				for(i=0;i<j_frames.length;i++) {
					if(j_frames.eq(i).find('.panel-content').length>0) {
						j_frames.eq(i).find('.panel-content').remove().prependTo(j_gallery).addClass('panel');
					} else {
						p = $('<div>');
						p.addClass('panel');
						
						// Asynchronous loading images
						var img = new Image();
						
						if(opts.caption_outside){
							$(img).css({
								'width': (opts.panel_width - opts.caption_thickness - 38)+'px',
								'height':(opts.panel_height) + 'px'
							});
							
							if(opts.overlay_position == 'left')
							{
								$(img).css({
									'marginLeft':(opts.caption_thickness + 38)+'px'
								});
							}
						}
						var src = j_frames.eq(i).find('img').eq(0).attr('title');
						var link = j_frames.eq(i).find('img').eq(0).attr('alt');
						$(img).load(function () {
							first_image_loaded = true;
							
							buildGallery();
						}).attr('src',src).attr('alt',link).appendTo(p);
						p.hide();
						p.appendTo($('.panels',j_gallery));
						
						if(opts.show_captions)
						{
							j_frames.eq(i).find('.panel-overlay').remove().appendTo(p);
						} else 
						{
							j_frames.eq(i).find('.panel-overlay').hide().appendTo(p);
						}
					}
				}
			} else { 
				$('.panel-overlay',j_frames).remove(); 
				$('.panel-content',j_frames).remove();
			}
			
			//If the user doesn't want a filmstrip, delete it
			if(!opts.show_filmstrip) { j_filmstrip.remove(); }
			else {
				//Wrap the frame images (and links, if applicable) in container divs
				//These divs will handle cropping and zooming of the images
				j_frames.each(function(i){
					if($(this).find('a').length>0) {
						$(this).find('a').wrap('<div class="img_wrap"></div>');
					} else {
						$(this).find('img').wrap('<div class="img_wrap"></div>');	
					}
				});
				j_frame_img_wrappers = $('.img_wrap',j_frames);
			}
			
			j_panels = $('.panel',j_gallery);
			
			if(!opts.show_panels) {
				opts.panel_height = 0;
				opts.panel_width = 0;
			}
			
			//Determine final frame dimensions, accounting for user-added padding and border
			f_frame_width = opts.frame_width+extraWidth(j_frame_img_wrappers);
			f_frame_height = opts.frame_height+extraHeight(j_frame_img_wrappers);
			
			//Number of frames in filmstrip
			item_count = opts.show_panels?j_panels.length:j_frames.length;
			
			//Number of frames that can display within the gallery block
			//64 = width of block for navigation button * 2 + 20
			if(filmstrip_orientation=='horizontal') {
				strip_size = Math.min(item_count,opts.filmstrip_size);
				//==>calculate auto strip size:  													
//opts.show_panels?Math.floor((opts.panel_width-opts.caption_thickness-((opts.frame_gap+22)*2)-opts.frame_margin-extraWidth($('.panel-overlay',j_gallery)))/(f_frame_width+opts.frame_gap)):Math.min(item_count,opts.filmstrip_size); 
			} else {
				strip_size = opts.show_panels?Math.floor((opts.panel_height-(opts.frame_gap+22))/(f_frame_height+opts.frame_gap)):Math.min(item_count,opts.filmstrip_size);
			}
			
			/************************************************/
			/*	Determine transition method for filmstrip	*/
			/************************************************/
					//If more items than strip size, slide filmstrip
					//Otherwise, slide pointer
					if(strip_size >= item_count) {
						slide_method = 'pointer';
						strip_size = item_count;
					}
					else {
						slide_method = 'strip';
					}
					item_showing_max = strip_size - 1;
			
			/************************************************/
			/*	Determine dimensions of various elements	*/
			/************************************************/
					j_filmstrip.css('margin','0px');
					
					//Width of filmstrip
					if(filmstrip_orientation=='horizontal') {
						if(slide_method == 'pointer') {strip_width = (f_frame_width*item_count)+(opts.frame_gap*(item_count));}
						else {strip_width = (f_frame_width*item_count*3)+(opts.frame_gap*(item_count*3));}
					} else {
						strip_width = (f_frame_width+filmstrip_margin);
					}
					
					if(filmstrip_orientation=='horizontal') {
						strip_height = (f_frame_height+filmstrip_margin);	
					} else {
						if(slide_method == 'pointer') {strip_height = (f_frame_height*item_count+opts.frame_gap*(item_count));}
						else {strip_height = (f_frame_height*item_count*3)+(opts.frame_gap*(item_count*3));}
					}
					
					//Width of filmstrip wrapper (to hide overflow)
					wrapper_width = ((strip_size*f_frame_width)+((strip_size-1)*opts.frame_gap));
					wrapper_height = ((strip_size*f_frame_height)+((strip_size-1)*opts.frame_gap));
			/*
			if(!window_loaded) {
				$(window).load(function(){
					window_loaded = true;
					if(!gallery_built)
						buildGallery();
				});
			} else {
				if(!gallery_built)
					buildGallery();
			}*/
		});
	};
	/*
	$.fn.galleryView.defaults = {
		show_panels: true,
		show_filmstrip: true,
		
		panel_width: 600,
		panel_height: 400,
		frame_width: 27,
		frame_height: 27,
		caption_thickness: 100,
		
		start_frame: 1,
		filmstrip_size: 5,
		transition_speed: 800,
		transition_interval: 4000,
		
		overlay_opacity: 0.7,
		frame_opacity: 0.3,
		pointer_size: 8,
		frame_gap: 8,
		frame_margin: 8,
		gallery_padding:1,
		
		nav_theme: 'dark',
		easing: 'swing',
		
		filmstrip_position: 'bottom',
		overlay_position: 'bottom',
		
		panel_scale: '',
		frame_scale: '',
		
		show_captions: false,
		fade_panels: true,
		pause_on_hover: false,
		show_controller: true, //show play - pause button when mouse over?
		auto_hide_filmstrip: false,
		auto_hide_overlay: true,
		show_next_item_title: true,
		play_at_first: false,
		panel_gradient: true,
		caption_outside: true,
		image_transition_effect:'blur',
		text_transition_effect:'blur',
	};*/
})(jQuery);