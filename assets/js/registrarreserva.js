function guardarAsistentes() {
    var formData = new FormData();
    var idReservas = [];
    $('.genteAsistente').each(function(index, element) {
        var idReserva = $(element).data('reserva-id');
        idReservas.push(idReserva); 

        var valor = $(element).val();
        formData.append('asistente_' + idReserva, valor); 
    });
    formData.append('imagenRuta', $('#imagenRuta')[0].files[0]);
    formData.append('observaciones', $('#observaciones').val());
    formData.append('recaudacion', $('#recaudacion').val());
    formData.append('idTour', $('#idTour').val());


    var idsJson = JSON.stringify(idReservas);
    formData.append('idReservas', idsJson);

    $.ajax({
        type: 'POST',
        url: '/ApiReserva/edit',
        processData: false,
        contentType: false,
        data: formData,
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error('Error al editar la reserva:', xhr.responseText);
        }
    });
}
