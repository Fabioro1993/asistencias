{{-- page title --}}
@section('title', 'Empresa')
<div class="section">
    <div class="row" id="ecommerce-products">
        <div class="col s12 m3 l3 pr-0 hide-on-med-and-down animate fadeLeft"></div>
        <div class="col s12 m12 l9 pr-0">
            <div class="col s12 m4 l4">
                <div class="card hoverable animate fadeUp">
                    <div class="card-content">
                        <div wire:click='empresa("oso")'>
                            <span class="card-title text-ellipsis center">Hato El Oso</span>
                            <img src="{{asset('images/logo/LogoBufalinda.jpg')}}" class="responsive-img" alt="">
                        </div>
                        <div class="display-flex flex-wrap justify-content-center">
                            <a class="mt-2 waves-effect waves-light gradient-45deg-deep-purple-blue btn btn-block z-depth-4 modal-trigger"
                                href="#modal1">Detalle</a>
                        </div>
                    </div>
                </div>
                <!-- Modal Structure -->
                <div id="modal1" class="modal">
                    <div class="modal-content pt-2">
                        <div class="row" id="product-one">
                            <div class="col s12">
                                <a class="modal-close right"><i class="material-icons">close</i></a>
                            </div>
                            <div class="col m6 s12">
                                <img src="{{asset('images/logo/LogoBufalinda.jpg')}}" class="responsive-img" alt="">
                            </div>
                            <div class="col m6 s12">
                                <h5>Hato El Oso</h5>
                                <span class="new badge left ml-0 mr-2" data-badge-caption="">{{$oso}} Trabajadores</span>
                                <br>
                                <hr class="mb-5">
                                {{-- <span class="vertical-align-top mr-4">
                                    <i class="material-icons mr-3">favorite_border</i>Wishlist</span> --}}
                                <ul class="list-bullet">
                                    <li class="list-item-bullet">Accept SIM card and call</li>
                                    <li class="list-item-bullet">Make calling instead of mobile phone</li>
                                    <li class="list-item-bullet">Sync music play and sync control music</li>
                                    <li class="list-item-bullet">Sync Facebook,Twiter,emailand calendar</li>
                                </ul>
                                {{-- <h5>$399.00 <span class="prise-text-style ml-2">$459.00</span></h5>
                                <a class="waves-effect waves-light btn gradient-45deg-deep-purple-blue mt-2 mr-2">ADD TO
                                    CART</a>
                                <a class="waves-effect waves-light btn gradient-45deg-purple-deep-orange mt-2">BUY NOW</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m4 l4">
                <div class="card hoverable animate fadeUp" >
                    <div class="card-content">
                        <div wire:click='empresa("agrense")'>
                            <span class="card-title text-ellipsis center">Agrense</span>
                            <br>
                            <img src="{{asset('images/logo/logo-pangolarestec.png')}}" class="responsive-img" alt="">
                        </div>
                        <div class="display-flex flex-wrap justify-content-center">
                            <br>
                            <a class="mt-2 waves-effect waves-light gradient-45deg-deep-purple-blue btn btn-block z-depth-4 modal-trigger"
                                href="#modal2">Detalle</a>
                        </div>
                    </div>
                </div>
                <!-- Modal Structure -->
                <div id="modal2" class="modal">
                    <div class="modal-content">
                        <a class="modal-close right"><i class="material-icons">close</i></a>
                        <div class="row" id="product-two">
                            <div class="col m6 s12">
                                <img src="{{asset('images/logo/logo-pangolarestec.png')}}" class="responsive-img" alt="">
                            </div>
                            <div class="col m6 s12">
                                <h5>Agrense</h5>
                                <span class="new badge left ml-0 mr-2" data-badge-caption="">{{$agrense}} Trabajadores</span>
                                <br>
                                <hr class="mb-5">
                                {{-- <span class="vertical-align-top mr-4">
                                    <i class="material-icons mr-3">favorite_border</i>Wishlist</span> --}}
                                <ul class="list-bullet">
                                    <li class="list-item-bullet">Accept SIM card and call</li>
                                    <li class="list-item-bullet">Make calling instead of mobile phone</li>
                                    <li class="list-item-bullet">Sync music play and sync control music</li>
                                    <li class="list-item-bullet">Sync Facebook,Twiter,emailand calendar</li>
                                </ul>
                                {{-- <h5>$399.00 <span class="prise-text-style ml-2">$459.00</span></h5>
                                <a class="waves-effect waves-light btn gradient-45deg-deep-purple-blue mt-2 mr-2">ADD TO
                                    CART</a>
                                <a class="waves-effect waves-light btn gradient-45deg-purple-deep-orange mt-2">BUY NOW</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
{{-- <script src="{{asset('js/scripts/eCommerce-products-page.js')}}"></script> --}}
@endsection