import Cookies from 'js-cookie';

function acceptCookie( expires ) {
	Cookies.set( 'vnh_cookie_notice_accepted', 'yes', { expires, path: '/' } );
}

acceptCookie();

const toggleButton = document.getElementById( 'search-toggle' );
const closeButton = document.getElementById( 'search-close' );

toggleButton.addEventListener( 'click', () => {
	document.body.classList.add( 'open-search' );
} );

closeButton.addEventListener( 'click', () => {
	document.body.classList.remove( 'open-search' );
} );
