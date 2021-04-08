@section('title','Calendar')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/fullcalendar/css/fullcalendar.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/fullcalendar/daygrid/daygrid.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/fullcalendar/timegrid/timegrid.min.css')}}">
@endsection

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-calendar.css')}}">
@endsection

<div> 
    <div class="row" id="app-calendar" wire:ignore>
        <div class="col s12" >
            <div class="card">
                <div class="card-content">
                    <h4 class="card-title">
                        Carga Mensual
                    </h4>
                    <div id="basic-calendar"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal1" class="modal">
        <div class="modal-content">
            <h4 id="modalTitle" class="center">Empresa</h4>
            <table>
                <thead>
                <tr>
                    <th data-field="id">Responsable</th>
                    <th data-field="name">Gerencias</th>
                    <th data-field="price">Ubicacion</th>
                    <th data-field="price">Detalle</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($registro_modal as $reg)
                        <tr>
                            <td>{{$reg->responsable->name}}</td>
                            <td>
                                @foreach($reg->registro_det as $det)
                                    {{$det->text_geren}} <br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($reg->registro_det as $det)
                                    {{$det->ubicacion}}<br>
                                @endforeach
                            </td>
                            <td>
                                <div class="invoice-action">
                                    <a href="historico/show/{{ $reg->id_registro }}"  class="invoice-action-edit">
                                        <i class="material-icons">remove_red_eye</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Aceptar</a>
        </div>
    </div>
</div>

{{--vendor scripts  --}}
@section('vendor-script')
    <script src="{{asset('vendors/fullcalendar/lib/moment.min.js')}}"></script>
    <script src="{{asset('vendors/fullcalendar/js/fullcalendar.min.js')}}"></script>
    <script src="{{asset('vendors/fullcalendar/daygrid/daygrid.min.js')}}"></script>
    <script src="{{asset('vendors/fullcalendar/timegrid/timegrid.min.js')}}"></script>
    <script src="{{asset('vendors/fullcalendar/interaction/interaction.min.js')}}"></script>
    <script src="{{asset('vendors/fullcalendar/locales/es.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
<script>
    var titulo;
    var basicCal = document.getElementById('basic-calendar');
    var fcCalendar = new FullCalendar.Calendar(basicCal, {
        header: {
            left: 'prev,next today',
            center: 'title',
            right: "dayGridMonth,timeGridWeek"
        },
        locale: 'es',
        color: 'yellow',
        defaultDate: moment().format("YYYY-MM-DD"),
        editable: false, //false
        plugins: ["dayGrid", "interaction", "timeGrid"],
        eventLimit: true, // allow "more" link when too many events
        events : [
            @foreach($registros as $registro)
                @foreach($registro->registro_det as $det)
                {
                    @if ($det->empresa == 'oso')
                        color: '#009688',
                    @endif
                    title : '{{ $det->empresa}}',
                    start : '{{ $registro->fecha }}',
                    end   :  '{{ $registro->fecha }}',
                    id    :  '{{ $registro->id_registro }}',
                },
                @endforeach
            @endforeach
        ],
        eventClick:  function(info) {
            titulo = info.event.title
            @this.call('modal', info.event.title, info.event.start)
        }
    });
    fcCalendar.render();
    window.addEventListener('contentChanged', event => {
        var may = titulo.charAt(0).toUpperCase() + titulo.slice(1);
        $('#modalTitle').html(may);
        $('#modal1').modal('open');
    });
</script>
@endsection