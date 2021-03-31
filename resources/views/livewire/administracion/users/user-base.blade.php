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

@push('script')
<script>
    window.addEventListener('contentChanged', event => {

        // $('ul.tabs').tabs();
        // var el = document.querySelector('.tabs');
        // //console.log(el);
        // var instance = M.Tabs.init(el);

        //$('.tabs').tabs('select_tab', 'information');
        //alert('a');
        // var elems = document.querySelectorAll('.tabs');
        // var instances = M.Tabs.init(elems);
        // instance.select('#registro');

        

        // console.log(instances);
        // $('ul.tab').tabs('select_tab', 'information');
        // var elems = document.querySelectorAll('tabs');
        // var instance = M.Tabs.init(elems,{
        //     duration: 5000
            
        // });

        // alert('a');
        
        

        // $('.tabs').tabs();
        // var instance = M.Tabs.init(el); 
    });
</script>
@endpush