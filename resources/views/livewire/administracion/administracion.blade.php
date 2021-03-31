@section('title','Administración')
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/sweetalert/sweetalert.css')}}">
@endsection
<div>
    <!--Basic Tabs-->
    <div class="row">
        <div class="col s12 m12 l12">
            <div id="basic-tabs" class="card card card-default scrollspy">
                <div class="card-content">
                    <div class="card-title">
                        <div class="row">
                            <div class="col s12 m6 l10">
                                <h4 class="card-title">Administración</h4>
                                <p>
                                Sección de maestros
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <ul class="tabs tab-demo z-depth-1">
                                <li class="tab col m3"><a href="#estado">Estados</a></li>
                                <li class="tab col m3"><a href="#roles">Roles</a></li>
                                <li class="tab col m3"><a class="active" href="#usuarios">Usuarios</a></li>
                                <li class="tab col m3"><a href="#evaluaciones">Evaluacion</a></li>
                            </ul>
                        </div>
                        <div class="col s12">
                            <div id="estado" class="col s12">
                                <livewire:estado-component/>
                            </div>
                            <div id="roles" class="col s12">
                                <livewire:rol-component/>
                            </div>
                            <div id="usuarios" class="col s12">
                                <livewire:user-base/>
                                
                                {{-- <livewire:user-component/> --}}
                            </div>
                            <div id="evaluaciones" class="col s12">
                                <livewire:evaluacion-component/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

{{-- vendor script --}}
@section('vendor-script')
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>
@endsection

