$(document).ready(function() {


    $('#valoracionForm').submit(function(event) {
        event.preventDefault();
        
        var url = window.location.href;

        var partesUrl = url.split('/');

        var numero = partesUrl[partesUrl.length - 1];

        var formData = new FormData(this);
        formData.append('reservaId', numero); // Agregar el ID de la reserva a los datos del formulario

        $.ajax({
            type: 'POST',
            url: '/apiValoracion/guardar',
            processData: false,
            contentType: false,
            data: formData,
            success: function(response) {
                console.log(response);
                alert('Valoración enviada exitosamente');
                // Aquí puedes redirigir a otra página o hacer cualquier otra acción después de enviar la valoración
            },
            error: function(xhr, status, error) {
                console.error('Error al enviar la valoración:', xhr.responseText);
                alert('Error al enviar la valoración. Por favor, inténtalo de nuevo más tarde.');
            }
        });
    });
});
