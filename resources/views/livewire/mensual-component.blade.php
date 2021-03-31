@section('title','Historico')
{{-- vendor styles --}}
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css"
    href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/select.dataTables.min.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/data-tables.css')}}">
@endsection
<div class="row">
    <div class="col s12 m12 l12">
        <div id="responsive-table" class="card card card-default scrollspy">
            <div class="card-content">
                <h4 class="card-title">{{$mes_text}} {{$anio}}</h4>
                {{-- <p class="mb-2">Add to the table tag to make the table horizontally scrollable on smaller screen widths. </p> --}}
                <div class="row">
                    <div class="col s12">
                    </div>
                    <div class="col s12">
                        <table id="data-table-row-grouping" class="display responsive-table">
                            <thead>
                                <tr>
                                    <th data-field="name">Fecha</th>
                                    <th data-field="price">Quincena</th>
                                    <th data-field="price">Responsable</th>
                                    <th data-field="total">Comentario</th>
                                    <th data-field="status">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $registro)
                                <tr>
                                    <td>
                                        <?PHP echo date('d-m-Y',strtotime($registro->fecha));?>
                                    </td>
                                    <td>Quincena - {{$registro->quincena}}</td>
                                    <td>{{$registro->responsable->name}}</td>
                                    <td>{{$registro->observacion}}</td>
                                    <td>
                                        @if ($registro->id_estado == 3)
                                        <a href="{{ url('/historico/edit/'.$registro->id_registro.'')  }}"
                                            class="btn-floating waves-effect waves-light cyan lightrn-1">
                                            <i class="material-icons">mode_edit</i>
                                        </a>
                                        @else
                                        <a href="{{ url('/historico/show/'.$registro->id_registro.'')  }}"
                                            class="btn-floating waves-effect waves-light gradient-45deg-purple-deep-purple"><i
                                                class="material-icons">remove_red_eye</i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.sidebar.fab-menu')
</div>

@push('script')
    {{-- vendor scripts --}}
    @section('vendor-script')
        <script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('vendors/data-tables/js/dataTables.select.min.js')}}"></script>
    @endsection

    {{-- page script --}}
    @section('page-script')
        <script src="{{asset('js/scripts/data-tables.js')}}"></script>
    @endsection
@endpush