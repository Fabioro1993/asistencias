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

<div id="app-calendar">
    <div class="row">
        <div class="col s12">
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
<script>
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
            {
                @if ($registro->empresa == 'oso')
                    color: '#009688',
                @endif
                title : '{{ $registro->empresa}}',
                start : '{{ $registro->fecha }}',
                end   :  '{{ $registro->fecha }}',
                id   :  '{{ $registro->id_registro }}',
            },
            @endforeach
        ],
        eventClick: function(info) {
            window.location.href = "/registro/show/"+ info.event.id;
        }
    });
    fcCalendar.render();
</script>
@endsection