<div>
    <br>
    <div id="responsive-table">
        <div class="row">
            <div class="col s12 m4 l4">
                <form action="" wire:submit.prevent="{!! $accion == 'store' ? 'store' : 'update' !!}"
                    class="left-alert">
                    <div id="input-fields">
                        <div class="input-field col s12">
                            <label for="evaluacion" class="active">Evaluacion</label>
                            <input wire:model.defer="evaluacion" id="evaluacion" type="text"
                            data-error="@error('evaluacion').errorTxt1 @enderror"
                            class="validate @error('evaluacion') invalid @enderror">
                            @error('evaluacion')
                                <small class="errorTxt1">
                                    <div class="error">{{ $message }}</div>
                                </small>
                            @enderror
                        </div>
                        <div class="input-field col s12" wire:ignore>
                            <select wire:model.defer="formato" wire:model="formato">
                              <option selected>Formato</option>
                              <option value="numerico">Numerico</option>
                              <option value="texto">Texto</option>
                            </select>
                            <label>Formato</label>
                        </div>
                        <div class="input-field col s12" {{$hidden}}>
                            <label for="max" class="active">Maximo</label>
                            <input wire:model.defer="max" id="max" type="text"
                            data-error="@error('max').errorTxt1 @enderror"
                            class="validate @error('max') invalid @enderror">
                            @error('max')
                                <small class="errorTxt1">
                                    <div class="error">{{ $message }}</div>
                                </small>
                            @enderror
                        </div>
                        @if ( $accion == 'store')
                        <div class="input-field col s8 offset-s5">
                            <button class="btn waves-effect waves-light cyan submit" type="submit">Guardar
                                <i class="material-icons left">send</i>
                            </button>
                        </div>
                        @else
                        <div class="input-field col s3">
                            <a class="btn red waves-effect waves-light left"
                                wire:click="cancelar">Cancelar</a>
                        </div>
                        <div class="input-field col s3 offset-s3">
                            <button class="btn cyan waves-effect waves-light left">Actualizar</button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col s12 m8 20">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th data-field="id">ID</th>
                            <th data-field="name">Evaluacion</th>
                            <th data-field="name">Formato</th>
                            <th data-field="name">Abrev</th>
                            <th data-field="name">Max</th>
                            <th data-field="name">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $evalua)
                        <tr>
                            <td>{{$evalua->id_evaluacion}}</td>
                            <td>{{$evalua->evaluacion}}</td>
                            <td>{{ucwords($evalua->formato)}}</td>
                            <td>{{$evalua->abrv}}</td>
                            <td>{{$evalua->max}}</td>
                            <td>
                                <a wire:click="edit({{$evalua}})" class="btn-floating waves-effect waves-light cyan lightrn-1">
                                    <i class="material-icons">mode_edit</i>
                                </a>
                                <a wire:click="$emit('delete',{{$evalua->id_evaluacion}})" class="btn-floating waves-effect waves-light red"><i class="material-icons">delete</i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    window.addEventListener('contentChanged', event => {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    }); 
    document.addEventListener('livewire:load', function () {
        @this.on('delete', id_evaluacion => {
            swal({
                title: "Seguro desea eliminar?",
                text: "No podrás recuperar este registro!",
                icon: 'warning',
                dangerMode: true,
                buttons: {
                    cancel: 'No, cancelar!',
                    delete: 'Si, eliminar!'
                }
            }).then(function (willDelete) {
                if (willDelete) {
                    @this.call('delete', id_evaluacion)
                    swal("Poof! El registro a sido eliminado con exito!", {
                        icon: "success",
                    });
                } else {
                    swal("El registro esta a salvo", {
                        title: 'Cancelado',
                        icon: "error",
                    });
                }
            });
        })
    })
</script>
@endpush