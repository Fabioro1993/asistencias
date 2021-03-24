<div>
    <br>
    <div id="responsive-table">
        <div class="row">
            <div class="col s12 m4 l4">
                <form action="" wire:submit.prevent="{!! $accion == 'store' ? 'store' : 'update' !!}"
                    class="left-alert">                    
                    <div class="input-field col s12">
                        <label for="estado" class="active">Estado</label>
                        <input wire:model.defer="estado" id="estado" type="text"
                        data-error="@error('estado').errorTxt1 @enderror"
                        class="validate @error('estado') invalid @enderror">
                        @error('estado')
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
                </form>
            </div>
            <div class="col s12 m8 20">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th data-field="id">ID</th>
                            <th data-field="name">Estado</th>
                            <th data-field="name">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $estado)
                        <tr>
                            <td>{{$estado->id_estado}}</td>
                            <td>{{$estado->estado}}</td>
                            <td>
                                <a wire:click="edit({{$estado}})" class="btn-floating waves-effect waves-light cyan lightrn-1">
                                    <i class="material-icons">mode_edit</i>
                                </a>
                                <a wire:click="$emit('delete',{{$estado->id_estado}})" class="btn-floating waves-effect waves-light red"><i class="material-icons">delete</i></a>
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
    document.addEventListener('livewire:load', function () {
        @this.on('delete', id_estado => {
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
                    @this.call('delete', id_estado)
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