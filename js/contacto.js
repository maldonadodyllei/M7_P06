( function ( document, window, index )
{
	var inputs = document.querySelectorAll( '.inputfile' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.querySelector( 'span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});
	});
}( document, window, 0 ));

function titleClick() {
    window.location.href = '../html/homeGZ.html';
}

document.getElementById('cerrarSesionBtn').addEventListener('click', function() {

    localStorage.clear();
    
    fetch('../php/cerrar_sesion.php', {
        method: 'POST',
        credentials: 'include'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cerrar la sesión');
        }
        window.location.href = '../html/inicio_sesion.html';
    })
    .catch(error => {
        console.error(error.message);
    });
});