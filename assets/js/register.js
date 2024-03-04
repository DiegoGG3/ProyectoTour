$(document).ready(function() {
    $('.signin-form').submit(function(event) {
        event.preventDefault();

        // Obtener los datos del formulario
        var formData = new FormData(this);

        // Enviar la solicitud AJAX
        $.ajax({
            type: 'POST',
            url: "{{ path('user-insert') }}", // La URL de tu API para insertar usuario, usando Twig para obtener la ruta
            processData: false,
            contentType: false,
            data: formData,
            success: function(response) {
                console.log(response);
                // Aquí puedes manejar la respuesta de la API
            },
            error: function(xhr, status, error) {
                console.error('Error al crear el usuario:', xhr.responseText);
                // Aquí puedes manejar errores en la solicitud AJAX
            }
        });
    });
});
