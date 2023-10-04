/**
 * This file is added to the single post page for the custom post type.
 * Your use case may not require it.
 */
( function () {
	// Now your JavaScript is protected from the global runtime scope.
	const element = document.createElement( 'p' );
	element.style.textAlign = 'center';
	element.style.fontSize = '16px';
	element.innerHTML =
		'wordpress-plugin-name/js/single-new-post-type.js, line 10: You are on the single page template for the post type!';
	const firstElement =
		document.body.querySelector( 'p' ) || document.body.firstChild;
	if ( firstElement && firstElement.nextSibling ) {
		document.body.insertBefore( element, firstElement.nextSibling );
	} else {
		document.body.appendChild( element );
	}

	// eslint-disable-next-line no-console
	console.log(
		'wordpress-plugin-name/js/single-new-post-type.js, line 6: You are on the single page template for the post type!'
	);
} )();
