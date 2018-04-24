(() => {
	let metas = ['signup'];

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

		div.appendChild(document.createTextNode(title));

		return div;
	}

	let newinput = (name, ...c) => {
		if (!name) return;

		let input = document.createElement('input');
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

		input.addEventListener('input', () => counter.innerHTML = input.value.length );

		div.appendChild(title);
		div.appendChild(input);
		div.appendChild(counter);

		return div;
	}

	let sortmeta = () => {
		let div =  newdiv();

		let title = newtitle('Sorting', 'emcasino-title');

		let input = document.createElement('input');
		input.setAttribute('type', 'number');
		input.setAttribute('step', '0.05');
		input.setAttribute('name', 'emcasino_sort');
		if (emcasino.sort) input.setAttribute('value', emcasino.sort);

		div.appendChild(title);
		div.appendChild(input);

		return div;
	}

	container.appendChild(sortmeta());

	for (let meta of metas)
		container.appendChild(newmeta(meta));

	


})();