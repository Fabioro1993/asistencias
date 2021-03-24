{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<style>
    td, th {
        padding: 2px 5px;
    }
</style>
@endsection

@section('title','Detalle')
<div>
    <!-- users view start -->
    <div class="section users-view">
        <!-- users view media object start -->
        <div class="card-panel">
            <div class="row">
                <div class="col s12 m7">
                    <div class="display-flex media">
                        <div class="media-body">
                            <h6 class="media-heading">
                                <span class="users-view-name">Fecha:
                                    <?PHP echo date('d-m-Y',strtotime($data->fecha));?></span>
                            </h6>
                            <span>Responsable:</span>
                            <span class="users-view-id">{{$data->responsable->name}}</span><br>
                            <span>Observacion:</span>
                            <span class="users-view-id">{{$data->observacion}}</span>
                        </div>
                    </div>
                </div>
                <div class="col s12 m5 quick-action-btns display-flex justify-content-end align-items-center pt-2">
                    @if ($data->id_estado == 3)
                    <a href="{{ url('/historico/edit/'.$data->id_registro.'')  }}" class="btn-small indigo">Edit</a>
                    @else
                    <span class=" users-view-status chip green lighten-5 green-text">{{$data->estado->estado}}</span>
                    @endif
                </div>                
            </div>
        </div>

        <!-- users view card details start -->
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <table class="striped responsive-table">
                            <thead>
                                <tr>
                                    <th>Cedula</th>
                                    <th>Nombre</th>
                                    @foreach($evaluaciones as $pregunta)
                                    <th>{{$pregunta->evaluacion}}</th>
                                    @endforeach
                                    <th>Comentario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->registro_det as $registro_dt)
                                <tr>
                                    <td class="users-view-username">{{$registro_dt->cedula}}</td>
                                    <td class="users-view-name">{{$registro_dt->nombre}}</td>
                                    @foreach($registro_dt->registro_sub as $sub)
                                    <th class="center">{{$sub->resultado}}</th>
                                    @endforeach
                                    <td class="users-view-email">{{$registro_dt->comentario}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- </div> -->
            </div>
        </div>
        <!-- users view card details ends -->
    </div>
    <!-- users view ends -->
    @include('livewire.registro.resumen') 
</div>

@push('script')
{{-- vendor script --}}
@section('vendor-script')
{{-- <script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script> --}}
@endsection

{{-- page script  --}}
@section('page-script')
{{-- <script src="{{asset('js/scripts/form-select2.js')}}"></script> --}}
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/customizer.js')}}"></script>
@endsection

<script>
    document.addEventListener('livewire:load', function () {
        $('input#input_text, textarea#textarea1').characterCounter();
        $('select').on('change', function (e) {
            @this.set('asistencia', e.target.value);
        });

        Livewire.hook('message.processed', (message, component) => {
            $('select').select2();
            $(".theme-cutomizer").sidenav({
                edge: "right"
            });

            var ps_theme_customiser = new PerfectScrollbar(".theme-cutomizer", {
                suppressScrollX: true
            });
        });
    })
</script>
@endpush