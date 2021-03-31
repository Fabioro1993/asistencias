<div>
    <br>
    <div id="responsive-table">
        <div class="row">
            <div class="col s12 m4 l4">
                <form action="" wire:submit.prevent="{!! $accion == 'store' ? 'store' : 'update' !!}"
                    class="left-alert">
                    <div id="input-fields">
                        <div class="input-field col s12">
                            <label for="rol" class="active">Rol</label>
                            <input wire:model.defer="rol" id="rol" type="text"
                            data-error="@error('rol').errorTxt1 @enderror"
                            class="validate @error('rol') invalid @enderror">
                            @error('rol')
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
                            <th data-field="name">Rol</th>
                            <th data-field="name">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $rol)
                        <tr>
                            <td>{{$rol->id_rol}}</td>
                            <td>{{$rol->rol}}</td>
                            <td>
                                <a wire:click="edit({{$rol}})" class="btn-floating waves-effect waves-light cyan lightrn-1">
                                    <i class="material-icons">mode_edit</i>
                                </a>
                                <a wire:click="$emit('delete',{{$rol->id_rol}})" class="btn-floating waves-effect waves-light red"><i class="material-icons">delete</i></a>
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
        @this.on('delete', id_rol => {
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
                    @this.call('delete', id_rol)
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