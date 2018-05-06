(($, api) => {

	let temp = '';
	if (location.hostname.includes('localhost')) temp = '/wordpress';

	api('emcasino_css', (value) => value.bind((newval) => {

		// let two = 'http://'+location.host+'/wp-content/plugins/em-casinoliste/assets/css/emcasino-two.css';
		let css = document.querySelector('.emcasino-css');
		if (css) css.parentNode.removeChild(css);
		else return;

		let css_mob = document.querySelector('.emcasino-css-mobile');
		if (css_mob) css_mob.parentNode.removeChild(css_mob);
		else return


		let list = {
			'one': {
				'desktop': 'http://'+location.host+temp+'/wp-content/plugins/em-casinoliste/assets/css/emcasino.css?v=1.0.1',
				'mobile': 'http://'+location.host+temp+'/wp-content/plugins/em-casinoliste/assets/css/emcasino-mobile.css?v=1.0.1'
			},
			'two': {
				'desktop': 'http://'+location.host+temp+'/wp-content/plugins/em-casinoliste/assets/css/emcasino-two.css?v=1.0.1',
				'mobile': 'http://'+location.host+temp+'/wp-content/plugins/em-casinoliste/assets/css/emcasino-mobile.css?v=1.0.1'
				// 'mobile': 'http://'+location.host+temp+'/wp-content/plugins/em-casinoliste/assets/css/emcasino-mobile-two.css'
			},
			'three': {
				'desktop': 'http://'+location.host+temp+'/wp-content/plugins/em-casinoliste/assets/css/emcasino-three.css?v=1.0.1',
				'mobile': 'http://'+location.host+temp+'/wp-content/plugins/em-casinoliste/assets/css/emcasino-mobile.css?v=1.0.1'
				// 'mobile': 'http://'+location.host+temp+'/wp-content/plugins/em-casinoliste/assets/css/emcasino-mobile-two.css'
			}
		};

		let o = document.createElement("link");
		o.classList.add("emcasino-css");
		o.setAttribute("rel", "stylesheet");
		o.setAttribute("href", list[newval].desktop);
		o.setAttribute("media", "(min-width: 1025px)");
		document.head.appendChild(o);

		let m = document.createElement("link");
		m.classList.add("emcasino-css-mobile");
		m.setAttribute("rel", "stylesheet");
		m.setAttribute("href", list[newval].mobile);
		m.setAttribute("media", "(max-width: 1024px)");
		document.head.appendChild(m);

	}));


})(jQuery, wp.customize);