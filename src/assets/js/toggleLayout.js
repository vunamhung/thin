import Cookie from 'js-cookie';

let buttons = document.querySelectorAll('.grid-list-toggle a');
let layout = Cookie.get('shop_layout');
if (layout) {
	document.querySelector('#primary ul.products').setAttribute('data-layout', layout);
}
buttons.forEach((btn) => {
	btn.addEventListener('click', (e) => {
		buttons.forEach((b) => b.classList.remove('active'));
		e.preventDefault();
		btn.classList.add('active');
		document.querySelector('#primary ul.products').setAttribute('data-layout', btn.getAttribute('data-class'));
		Cookie.set('shop_layout', btn.getAttribute('data-class'));
	});
});
