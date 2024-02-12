

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

    var mymap = L.map('map').setView([40.2668, -3.6636], 6); // Latitud y longitud iniciales, y el nivel de zoom

    // Añadir un proveedor de mapas (puedes usar otros proveedores como OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    mymap.on('click', function(e){
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

    });


    $("#crearRuta").tabs();
    $("#crearRuta").dialog({
        autoOpen: true,
        modal: true,
        draggable: false,
        buttons: {
            "Crear Ruta": crearRuta,
            "Crear Ruta Y Tours": CrearRutaYTours,

            "Cerrar": function () {
                $(this).dialog("close")
            }
        },
        width: "90%",
        heigth: "auto",
        position: { my: "top", at: "top", of: window }
    });

    function crearRuta(){
        alert("sisi");
    }
    function CrearRutaYTours(){
        alert("sisino");

    }

    $("#descripcion").richText({
        height: "auto",
        removeStyles: true,
    });

    $('.input-images').imageUploader();

    $( "ul#visitasDisponibles" ).sortable({
        connectWith: "ul"
      });
   
      $( "ul#visitasRuta" ).sortable({
        connectWith: "ul",
        dropOnEmpty: false
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
        var rangoFecha = $("#fechaInicioPr").val() + " - " + $("#fechaFinPr").val();
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
        return diasSeleccionados.join(", ");
    }
    
});

/*
services.ymal
App\EventSubscriber\RequestSubscriber:
        arguments:
           # $mailer: '@Symfony\Component\Mailer\MailerInterface'
        tags:
            - { name: 'kernel.event_subscriber' }

RequestSubscriber
<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RequestSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        // Accede al objeto Request para obtener información sobre la solicitud
        $request = $event->getRequest();
        $method = $request->getMethod();
        $uri = $request->getUri();

        // Envía un correo electrónico cada vez que se recibe una solicitud
        $this->sendEmail("Solicitud recibida: $method $uri");
    }

    private function sendEmail($message)
    {
        // Implementa la lógica para enviar el correo electrónico utilizando el servicio Mailer
        $email = (new Email())
            ->from('noreply@example.com')
            ->to('admin@example.com')
            ->subject('Nueva Solicitud')
            ->text($message);

        $this->mailer->send($email);
    }
}
*/