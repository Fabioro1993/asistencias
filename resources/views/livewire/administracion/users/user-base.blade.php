<div>
    <br>
    <div class="card-content">
        <!-- <div class="card-body"> -->
        <ul class="tabs mb-2 row">
            <li class="tab">
                <a class="display-flex align-items-center" id="registro-tab" href="#registro">
                    <span>Registro</span>
                </a>
            </li>
            <li class="tab">
                <a class="display-flex align-items-center" id="information-tab" href="#information">
                    <span>Usuarios</span>
                </a>
            </li>
            {{-- <li class="tab">
                <a class="display-flex align-items-center" id="detalle-tab" href="#detalle">
                    <span>Detalle</span>
                </a>
            </li> --}}
        </ul>
        <div class="divider mb-3"></div>
        <div class="row">
            <div class="col s12 " id="registro">
                <livewire:user-registro-component/>
            </div>
            <div class="col s12" id="information">
                <livewire:user-component/>
            </div>
            {{-- <div class="col s12" id="detalle">
                <livewire:user-component/>
            </div> --}}
        </div>
        <!-- </div> -->
    </div>
</div>