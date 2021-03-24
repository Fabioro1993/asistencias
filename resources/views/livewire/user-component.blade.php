<div>
    <table class="responsive-table">
        <thead>
            <tr>
                <th data-field="id">ID</th>
                <th data-field="name">Indicador</th>
                <th data-field="name">Nombre</th>
                <th data-field="name">Correo</th>
                <th data-field="name">Rol</th>
                <th data-field="name">Estado</th>
                <th data-field="name">Accion</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->rol->rol}}</td>
                    <td>{{$user->estado->estado}}</td>
                    <td>
                        <a wire:click="showPost({{ $user->id }})"
                            class="btn-floating waves-effect waves-light cyan lightrn-1">
                            <i class="material-icons">mode_edit</i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>