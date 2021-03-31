<div>
    <form action="" wire:submit.prevent="{!! $accion == 'store' ? 'store' : 'update' !!}" class="left-alert">
        <div class="row">
            <div class="input-field col s4 m4 l4">
                <label for="username" class="active">Indicador</label>
                <input wire:model.defer="username" id="username" type="text"
                    data-error="@error('username').errorTxt1 @enderror"
                    class="validate @error('username') invalid @enderror">
                @error('username')
                <small class="errorTxt1">
                    <div class="error">{{ $message }}</div>
                </small>
                @enderror
            </div>
            <div class="input-field col s4 m4 l6">
                <a wire:click="buscar" class="btn cyan waves-effect waves-light left">Buscar</a>
            </div>
        </div>        
        <div class="row">
            <div class="col s12 m6">
                <div class="row">
                    <div class="col s12 input-field">
                        <input id="new_name" type="text" value="{{$new_name}}" readonly>
                        <label for="new_name" class="active">Nombre</label>
                    </div>
                    <div class="col s12 input-field">
                        <input id="new_email" type="text" value="{{$new_email}}" readonly>
                        <label for="new_email" class="active">Correo</label>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="row">
                    <div class="col s12 input-field" wire:ignore>
                        <select wire:model.defer="rol">
                            <option selected>Rol</option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->id_rol }}"> {{ $rol->rol }}</option>
                            @endforeach
                        </select>
                        <label>Rol</label>
                    </div>
                    <div class="col s12 input-field" wire:ignore>
                        <select wire:model.defer="estado">
                            <option selected>Estado</option>
                            @foreach($estados as $estado)
                            <option value="{{ $estado->id_estado }}"> {{ $estado->estado }}</option>
                            @endforeach
                        </select>
                        <label>Estado</label>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <table class="mt-1">
                    <thead>
                        <tr>
                            <th>Gerencia Ubicacion</th>
                            @foreach ($ubicacion as $item)
                            <th>{{$item->UBICACION }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dptos as $dpto )
                        <tr>
                            <td>{{$dpto->DEP_DESCRI}}</td>
                            @foreach ($ubicacion as $item)
                            <td>
                                <label>
                                    <input type="checkbox" wire:model.defer="permiso.{{ $dpto->DEP_CODIGO }}.{{$item->UBICACION }}"/>
                                    <span></span>
                                </label>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- </div> -->
            </div>
            @if ( $accion == 'store')
            <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="btn indigo" wire:click="store">Guardar</button>
            </div>
            @elseif ( $accion == 'update')
            <div class="col offset-s8 s2 display-flex justify-content-end mt-3">
                <a class="btn red waves-effect waves-light left"
                    wire:click="cancelar">Cancelar</a>
            </div>
            <div class="col s2 display-flex justify-content-end mt-3">
                <button type="submit" class="btn indigo" wire:click="update">Actualizar</button>
            </div>
            @endif
        </div>
    </form>
</div>

@push('script')
<script>
    //REINICIAR SELECT
    window.addEventListener('contentChanged', event => {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    });
</script>
@endpush
