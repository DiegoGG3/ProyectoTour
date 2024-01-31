

$(function () {
    $("#map").hide();
    $("#mapaBoton").click(function () {
        $("#map").dialog({
            autoOpen: true,
            modal: true,
            draggable: false,
            buttons: {
                "Cerrar": function () {
                    $(this).dialog("close")
                }
            },
            width: "90vh",
            heigth: "90vh",
            position: { my: "top", at: "top", of: window }
        });
        $("#map").parent().css({ height: "60vh" });
        $("#map").css({ height: "100%" });
    });


    $("#crearRuta").tabs();
    $("#crearRuta").dialog({
        autoOpen: true,
        modal: true,
        draggable: false,
        buttons: {
            "Cerrar": function () {
                $(this).dialog("close")
            }
        },
        width: "90%",
        heigth: "auto",
        position: { my: "top", at: "top", of: window }
    });


    $("#descripcion").richText({
        height: "auto",
        removeStyles: true,
    });

    $('.input-images').imageUploader();

    var mymap = L.map('map').setView([0, 0], 2); // Latitud y longitud iniciales, y el nivel de zoom

    // Añadir un proveedor de mapas (puedes usar otros proveedores como OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    // Manejar clics en el mapa para obtener coordenadas
    mymap.on('click', function (e) {
        var latlng = e.latlng; // Obtiene las coordenadas del clic
        var lat = latlng.lat;
        var lng = latlng.lng;

        // Marcar un punto en el mapa
        L.marker([lat, lng]).addTo(mymap)
            .bindPopup('Coordenadas: ' + lat + ', ' + lng)
            .openPopup();

        // Mostrar coordenadas en la consola (puedes hacer lo que quieras con ellas)
        $("#x").val(lat);
        $("#y").val(lng);

    });

    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

    $.datepicker.setDefaults($.datepicker.regional['es']);

    

    $("#spinner").spinner({
        min: 0
    });


    $("#fechaInicio").datepicker({


        onSelect: function (selected) {
            $("#fechaFin").datepicker("option", "minDate", selected)
        }
    });

    $("#fechaFin").datepicker({

        numberOfMonths: 2,

        onSelect: function (selected) {
            $("#fechaInicio").datepicker("option", "maxDate", selected)

        }
    });

});