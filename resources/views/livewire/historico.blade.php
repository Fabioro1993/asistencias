@section('title','Historico')
{{-- vendor styles --}}
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/noUiSlider/nouislider.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css"
    href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/select.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/sweetalert/sweetalert.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
  <link rel="stylesheet" type="text/css" href="{{asset('css/pages/data-tables.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/pages/form-select2.css')}}">
@endsection
  <div>
    <div class="col s12 m12 l12">
      <div class="card card card-default">
        <div class="card-content">
          <div class="row">
            <div class="col s12">
              <p>Resumen mensual de trabajador</p>
              <form action="" wire:submit.prevent="resumen" class="left-alert" >                    
                <div class="input-field col s2">
                    <label for="cedula" class="active">Cedula</label>
                    <input wire:model.defer="cedula" id="cedula" type="text"
                    data-error="@error('cedula').errorTxt1 @enderror"
                    class="validate @error('cedula') invalid @enderror">
                    @error('cedula')
                        <small class="errorTxt1">
                            <div class="error">{{ $message }}</div>
                        </small>
                    @enderror
                </div>
                <div class="input-field col s4">
                  <label for="nombre" class="active">Nombre</label>
                  <input wire:model.defer="nombre" id="nombre" type="text"
                  data-error="@error('nombre').errorTxt1 @enderror"
                  class="validate @error('nombre') invalid @enderror">
                  @error('nombre')
                      <small class="errorTxt1">
                          <div class="error">{{ $message }}</div>
                      </small>
                  @enderror
                </div>
                <div class="input-field col s2" >
                  <div wire:ignore>
                    <select wire:model.defer="mes_select">
                      <option selected>Mes</option>
                      @foreach ($meses as $number => $mes)
                      <option value="{{$number}}">{{$mes}}</option>
                      @endforeach
                    </select>
                  </div>
                  @error('mes_select')
                      <small class="errorTxt1">
                          <div class="error">{{ $message }}</div>
                      </small>
                  @enderror
                </div>
                <div class="input-field col s1">
                  <label for="anio" class="active">Año</label>
                  <input wire:model.defer="anio" id="anio" type="text"
                  data-error="@error('anio').errorTxt1 @enderror"
                  class="validate @error('anio') invalid @enderror">
                  @error('anio')
                      <small class="errorTxt1">
                          <div class="error">{{ $message }}</div>
                      </small>
                  @enderror
                </div>
                <div class="input-field col s2">
                  <p class="mb-1">
                    <label>
                      <input wire:model.defer="quincena" name="quincena" type="radio" checked value="I"/>
                      <span>Quincena I</span>
                    </label>
                  </p>
                  <p class="mb-1">
                    <label>
                      <input  wire:model.defer="quincena" name="quincena" type="radio"  value="II"/>
                      <span>Quincena II</span>
                    </label>
                  </p>
                  @error('quincena')
                      <small class="errorTxt1">
                          <div class="error">{{ $message }}</div>
                      </small>
                  @enderror
                </div>
                <div class="input-field col s1">                        
                    <button class="waves-effect btn-flat btn-small" type="submit">
                        <i class="material-icons left">search</i>
                    </button>
                </div>                    
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
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
                            {{$registro->text_mes}}
                        </td>
                        <td class="center">{{$registro->anio}}</td>
                        <td class="center">
                            <a class="waves-effect waves-light gradient-45deg-light-blue-cyan btn-small mb-1 mr-1"
                            wire:click="$emit('modal',{{$registro->mes}}, {{$registro->anio}})">Detalle</a>
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
      <script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
      <script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
      <script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
      <script src="{{asset('vendors/data-tables/js/dataTables.select.min.js')}}"></script>
      <script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>
    @endsection

    {{-- page script --}}
    @section('page-script')
      <script src="{{asset('js/scripts/data-tables.js')}}"></script>
      <script src="{{asset('js/scripts/form-select2.js')}}"></script>
    @endsection

    <script>
      document.addEventListener('livewire:load', function () {
        @this.on('modal', (mes, anio) => {
          @this.call('modal', mes, anio)
        })
      });
      window.addEventListener('contentChanged', event => {
        swal("Oh no!", "No existe ningun registro!", "error");
      }); 
    </script>
@endpush