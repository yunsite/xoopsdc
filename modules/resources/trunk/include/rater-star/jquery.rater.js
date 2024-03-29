jQuery.fn.rater	= function(options) {
		
	// default settings
	var settings = {
		enabled	: true,
		url		: '',
		method	: 'post',
		data : '',
		dataType : 'json',
		min		: 1,
		max		: 3,
		step	: 1,
		value	: null,
		after_click	: null,
		before_ajax	: null,
		after_ajax	: null,
		image	: './include/rater-star/star.gif',
		width	: 25,
		height	: 25
	}; 
	
	// custom settings
	if(options) {  
		jQuery.extend(settings, options); 
	}
	
	// main container
	var content	= jQuery('<ul class="rater-star"></ul>');
	content.css('background-image' , 'url(' + settings.image + ')');
	content.css('height' , settings.height);
	content.css('width' , settings.width*((settings.max-settings.min)/settings.step+1));
	
	// current
	var item	= jQuery('<li class="rater-star-item-current"></li>');
	item.css('background-image' , 'url(' + settings.image + ')');
	item.css('height' , settings.height);
	item.css('width' , 0);
	item.css('z-index' , settings.max / settings.step + 1);
	if (settings.value) {
		item.css('width' , ((settings.value-settings.min)/settings.step+1)*settings.width);
	}
	
	content.append(item);
	
	// star
	if (settings.enabled) {	// selectable
		for (var value=settings.min ; value<=settings.max ; value+=settings.step) {
			item	= jQuery('<li class="rater-star-item"></li>');
			
			item.attr('title' , value);
			item.css('height' , settings.height);
			item.css('width' , settings.width*((value-settings.min)/settings.step+1));
			item.css('z-index' , (settings.max - value) / settings.step + 1);
			item.css('background-image' , 'url(' + settings.image + ')');
			
			content.append(item);
		}
	}
	
	if (settings.enabled) {
		content.mouseover(function(){
			jQuery(this).find('.rater-star-item-current').hide();
		}).mouseout(function(){
			jQuery(this).find('.rater-star-item-current').show();
		})
	}
	
	// event of mouse
	content.find('.rater-star-item').mouseover(function() {
		jQuery(this).attr('class' , 'rater-star-item-hover');
	}).mouseout(function() {
		jQuery(this).attr('class' , 'rater-star-item');
	}).click(function() {
		jQuery(this).prevAll('.rater-star-item-current').css('width' , jQuery(this).width());

		var star_count		= (settings.max - settings.min) / settings.step + 1;
		var current_number	= jQuery(this).width() / settings.width;
		var current_value	= settings.min + (current_number - 1) * settings.step;
		var data	= {
			value	: current_value,
			number	: current_number,
			count	: star_count,
			min		: settings.min,
			max		: settings.max,
			extra	: settings.data
		}

		// callback event
		if (typeof settings.after_click == 'function') {
			settings.after_click(data);
		}
		
		// ajax request
		if (settings.url) {
			/*
			jQuery.ajax({
				data		: data,
				type		: settings.method,
				url			: settings.url,
				dataType :settings.dataType,
				beforeSend	: function() {
					if (typeof settings.before_ajax == 'function') {
						settings.before_ajax(data);
					}
				},
				success		: function(ret) {
					if (typeof settings.after_ajax == 'function') {
						settings.after_ajax(ret);
					}
				}
			});
			*/
		jQuery.getJSON(settings.url,{rate:current_value,res_id:settings.data},
        function(data){
            if (typeof settings.after_ajax == 'function') {
						settings.after_ajax(data);
					}
          });
		}
	})
	
	jQuery(this).html(content);
	
}