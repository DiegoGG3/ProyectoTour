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
            "Editar": function() {
                if (!validarInputs()) {
                    return; 
                }else{
                    submit(event, false);
                }
            }
        },
        width: "90%",
        height: "auto",
        position: { my: "top", at: "top", of: window }
    });


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
        var inputsValidos = true; 


        inputsArray.forEach(function(input) {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red';
                inputsValidos = false; 
            } else {
                input.style.borderColor = ''; 
                inputsValidos = true; 

            }
        });

        var checkboxesSeleccionados = $('input[name="dia"]:checked').length;
        
        if (checkboxesSeleccionados === 0) {
            $("#diasError").show(); 
            inputsValidos = false;
        }else{
            $("#diasError").hide(); 
            inputsValidos = true;
        }

        var hora = $('#hora').val();
        
        if (hora === '') {
            $('#hora').css('border-color', 'red'); 
            inputsValidos = false;
        }else{
            $('#hora').css('border-color', ''); 
            inputsValidos = true;
        }

        if (inputsValidos==true) {
            agregarFila();
            $("#tablaError").hide(); 
        }
        
    });

    function agregarFila() {
        var rangoFecha = $("#fechaInicioPr").val() + " - " + $("#fechaFinPr").val();
        var dias = obtenerDiasSeleccionados();
        var hora = $("#hora").val();
        var persona = $("#personas").val();
    
        var fila = "<tr><td>" + rangoFecha + "</td><td>" + dias + "</td><td>" + hora + "</td><td>" + persona + "</td><td><input type='button' class='eliminarFila' value='Eliminar'></td></tr>";
    
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
        var ids = [];
        $("#visitasRuta li").each(function() { 
            var id = $(this).attr("id"); 
            if (id) { 
                ids.push(id); 
            }
        });
        return ids; 

    }

    function submit(event, tour) {

        event.preventDefault();

        var table = document.getElementById('horarios');

        var datosTabla = [];

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
        formData.append('creaTour', tour);
        formData.append('idGuia', $('#personas').val());


        var rutaId = $('#rutaId').val();

        $.ajax({
            type: 'POST',
            url: '/apiRuta/editar/' + rutaId,
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
    document.getElementById('filtroLoc').addEventListener('change', function() {
        var localidadId = this.value;
        
        // Realizar una solicitud AJAX para obtener los lugares disponibles para la localidad seleccionada
        $.ajax({
            type: 'POST',
            url: '/obtener_lugares_disponibles',
            data: { localidadId: localidadId },
            success: function(response) {
                // Limpiar la lista de lugares disponibles antes de agregar los nuevos elementos
                $('#visitasDisponibles').empty();
                $("#visitasRuta").empty();

                // Recorrer los datos recibidos y agregar cada lugar disponible como un nuevo elemento de lista
                response.forEach(function(lugar) {
                    var listItem = '<li id="' + lugar.id + '" class="tarjeta" style="border: 1px solid green;">' +
                                       '<img src="/fotos_visita/' + lugar.foto + '" width="80px" height="80px" alt="' + lugar.nombre + '">' +
                                       '<h2>' + lugar.nombre + '</h2>' +
                                   '</li>';
                    $('#visitasDisponibles').append(listItem);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener lugares disponibles:', xhr.responseText);
            }
        });
    });

    function validarInputs() {
        var inputs = document.querySelectorAll('#tabs-1 input[type="text"], textarea');
        var inputsArray = Array.from(inputs);
        var spinnerValue = $("#spinner").spinner("value"); 
        var inputsValidos = true; 
        inputsArray.forEach(function(input) {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red';
                inputsValidos = false; 
            } else {
                input.style.borderColor = ''; 
                inputsValidos = true; 

            }
        });

        
        if (spinnerValue === null || spinnerValue === undefined || spinnerValue === '') {
            $(".ui-spinner").css("border-color", "red");
            inputsValidos = false; 
        } else {
            $(".ui-spinner").css("border-color", "");
            inputsValidos = true; 
        }


        var descripcion = $("#descripcion").html().trim(); 
        if (descripcion == '') {
            $("#descripcionError").show(); 
            inputsValidos = false; 
            $("#descripcionError").hide(); 
            inputsValidos = true; 
        }

        if ($("#visitasRuta").find("li").length === 0) {
            $("#tarjetasError").show(); 
            inputsValidos = false;
        } else {
            $("#tarjetasError").hide(); 
            inputsValidos = true;
        }

        if ($("#horarios tbody tr").length === 0) {
            $("#tablaError").show(); 
            inputsValidos = false;
        } else {
            $("#tablaError").hide(); 
            inputsValidos = true; 
        }

        var cantidadImagenes = $(".uploaded-image").length;

        
        if (cantidadImagenes == 1) {
            $("#fotoError").hide(); 
            inputsValidos = true;
        } else {
            $("#fotoError").show(); 
            inputsValidos = false;
        }
    

        return inputsValidos; 
    }

});
