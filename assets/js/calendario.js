document.addEventListener('DOMContentLoaded', function() {
    function mostrarModal(arg) {
        var id = arg.event.id;
        
        $('<div>').dialog({
            title: "Ruta "+arg.event.title,
            modal: true,
            width: 400,
            height: 'auto',
            resizable: false,
            draggable: false,
            open: function() {
                var jsonGuias=[];
                var formData = new FormData();
                formData.append('idRuta', id);
                $.ajax({
                    url: '/rutaIdAPI',
                    type: 'GET',
                    data: { idRuta: id },
                    success: function(data) {
                        var ruta= JSON.parse(data);
                        console.log(ruta);
                        var HTML = '<div id="tour'+id+'">' +
                                            '<div>' +
                                                '<label for="nombreRuta">Nombre Ruta:</label>' +
                                                '<input type="text" id="nombreRuta" name="nombreRuta" value="'+ruta.nombre+'">' +
                                            '</div>' +
                                            '<div>' +
                                                '<label for="guiaRuta">Guia: </label>' +
                                                '<input type="text" id="guiaRuta" name="guiaRuta">' +
                                            '</div>' +
                                            '<div>' +
                                                '<button id="asignarGuia">Asignar Guia</button>'+
                                            '</div>' +
                                        '</div>';
                        $("div[id^='ui-id-']").html(HTML);
                        
                        $("#asignarGuia").click(function(){
                            console.log($("div[id^='ui-id-']").children()[0].id.replace("tour",""));
                            if($("#guiaRuta").val()!=="" && jsonGuias.includes($("#guiaRuta").val())){
                                console.log($("#guiaRuta").val());
                                updateGuia($("#guiaRuta").val(),id);
                            }else{
                                alert("El guia o no existe o esta vacio!!");
                            }
                        });


                        $.ajax({
                            url:'/selectGuias',
                            type: 'GET',
                            success: function(data) {
                                guias=JSON.parse(data);
                                for(var i=0;i<guias.length;i++){
                                    jsonGuias.push(guias[i].email);
                                }
                                $('#nombreRuta').prop('disabled', true);
                                $('#guiaRuta').autocomplete({
                                    source: jsonGuias,
                                    select: function(event, ui) {
                                        console.log(event);
                                        console.log(ui);
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                console.log('Error al obtener los datos de la API:', error);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('Error al obtener los datos de la API:', error);
                    }
                });
            } 
        });
    }

    var jsonEventos = [];
    $.ajax({
        url: '/eventosToursAPI',
        type: 'GET',
        success: function(data) {
            for (var i = 0; i < data.length; i++) {
                var tour = {
                    id: data[i].id,
                    title: data[i].nombre,
                    start: new Date(data[i].fechaHora),
                };
                jsonEventos.push(tour);
            }

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                events: jsonEventos,
                eventContent: function(arg) {
                    var buttonHtml = '<div class="fc-daygrid-event-dot"></div><div class="fc-event-time">' + arg.timeText + '</div><div class="fc-event-title">' + arg.event.title + '</div>';
                    return { html: buttonHtml, eventDisplay: 'block' }; 
                },
                eventClick: function(arg) {
                    mostrarModal(arg);
                }
            });
            calendar.render();
        },
        error: function(xhr, status, error) {
            console.log('Error al obtener los datos de la API:', error);
        }
        
    });    
});

function updateGuia(emailGuia,idTour){
    var formData = new FormData();
    formData.append('emailGuia', emailGuia); 
    formData.append('idTour', idTour); 

    $.ajax({
        url: '/editarGuiaAPI',
        type: 'POST', 
        data: formData,
        processData: false, 
        contentType: false, 
        success: function(data) {
            console.log(data);
        },
        error: function(xhr, status, error) {
            console.log('Error al obtener los datos de la API:', error);
        }
    });
}