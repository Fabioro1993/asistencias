{{-- vendor styles --}}
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/noUiSlider/nouislider.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/sweetalert/sweetalert.css')}}">
@endsection
{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/form-select2.css')}}">
<style>
    td, th {
        padding: 2px 5px;
    }
</style>
@section('title','Registro')

@endsection
<div>
    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card-panel">
                <div class="row">
                    <div id="input-fields">                        
                        <div class="input-field col s2">
                            <label class="active" for="fecha"></label>
                            <input id="fecha"
                            type="date"
                            class="validate materialize-textarea @error('evaluacion') invalid @enderror"
                            data-error="@error('evaluacion').errorTxt1 @enderror"
                            max="{{$fecha_max}}"
                            min="{{$fecha_min}}"
                            wire:model="fecha"
                            required>
                            @error('evaluacion')
                                <small class="errorTxt1">
                                    <div class="error">{{ $message }}</div>
                                </small>
                            @enderror
                        </div>  
                        <div class="input-field col s3">
                            <input id="nombre" type="text" class="validate" readonly value="{{ Auth::user()->name }}">
                            <label for="nombre" class="active">Responsable</label>
                        </div>  
                        <div class="input-field col s7">
                            <textarea id="textarea" class="materialize-textarea" data-length="120" wire:model.defer="observacion"></textarea>
                            <label for="textarea">Observacion</label>
                        </div>
                    </div>
                    <div class="col s12">
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th class="center" data-field="id">Empresa</th>
                                    <th class="center" data-field="id">Cedula </th>
                                    <th class="center" data-field="name">Nombre y Apellido</th>
                                    @foreach($evaluaciones as $pregunta)
                                    <th class="center" data-field="status">{{$pregunta->evaluacion}}</th>
                                    @endforeach
                                    <th class="center" data-field="status">Comentario</th>
                                    <th class="center" data-field="status" {{$hidden}} >Guardias Adicionales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($oso as $p_oso)
                                <tr>
                                    <td class="center" style="font-size: 11px;">
                                        <?PHP echo ucwords($p_oso->empresa);?> <br>
                                        {{$p_oso->descr}} <br>
                                        {{$p_oso->ubicacion}}
                                    </td>
                                    <td class="center">{{$p_oso->CEDULA}}</td>
                                    <td style="font-size: 11px;">{{$p_oso->NOMBRE }}</td>                                            
                                    <td>
                                        <select class="select2 browser-default" wire:model="asistencia" id="{{ $p_oso->CEDULA }}"
                                        wire:ignore>
                                            <option value="0_{{ $p_oso->CEDULA }}">0</option>
                                            <option value="1_{{ $p_oso->CEDULA }}">1</option>
                                            @foreach($select as $estado)
                                                <option value="{{ $estado->id_evaluacion }}_{{ $p_oso->CEDULA }}"> {{ $estado->abrv }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    @for ($i = 1; $i < count($evaluaciones); $i++)
                                        <td>
                                            <input type="number" min="1" max="{{$evaluaciones[$i]->max}}" class="evaluacion center"
                                            wire:model.defer="evaluacion.{{ $p_oso->CEDULA }}.{{$evaluaciones[$i]->id_evaluacion}}"
                                            wire:keyup="evaluacion({{$p_oso->CEDULA}}, '{{$evaluaciones[$i]->id_evaluacion}}')">
                                            @if ($error[$p_oso->CEDULA][$evaluaciones[$i]->id_evaluacion] == 1)
                                                <small class="errorTxt1">
                                                    <div class="error">El valor debe ser menor a 2</div>
                                                </small>
                                            @endif
                                        </td> 
                                    @endfor
                                    <td>
                                        <input id="comentario.{{ $p_oso->CEDULA }}" type="text" class="validate"
                                        wire:model.defer='comentario.{{ $p_oso->CEDULA }}'>
                                        @error('comentario.'.$p_oso->CEDULA)
                                            <small class="errorTxt1">
                                                <div class="error">{{ $message }}</div>
                                            </small>
                                        @enderror
                                    </td>
                                    <td {{$hidden}}>
                                        <input type="number" min="2" max="6" class="adicionales center"
                                        wire:model.defer="adicionales.{{ $p_oso->CEDULA }}"
                                        wire:keyup="adicionales({{$p_oso->CEDULA}})">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <button wire:click="$emit('store','a')" class="btn waves-effect waves-light cyan submit right" type="submit">Guardar
                                <i class="material-icons left">send</i>
                            </button>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
    @include('pages.partials.customizer') 
</div>

@push('script')
    {{-- vendor script --}}
    @section('vendor-script')
        <script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
        <script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>
    @endsection

    {{-- page script --}}
    @section('page-script')
        <script src="{{asset('js/scripts/form-select2.js')}}"></script>
        <script src="{{asset('js/scripts/customizer.js')}}"></script>
    @endsection

    <script>
        window.addEventListener('contentChanged', event => {
            var selectValue = @this.select_val;
            $.each( selectValue, function( key, value ) {
                $('#'+key).val(value);
            })
        });
        
        document.addEventListener('livewire:load', function () {
            $('input#input_text, textarea#textarea').characterCounter();
            var selectValue = @this.select_val;
            $.each( selectValue, function( key, value ) {
                $('#'+key).val(value);
            })

            $('select').on('change', function(e){
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

            @this.on('store', id_rol => {
                swal({
                    title: "Guardar registro",
                    icon: 'warning',
                    dangerMode: true,
                    buttons: {
                        cancel: 'Guardar!',
                        save: 'Guardar y liberar!'
                    }
                }).then(function (willSave) {
                    if (willSave) {
                        @this.call('store', 4)
                        swal("Poof! El registro a sido guardado y liberado con exito!", {
                            icon: "success",
                        });
                    } else {
                        @this.call('store', 3)
                        swal("El registro a sido guardado", {
                            icon: "success",
                        });
                    }
                }).catch(function (err) {
                    if (err) {
                        swal("Oh no!", "Existio algun error!", "error");
                    } else {
                        swal.stopLoading();
                        swal.close();
                    }
                });
            })
        })
    </script>
@endpush