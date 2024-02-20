$(document).ready(function() {
    $('.reserva-form').submit(function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        var idTour = $(this).find('.idTour').val();
        var idUser = $(this).find('.idUser').val();
        var cantidadPersonas = $(this).find('.cantidad-personas').val();
        formData.set('gente', cantidadPersonas);
        formData.set('idUser', idUser);
        formData.set('idTour', idTour);


        $.ajax({
            type: 'POST',
            url: '/ApiReserva/insert',
            processData: false,
            contentType: false,
            data: formData,
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error('Error al crear la reserva:', xhr.responseText);
            }
        });
    });
});
