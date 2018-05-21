(($, api) => {
	document.addEventListener('DOMContentLoaded', function () {

	// storing for hover effect
	let playhere_background = api('emcasino_color[playhere]').get();
	let readmore_background = api('emcasino_color[readmore]').get();


	// bonus text color
	api('emcasino_color[bonus]', (value) => value.bind((newval) => $('.emcasino-bonus').css('color', newval)));
	

	// freespins text color
	api('emcasino_color[freespins]', (value) => value.bind((newval) => $('.emcasino-freespins').css('color', newval)));


	// nr background color
	api('emcasino_color[nr]', (value) => value.bind((newval) => $('.emcasino-nr').css('background-color', newval)));
	
	// nr text color
	api('emcasino_color[nr_text]', (value) => value.bind((newval) => $('.emcasino-nr').css('color', newval)));




	// read more text
	api('emcasino_text[readmore]', (value) => value.bind((newval) => $('.emcasino-link-readmore').text(newval)));
	
	// read more background
	api('emcasino_color[readmore]', (value) => value.bind((newval) => { 
		$('.emcasino-link-readmore').css('background-color', newval) 
		readmore_background = newval;
	}));

	// read more background hover
	api('emcasino_color[readmore_hover]', (value) => value.bind((newval) => {
		$('.emcasino-link-readmore').hover(
			function() { $(this).css('background-color', newval) },
			function() { $(this).css('background-color', readmore_background) }
		);
	}));
	
	// read more text color
	api('emcasino_color[readmore_text]', (value) => value.bind((newval) => $('.emcasino-link-readmore').css('color', newval)));
	
	// read more border color
	api('emcasino_color[readmore_border]', (value) => value.bind((newval) => $('.emcasino-link-readmore').css('border', 'solid 1px '+newval)));



	// play here text
	api('emcasino_text[playhere]', (value) => value.bind((newval) => $('.emcasino-link-playhere').text(newval)));
	
	// play here background
	api('emcasino_color[playhere]', (value) => value.bind((newval) => {
		$('.emcasino-link-playhere').css('background-color', newval) 
		playhere_background = newval;
	}));
	
	// play here background hover
	api('emcasino_color[playhere_hover]', (value) => value.bind((newval) => {
		$('.emcasino-link-playhere').hover(
			function() { $(this).css('background-color', newval) },
			function() { $(this).css('background-color', playhere_background) }
		);
	}));
	
	// play here background text color
	api('emcasino_color[playhere_text]', (value) => value.bind((newval) => $('.emcasino-link-playhere').css('color', newval)));
	
	// play here background border color
	api('emcasino_color[playhere_border]', (value) => value.bind((newval) => $('.emcasino-link-playhere').css('border', 'solid 1px '+newval)));



	// container background color
	api('emcasino_color[background]', (value) => value.bind((newval) => $('.emcasino-container').css('background-color', newval)));
	

	// container border color
	api('emcasino_color[border]', (value) => value.bind((newval) => {
		$('.emcasino-container').css('border-top', 'solid 2px '+newval); 
		$('.emcasino-container').css('border-bottom', 'solid 2px '+newval); 
	}));


	});	// end of on load event
})(jQuery, wp.customize);