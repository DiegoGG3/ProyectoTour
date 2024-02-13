$(function () {
    $("#map").hide();
    $("#mapaBoton").click(function () {
        $("#map").dialog({
            autoOpen: true,
            modal: true,
            draggable: false,
            buttons: {
                "Cerrar": function () {
                    $(this).dialog("close");
                }
            },
            width: "90vh",
            height: "90vh",
            position: { my: "top", at: "top", of: window }
        });
        $("#map").parent().css({ height: "60vh" });
        $("#map").css({ height: "100%" });
    });

    var mymap = L.map('map').setView([40.2668, -3.6636], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    mymap.on('click', function(e){
        var latlng = e.latlng;
        var lat = latlng.lat;
        var lng = latlng.lng;

        L.marker([lat, lng]).addTo(mymap)
            .bindPopup('Coordenadas: ' + lat + ', ' + lng)
            .openPopup();

        $("#x").val(lat);
        $("#y").val(lng);
    });

    $("#crearRuta").tabs();
    $("#crearRuta").dialog({
        autoOpen: true,
        modal: true,
        draggable: false,
        buttons: {
            "Crear Ruta": submit,
            "Crear Ruta Y Tours": CrearRutaYTours,
            "Cerrar": function () {
                $(this).dialog("close");
            }
        },
        width: "90%",
        height: "auto",
        position: { my: "top", at: "top", of: window }
    });

    function CrearRutaYTours(){
        alert("sisino");
    }

    $("#descripcion").richText({
        height: "auto",
        removeStyles: true,
    });

    $('.input-images').imageUploader();

    $("ul#visitasDisponibles").sortable({
        connectWith: "ul"
    });

    $("ul#visitasRuta").sortable({
        connectWith: "ul"
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
    
    $("#fechaInicio, #fechaFin, #fechaInicioPr, #fechaFinPr").datepicker();

    $("#fechaInicio, #fechaFin").on('change',function(){
        console.log($("#fechaInicio").datepicker("getDate"));
        $("#fechaInicioPr").datepicker("option", "minDate", $("#fechaInicio").datepicker("getDate"));
        $("#fechaInicioPr").datepicker("option", "maxDate", $("#fechaFin").datepicker("getDate"));
        $("#fechaFinPr").datepicker("option", "minDate", $("#fechaInicio").datepicker("getDate"));
        $("#fechaFinPr").datepicker("option", "maxDate", $("#fechaFin").datepicker("getDate"));
    });

    $("#fechaInicioPr").on('change',function(){
        $("#fechaFinPr").datepicker("option", "minDate", $("#fechaInicioPr").datepicker("getDate"));
    });

    $("#fechaFinPr").on('change',function(){
        $("#fechaInicioPr").datepicker("option", "maxDate", $("#fechaFinPr").datepicker("getDate"));
    });

    $("#spinner").spinner({
        min: 1
    });

    $("#botonAñadir").click(function(){
        agregarFila();
    });

    function agregarFila() {
        var rangoFecha = $("#fechaInicioPr").val() + "-" + $("#fechaFinPr").val();
        var dias = obtenerDiasSeleccionados();
        var hora = $("#hora").val();
        var persona = $("#personas").val();
  
        var fila = "<tr><td>" + rangoFecha + "</td><td>" + dias + "</td><td>" + hora + "</td><td>" + persona + "</td></tr>";
        $("#horarios tbody").append(fila);
    }
  
    function obtenerDiasSeleccionados() {
        var diasSeleccionados = [];
        $("#diasSemana input[type='checkbox']:checked").each(function() {
            diasSeleccionados.push($(this).val());
        });
        return diasSeleccionados.join(",");
    }
    
    var closeButton = $(".ui-dialog-titlebar-close");
    
    closeButton.on("click", function () {
        window.location.href = "/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CRutaCrudController";
    });

    function validarInputs() {
        var inputs = document.querySelectorAll('input[type="text"], textarea');
        var inputsArray = Array.from(inputs);
        inputsArray.forEach(function(input) {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red';
            } else {
                input.style.borderColor = ''; 
            }
        });
    }

    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); 
        validarInputs();
    });

    function obtenerIdsVisitasRuta() {
        var ids = []; // Array para almacenar las ID
        $("#visitasRuta li").each(function() { // Iterar sobre cada elemento <li> dentro de #visitasRuta
            var id = $(this).attr("id"); // Obtener la ID del elemento actual
            if (id) { // Si la ID existe
                ids.push(id); // Agregar la ID al array
            }
        });
        return ids; // Devolver el array de IDs
    }

    function submit(event) {
        event.preventDefault();

        var formData = {
            nombre: $('#nombre').val(),
            descripcion: $('#descripcion').val(),
            foto: ('foto', $('#foto')[0].files[0]), // Obtener el archivo de la entrada de archivo
            puntoInicio: $('#x').val()+$('#y').val(),
            tamanoMaximo: $('#spinner').val(),
            visita: obtenerIdsVisitasRuta(),


            
        };

        $.ajax({
            type: 'POST',
            url: '/apiRuta/insert',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                console.log('Ruta creada con éxito. ID: ' + response.id);
            },
            error: function(xhr, status, error) {
                console.error('Error al crear la ruta:', xhr.responseText);
            }
        });
    }
});
