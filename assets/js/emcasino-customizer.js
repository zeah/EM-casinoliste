(($, api) => {

	api('emcasino_css', (value) => value.bind((newval) => {

		console.log('yea');
		let two = 'http://'+location.host+'/wordpress/wp-content/plugins/em-casinoliste/assets/css/emcasino-two.css';
		let css = document.querySelector('.emcasino-css');
		if (css) css.parentNode.removeChild(css);
		else return;

		// let css_mob = document.querySelector('.emcasino-css-mobile');
		// if (css_mob) css_mob.parentNode.removeChild(css_mob);
		// else return


		let list = {
			'one': {
				'desktop': '',
				'mobile': ''
			},
			'two': {


			}
		};

		let o = document.createElement("link");
		o.classList.add("emcasino-css");
		o.setAttribute("rel", "stylesheet");
		o.setAttribute("href", two);
		o.setAttribute("media", "(min-width: 1025px)");
		document.head.appendChild(o);

		// let m = document.createElement("link");
		// m.classList.add("emcasino-css-mobile");
		// m.setAttribute("rel", "stylesheet");
		// m.setAttribute("href", "'");
		// m.setAttribute("media", "(max-width: 1024px)");
		// document.head.appendChild(m);

	}));


})(jQuery, wp.customize);