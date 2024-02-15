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
            "Crear Ruta": function() {
                if (!validarInputs()) {
                    return; // Detener la ejecución si la validación falla
                }else{
                    submit(event); // Llamar a la función submit para enviar el formulario si la validación pasa
                }
            },
            "Crear Ruta Y Tours": obtenerIdsVisitasRuta,
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
        var inputs = document.querySelectorAll('#tabs-3 input[type="text"]');
        var inputsArray = Array.from(inputs);
        var inputsValidos = true; // Variable para rastrear si todos los campos son válidos


        inputsArray.forEach(function(input) {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red';
                inputsValidos = false; // Marcar como no válido si hay algún campo vacío
            } else {
                input.style.borderColor = ''; 
                inputsValidos = true; // Marcar como no válido si hay algún campo vacío

            }
        });

        var checkboxesSeleccionados = $('input[name="dia"]:checked').length;
        
        if (checkboxesSeleccionados === 0) {
            $("#diasError").show(); 
            inputsValidos = false; // Marcar como no válido si hay algún campo vacío        }
        }else{
            $("#diasError").hide(); 
            inputsValidos = true; // Marcar como no válido si hay algún campo vacío        }
        }

        var hora = $('#hora').val();
        
        if (hora === '') {
            $('#hora').css('border-color', 'red'); // Establecer el borde rojo
            inputsValidos = false; // Marcar como no válido si hay algún campo vacío        }
        }else{
            $('#hora').css('border-color', ''); // Establecer el borde rojo
            inputsValidos = true; // Marcar como no válido si hay algún campo vacío        }
        }

        if (inputsValidos==true) {
            agregarFila();
            $("#tablaError").hide(); 
        }
        
    });

    function agregarFila() {
        var rangoFecha = $("#fechaInicioPr").val() + "-" + $("#fechaFinPr").val();
        var dias = obtenerDiasSeleccionados();
        var hora = $("#hora").val();
        var persona = $("#personas").val();
    
        // Agregamos el botón de eliminar a la fila
        var fila = "<tr><td>" + rangoFecha + "</td><td>" + dias + "</td><td>" + hora + "</td><td>" + persona + "</td><td><input type='button' class='eliminarFila' value='Eliminar'></td></tr>";
    
        // Agregamos la fila a la tabla
        $("#horarios tbody").append(fila);
    }
    
  
    function obtenerDiasSeleccionados() {
        var diasSeleccionados = [];
        $("#diasSemana input[type='checkbox']:checked").each(function() {
            diasSeleccionados.push($(this).val());
        });
        return diasSeleccionados.join(",");
    }

    $('#horarios').on('click', '.eliminarFila', function() {
        // Eliminar la fila actual
        $(this).closest('tr').remove();
    });
    
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

        var table = document.getElementById('horarios');

        var datosTabla = [];

        // Recorrer las filas de la tabla (empezando desde 1 para omitir la fila de encabezado)
        for (var i = 1; i < table.rows.length; i++) {
            var row = table.rows[i];
            var fila = {
                "rangoFecha": row.cells[0].innerText,
                "dias": row.cells[1].innerText,
                "hora": row.cells[2].innerText,
                "persona": row.cells[3].innerText
            };
            datosTabla.push(fila);
        }

        // Convertir el array de datos en formato JSON
        var jsonData = JSON.stringify(datosTabla);
        var formData = new FormData();
        formData.append('nombre', $('#nombre').val());
        formData.append('descripcion', $('#descripcion').val());
        formData.append('foto', $('input[type="file"][id^="images-"]')[0].files[0]);
        formData.append('puntoInicio', $('#x').val() + $('#y').val());
        formData.append('tamanoMaximo', $('#spinner').val());
        formData.append('fechaInicio', $('#fechaInicio').val());
        formData.append('fechaFin', $('#fechaFin').val());
        formData.append('programacion', jsonData);
        formData.append('visitasId', obtenerIdsVisitasRuta());

        $.ajax({
            type: 'POST',
            url: '/apiRuta/insert',
            processData: false,
            contentType: false,
            data: formData,
            success: function(response) {
                console.log(response);  
            },
            error: function(xhr, status, error) {
                console.error('Error al crear la ruta:', xhr.responseText);
            }
        });

    }

    function validarInputs() {
        var inputs = document.querySelectorAll('#tabs-1 input[type="text"], textarea');
        var inputsArray = Array.from(inputs);
        var spinnerValue = $("#spinner").spinner("value"); // Obtener el valor del spinner
        var inputsValidos = true; // Variable para rastrear si todos los campos son válidos
        inputsArray.forEach(function(input) {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red';
                inputsValidos = false; // Marcar como no válido si hay algún campo vacío
            } else {
                input.style.borderColor = ''; 
                inputsValidos = true; // Marcar como no válido si hay algún campo vacío

            }
        });

        // Validar el spinner
        if (spinnerValue === null || spinnerValue === undefined || spinnerValue === '') {
            $(".ui-spinner").css("border-color", "red");
            inputsValidos = false; // Marcar como no válido si el spinner está vacío
        } else {
            $(".ui-spinner").css("border-color", "");
            inputsValidos = true; // Marcar como no válido si hay algún campo vacío
        }


        var descripcion = $("#descripcion").html().trim(); // Obtener el contenido del Rich Text Editor y eliminar espacios en blanco al principio y al final
        if (descripcion == '') {
            $("#descripcionError").show(); 
            inputsValidos = false; // Marcar como no válido si hay algún campo vacío
        } else {
            $("#descripcionError").hide(); 
            inputsValidos = true; // Marcar como no válido si hay algún campo vacío
        }

        if ($("#visitasRuta").find("li").length === 0) {
            $("#tarjetasError").show(); 
            inputsValidos = false; // Marcar como no válido si hay algún campo vacío
        } else {
            $("#tarjetasError").hide(); 
            inputsValidos = true; // Marcar como no válido si hay algún campo vacío
        }

        if ($("#horarios tbody tr").length === 0) {
            $("#tablaError").show(); 
            inputsValidos = false; // Marcar como no válido si la tabla de horarios está vacía
        } else {
            $("#tablaError").hide(); 
            inputsValidos = true; // Marcar como válido si la tabla de horarios tiene al menos una fila
        }

        var cantidadImagenes = $(".uploaded-image").length;

        // Comprobar si hay exactamente una imagen cargada
        if (cantidadImagenes == 1) {
            $("#fotoError").hide(); 
            inputsValidos = true; // Marcar como no válido si hay algún campo vacío
        } else {
            $("#fotoError").show(); 
            inputsValidos = false; // Marcar como no válido si hay algún campo vacío
        }
    

        return inputsValidos; // Devolver si todos los campos son válidos
    }

});
