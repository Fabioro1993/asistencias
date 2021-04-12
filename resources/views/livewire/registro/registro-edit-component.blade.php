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
@endsection
<div>
    @section('title','Editar')
    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card-panel">
                <div class="row">
                    <div id="input-fields">                        
                        <div class="input-field col s2">
                            <label class="active" for="fecha"></label>
                            <input id="fecha"
                            type="date"
                            class="materialize-textarea"
                            value="{{$data->fecha}}"
                            readonly>
                        </div>  
                        <div class="input-field col s3">
                            <input id="nombre" type="text" class="validate" readonly value="{{ Auth::user()->name }}">
                            <label for="nombre" class="active">Responsable</label>
                        </div>  
                        <div class="input-field col s7">
                            <textarea id="textarea1" class="materialize-textarea" data-length="120" wire:model.defer="observacion"></textarea>
                            <label for="textarea1">Observacion</label>
                        </div>
                    </div>
                    <div class="col s12">
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th class="center" data-field="id">Empresa</th>
                                    <th class="center" data-field="id">Cedula</th>
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
                                    <td class="center">{{$p_oso->cedula}}</td>
                                    <td style="font-size: 11px;">{{$p_oso->nombre }}</td>                                            
                                    <td>
                                        <select class="select2 browser-default" wire:model="asistencia" id="{{ $p_oso->cedula }}"
                                            wire:ignore>
                                            <option value="0_{{ $p_oso->cedula }}">0</option>
                                            <option value="1_{{ $p_oso->cedula }}">1</option>
                                            @foreach($select as $estado)
                                                <option value="{{ $estado->id_evaluacion }}_{{ $p_oso->cedula }}"> {{ $estado->abrv }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    @for ($i = 1; $i < count($evaluaciones); $i++)
                                        <td>
                                            <input type="number" min="1" max="{{$evaluaciones[$i]->max}}" class="evaluacion center"
                                            wire:model.defer="evaluacion.{{ $p_oso->cedula }}.{{$evaluaciones[$i]->id_evaluacion}}"
                                            wire:keyup="evaluacion({{$p_oso->cedula}}, '{{$evaluaciones[$i]->id_evaluacion}}')">
                                            @if ($error[$p_oso->cedula][$evaluaciones[$i]->id_evaluacion] == 1)
                                                <small class="errorTxt1">
                                                    <div class="error">El valor debe ser menor a 2</div>
                                                </small>
                                            @endif
                                        </td> 
                                    @endfor
                                    <td>
                                        <input id="comentario" type="text" class="validate" wire:model.defer='comentario.{{ $p_oso->cedula }}'>
                                    </td>
                                    <td {{$hidden}}>
                                        <input type="number" min="2" max="6" class="adicionales center"
                                        wire:model.defer="adicionales.{{ $p_oso->cedula }}"
                                        wire:keyup="adicionales({{$p_oso->cedula}})">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <button wire:click="$emit('update','a')" class="btn waves-effect waves-light cyan submit right" type="submit">Guardar
                                <i class="material-icons left">send</i>
                            </button>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>

    <a href="#" data-target="theme-cutomizer-out" class="btn btn-customizer pink accent-2 white-text sidenav-trigger theme-cutomizer-trigger">
        <i class="material-icons">settings</i>
    </a>
    
    <div id="theme-cutomizer-out" class="theme-cutomizer sidenav row" style="right: 12px;">
        @include('livewire.registro.registro-resumen-edit-component') 
    </div>
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
            var seletVal = @this.select_val;
            $.each( seletVal, function( key, value ) {
                $('#'+key).val(value);
            })
        });

        document.addEventListener('livewire:load', function () {
            $('input#input_text, textarea#textarea1').characterCounter();
            
            var seletVal = @this.select_val;
            $.each( seletVal, function( key, value ) {
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

            @this.on('update', id_rol => {
                swal({
                    title: "Guardar registro",
                    // text: "No podrás recuperar este registro!",
                    icon: 'warning',
                    dangerMode: true,
                    buttons: {
                        cancel: 'Guardar!',
                        save: 'Guardar y liberar!'
                    }
                }).then(function (willSave) {
                    if (willSave) {
                        @this.call('update', 4)
                        swal("Poof! El registro a sido guardado y liberado con exito!", {
                            icon: "success",
                        });
                    } else {
                        @this.call('update', 3)
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