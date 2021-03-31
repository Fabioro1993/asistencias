@section('title','Nomina')
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
<div>
    <div class="col s8 m8 l8 offset-s2 offset-m2 offset-l2">
      <div id="button-trigger2" class="card card card-default scrollspy" wire:ignore>
        <div class="card-content">
          <div class="row">
              <div class="col s12">
              <table id="data-table-row-grouping" class="display" >
                <thead>
                    <tr>
                        <th class="center" data-field="name">Mes</th>
                        <th class="center" data-field="total">Año</th>
                        <th class="center" data-field="status">Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $registro)
                    <tr>
                        <td class="center">
                            {{$registro->text_mes}} - {{$registro->quincena}}
                        </td>
                        <td class="center">{{$registro->anio}}</td>
                        <td class="center">
                            <a href="nomina/resumen/{{$registro->quincena}}/{{$registro->mes}}/{{$registro->anio}}"
                            class="waves-effect waves-light gradient-45deg-light-blue-cyan btn-small mb-1 mr-1"
                            >Detalle</a>
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