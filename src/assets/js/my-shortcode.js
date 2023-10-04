/**
 * Add JavaScript functionality to the Shortcode output.
 * Your use case may not require it.
 */
( function () {
	// Now your JavaScript is protected from the global runtime scope.
	let countValue = 0;

	const counter = document.createElement( 'span' );
	counter.classList.add( 'my-shortcode-counter' );

	const button = document.createElement( 'button' );
	button.innerHTML = "Click to increase the shortcode button's counter";
	button.addEventListener( 'click', function () {
		countValue += 1;
		counter.innerHTML = countValue;
	} );

	const container = document.getElementById( 'my-shortcode' );
	container.innerHTML += ' Your shortcode is loaded.';
	container.appendChild( button );
	container.appendChild( counter );
} )();
