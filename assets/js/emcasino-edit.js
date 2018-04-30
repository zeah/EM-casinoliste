(() => {
	let metas = [
			// 'logo',
			'info_1', 
			'info_2', 
			'info_3', 
			'bonus_tekst', 
			'bonus_krav', 
			'bonus_prosent',
			'bonus_opptil',
			'bonus_total',
			'bonus_total_prosent', 
			'freespins', 
			'les_omtale', 
			'spill_na_link'
		];

	let container = document.querySelector('.emcasino-container');

	let newdiv = (...c) => {
		let div = document.createElement('div');

		for (let cl of c)
			div.classList.add(cl);

		return div;
	}

	let newtitle = (title, ...c) => {
		let div;
		if (c.length > 0) 	div = newdiv(c);
		else 				div = newdiv();

		div.appendChild(document.createTextNode(title.replace(/_/g, ' ')));

		return div;
	}

	let newinput = (name, ...c) => {
		if (!name) return;

		let input = document.createElement('input');
		input.classList.add('emcasino-input');
		input.setAttribute('type', 'text');
		input.setAttribute('name', 'emcasino['+name+']');
		if (emcasino.meta[name]) input.setAttribute('value', emcasino.meta[name]);

		return input;
	}

	let newmeta = (name) => {
		let div = newdiv('emcasino-input-container');

		let title = newtitle(name, 'emcasino-title');

		let counter = newdiv('emcasino-counter');

		let input = newinput(name);

		input.addEventListener('input', () => counter.innerHTML = 'Tegn teller: '+input.value.length );

		div.appendChild(title);
		div.appendChild(input);
		div.appendChild(counter);

		return div;
	}

	// sort input
	let sortmeta = () => {
		let div =  newdiv('emcasino-input-container');

		let title = newtitle('Sorting', 'emcasino-title');

		let input = document.createElement('input');
		input.classList.add('emcasino-input');
		input.setAttribute('type', 'number');
		input.setAttribute('step', '0.05');
		input.setAttribute('name', 'emcasino_sort');
		if (emcasino.sort) input.setAttribute('value', emcasino.sort);

		div.appendChild(title);
		div.appendChild(input);

		return div;
	}

	// sort input
	container.appendChild(sortmeta());

	let starmeta = () => {
		let div = document.createElement('div');
		div.classList.add('emcasino-input-container');

		let title = newtitle('Rating', 'emcasino-title');

		let input = document.createElement('select');
		input.classList.add('emcasino-select');
		input.setAttribute('name', 'emcasino[rating]');

		console.log('rating '+emcasino.meta['rating']);

		for (let i = 5; i > 0; i--) {
			let option = document.createElement('option');
			option.appendChild(document.createTextNode(i+' stars'));


			if (emcasino.meta['rating'] == i+' stars') option.setAttribute('selected', '');

			input.appendChild(option);
		}

		// if (emcasino.meta['rating']) input.setAttribute('value', emcasino.meta['rating']); 
		div.appendChild(title);
		div.appendChild(input);
		return div;
	}

	container.appendChild(starmeta());

	for (let meta of metas)
		container.appendChild(newmeta(meta));

	


})();