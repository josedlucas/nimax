@extends('frontend.template.master')

@section('title', 'Propiedad: '.$propiedad_obj->nombre)

@section("style")
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
 #map {
   width: 100%;
   height: 400px;
   background-color: grey;
 }

 .owl-prev,
.owl-next {
  width: 55px;
  height: 55px;
  background-color: #f8f4ea;
  border-radius: 50px;
  outline: none;
  position: absolute;
  top: calc(50% - 27.5px);
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  -webkit-box-pack: center;
      -ms-flex-pack: center;
          justify-content: center;
  -webkit-transition: .2s transform;
  transition: .2s transform;
  overflow: hidden;
}

.owl-prev svg,
.owl-next svg {
  width: 10px;
  fill: #f8f4ea;
  -webkit-transition: .4s fill;
  transition: .4s fill;
  position: relative;
  z-index: 10;
}

.owl-prev::before,
.owl-next::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #5ce1aa;
  opacity: 0;
  -webkit-transform: scale(0);
          transform: scale(0);
  -webkit-transition: 0.4s opacity, 0.4s -webkit-transform;
  transition: 0.4s opacity, 0.4s -webkit-transform;
  transition: 0.4s opacity, 0.4s transform;
  transition: 0.4s opacity, 0.4s transform, 0.4s -webkit-transform;
}

.owl-prev:hover svg,
.owl-next:hover svg {
  fill: #f8f4ea;
}

.owl-prev:hover:before,
.owl-next:hover:before {
  -webkit-transform: scale(1.1);
          transform: scale(1.1);
  opacity: 1;
}

.owl-prev:active,
.owl-next:active {
  -webkit-transform: scale(0.95);
          transform: scale(0.95);
}

.owl-prev:active.owl-prev,
.owl-next:active.owl-prev {
  -webkit-transform: scale(0.95) rotate(180deg);
          transform: scale(0.95) rotate(180deg);
}

.owl-prev {
  left: 15px;
  -webkit-transform: rotate(180deg);
          transform: rotate(180deg);
}

.owl-next {
  right: 15px;
}

</style>
@endsection

@section('contenido')
<main class="page-single">
  <header class="page-header">
    <div class="owl-carousel page-header--carousel">
      @foreach($propiedad_obj->get_imagenes as $imagen)
      <img class="owl-lazy" data-src="{{ asset('/storage/imagenes/propiedades/'.$imagen->file_name) }}" alt="">
      @endforeach
    </div>
  </header>
  <section class="page-single--content">
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <article class="box-content">
            <div class="id">#{{$propiedad_obj->id}}</div>

            <h1 class="title wow fadeUp">{{$propiedad_obj->nombre}}</h1>
            <div class="ubicacion wow fadeUp" data-wow-delay=".1s"><b>{{$propiedad_obj->tipo_propiedad}}</b> en <b>{{$propiedad_obj->ubicacion_general}}</b></div>

            <div class="datos" style="text-align:center;">
              <div class="item wow fadeUp match-height" data-wow-delay=".2s">
                <figure>
                  <img src="{{ asset('/assets/img/icon-map-pin.svg') }}">
                </figure>
                <h5 style="word-break: break-word;">{{$propiedad_obj->ubicacion_general}}</h5>
              </div>
			  @if($propiedad_obj->get_texto_cantidad_huespedes() > 0)
              <div class="item wow fadeUp match-height" data-wow-delay=".2s">
                <figure>
                  <img src="{{ asset('/assets/img/icon-user-black.svg') }}">
                </figure>
                <h5 style="word-break: break-word;">{{$propiedad_obj->get_texto_cantidad_huespedes()}}</h5>
              </div>
              @endif
              <div class="item wow fadeUp match-height" data-wow-delay=".3s">
                <figure>
                  <img src="{{ asset('/assets/img/icon-bed-black.svg') }}">
                </figure>
                <h5 style="word-break: break-word;">{{$propiedad_obj->get_texto_cantidad_cuartos()}}</h5>
              </div>
              <div class="item wow fadeUp match-height" data-wow-delay=".4s">
                <figure>
                  <img src="{{ asset('/assets/img/icon-bath-black.svg') }}">
                </figure>
                <h5 style="word-break: break-word;">{{$propiedad_obj->get_texto_cantidad_banios()}}</h5>
              </div>
            </div>

            <div class="descripcion">
              <h5 class="box-title wow fadeUp">Descripción</h5>
              <div class="wow fadeUp" data-wow-delay=".1s">
              <?php echo nl2br(e($propiedad_obj->descripcion))?>
              </div>
            </div>

            @php
            $servicios_de_propiedad = $propiedad_obj->get_servicios();
            @endphp

            @if($servicios_de_propiedad)
            <div class="servicios">
              <h5 class="box-title wow fadeUp">Servicios</h5>
              <ul class="wow fadeUp" data-wow-delay=".1s">
                @foreach($servicios_de_propiedad as $servicio_propiedad_obj)
                <li>{{$servicio_propiedad_obj->get_servicio->servicio}}</li>
                @endforeach
              </ul>
            </div>
            @endif

            <div class="ubicacion">
              <h5 class="box-title wow fadeUp">Ubicación</h5>
              <div class="mapa wow fadeIn" data-wow-delay="1s">
                <div id="map"></div>
              </div>
          </article>
        </div>


        <div class="col-md-4">
          <div class="box-reservar">
            <div class="precio"> <p><b>
            @if($propiedad_obj->tipo_moneda == "pesos")
              $
            @else
             US$
            @endif
             {{number_format($propiedad_obj->valor,0,",",".")}}</b> 
             @if($propiedad_obj->temporario == 'alquiler')
            	por mes
             @else
            	por noche
             @endif
            </p></div>
            <div class="button">
              <div class="button-2 btn-mobile-reservar">CONSULTAR</div>
            </div>
          </div>

          <aside class="box-aside">
            <header class="box-aside--header">
              <p><b>
            @if($propiedad_obj->tipo_moneda == "pesos")
              $
            @else
             US$
            @endif {{number_format($propiedad_obj->valor,0,",",".")}}</b> 
            	@if($propiedad_obj->temporario == 'alquiler')
                por mes
             @else
                por noche
             @endif
            </p>
              <div class="btn-mobile-close"><img src="{{ asset('/assets/img/btn-close.svg') }}"></div>
            </header>
            <div class="box-aside--content">
              <div class="form">
                <form action="#" id="formulario_reserva">
                  <input type="text" class="custom-input" placeholder="Nombre y apellido" name="nombre">
                  <input type="text" class="custom-input" placeholder="Teléfono de contacto" name="telefono">
                  <input type="text" class="custom-input" placeholder="Correo" name="correo">
                  <div class="fechas">
                    <input type="text" class="custom-input" id="input_datepicker_llegada" placeholder="Llegada" name="llegada" readonly="true">
                    <input type="text" class="custom-input" id="input_datepicker_salida" placeholder="Salida" name="salida" readonly="true">
                  </div>
                  <input type="text" class="custom-input" placeholder="Cantidad de huéspedes" name="cantidad_huespedes">
                  <button class="button-2">CONSULTAR</button>
                  <div class="copy"></div>
                </form>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>
  </section>
</main>


@endsection


@section("js_code")
<script src="{{ asset('assets/js/single.js') }}"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('/assets/js/moment.js') }}"></script>

<script>
 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
 </script>

<script type="text/javascript">

<?php

$fecha = date('Y-m-d');
$nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-d' , $nuevafecha );

$nuevafecha = \DateTime::createFromFormat("Y-m-d", $nuevafecha);

?>

var fecha_comienzo = new Date({{$nuevafecha->format("Y")}},{{$nuevafecha->format("m")-1}},{{$nuevafecha->format("d")}},0,0,0,0);

$(document).ready(function(){


  var highest = 0, columns = $('.match-height');

  columns.each(function(){
     var currColumnHeight = $(this).outerHeight();
     if (currColumnHeight > highest) {
        highest = currColumnHeight;
     }
  });

  columns.css('height', highest);




  <?php
  $reserved = array();
  foreach ($fechas_reservadas as $reserva) {

    $desde = \DateTime::createFromFormat("Y-m-d H:i:s", $reserva->desde);
    $hasta = \DateTime::createFromFormat("Y-m-d H:i:s", $reserva->hasta);

    $period = new DatePeriod(
      $desde,
      new DateInterval('P1D'),
      $hasta
    );

    foreach ($period as $key => $value) {
      $reserved[] = $value->format('Y-m-d');
    }

    $reserved[] = $desde->format('Y-m-d');
    $reserved[] = $hasta->format('Y-m-d');
  }
  $reserved = array_unique($reserved);
  ?>




  var restrict_dates = [
    <?php foreach($reserved as $key => $value){
      echo '"'.$value.'", ';
    } ?>
  ]

  $("#input_datepicker_llegada").datepicker({
    timepicker: false,
    closeOnDateSelect: true,
    dateFormat: 'dd/mm/yy',
    minDate: fecha_comienzo,
    beforeShowDay: function(date){
        var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
        return [ restrict_dates.indexOf(string) == -1 ]
    }
  });

  $("#input_datepicker_llegada").change(function(e){
    
    var fecha_llegada_select = $.trim($("#input_datepicker_llegada").val());

    fecha_llegada_select = fecha_llegada_select.split("/");

    if(fecha_llegada_select.length == 3)
    {
      var fecha_select_llegada = $("#input_datepicker_llegada").datepicker('getDate');

      var dias = 1; // Número de días a agregar
      fecha_select_llegada.setDate(fecha_select_llegada.getDate() + dias);
    
      var anio = fecha_select_llegada.getFullYear();
      var mes = parseInt(fecha_select_llegada.getMonth() + 1);
      var numero_dia= parseInt(fecha_select_llegada.getDate());

      if(mes < 10)
      {
        mes = "0"+mes;
      }

      if(numero_dia < 10)
      {
        numero_dia = "0"+numero_dia;
      }

      //chequear si esta restringida
      if(restrict_dates.includes(anio+"-"+mes+"-"+numero_dia)){
        $('#input_datepicker_salida').val($("#input_datepicker_llegada").val());
      }else{
        $('#input_datepicker_salida').val(numero_dia+"/"+mes+"/"+anio);
      }



      $('#input_datepicker_salida').datepicker('destroy');

      $('#input_datepicker_salida').datepicker({
        timepicker: false,
        closeOnDateSelect: true,
        dateFormat: 'dd/mm/yy',
        minDate: fecha_select_llegada,
        beforeShowDay: function(date){
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            return [ restrict_dates.indexOf(string) == -1 ]
        }
      });
    }
  });

  $('#input_datepicker_salida').datepicker({
    timepicker: false,
    closeOnDateSelect: true,
    dateFormat: 'dd/mm/yy',
    minDate: fecha_comienzo,
    beforeShowDay: function(date){
        var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
        return [ restrict_dates.indexOf(string) == -1 ]
    }
  });
});

$("#formulario_reserva").submit(function(){

  var nombre = $("#formulario_reserva [name=nombre]").val();
  var telefono = $("#formulario_reserva [name=telefono]").val();
  var correo = $("#formulario_reserva [name=correo]").val();
  var llegada = $("#formulario_reserva [name=llegada]").val();
  var salida = $("#formulario_reserva [name=salida]").val();
  var cantidad_huespedes = $("#formulario_reserva [name=cantidad_huespedes]").val();

  $.ajax({
    url : "{{url('/reservar_propiedad')}}",
    type:"POST",
    data:{
      nombre:nombre,
      telefono:telefono,
      correo:correo,
      llegada:llegada,
      salida:salida,
      cantidad_huespedes:cantidad_huespedes,
      id_propiedad:{{$propiedad_obj->id}}
    },
    headers:
    {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    beforeSend: function(data){
      abrir_loading();
    },
    success: function(data)
    {
      cerrar_loading();

      try{
        if(data["response"] == true)
        {
          window.location.href = "{{url('/contacto_enviado')}}";
          // mostrar_mensajes_success("Reserva realizada!","Pronto nos pondremos en contacto con usted");

          // $("#formulario_reserva [name=nombre]").val("");
          // $("#formulario_reserva [name=telefono]").val("");
          // $("#formulario_reserva [name=correo]").val("");
          // $("#formulario_reserva [name=llegada]").val("");
          // $("#formulario_reserva [name=salida]").val("");
          // $("#formulario_reserva [name=cantidad_huespedes]").val("");
        }
        else {
          mostrar_mensajes_errores(data["messages_errors"]);
        }
      }
      catch(e)
      {
        mostrar_mensajes_errores();
      }
    },
    error: function(data)
    {
      cerrar_loading();
      mostrar_mensajes_errores();
    }
  });

  return false;

});


</script>

<script>
// Initialize and add the map
function initMap() {
  // The location of Uluru
  var uluru = {lat: {{$propiedad_obj->ubicacion_latitud}}, lng: {{$propiedad_obj->ubicacion_longitud}}};
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 16, center: uluru});
  // The marker, positioned at Uluru
  var marker = new google.maps.Marker({position: uluru, map: map});
  marker.setVisible(false);
    // Add circle overlay and bind to marker
    var circle = new google.maps.Circle({
      map: map,
      radius: 250,    // 10 miles in metres
      fillColor: '#6dddd3'
    });

    circle.bindTo('center', marker, 'position');
}
</script>

<script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{Config("app.key_google_map")}}&callback=initMap'></script>


@endsection
