<aside
  class="{{$configData['sidenavMain']}} @if(!empty($configData['activeMenuType'])) {{$configData['activeMenuType']}} @else {{$configData['activeMenuTypeClass']}}@endif @if(($configData['isMenuDark']) === true) {{'sidenav-dark'}} @elseif(($configData['isMenuDark']) === false){{'sidenav-light'}}  @else {{$configData['sidenavMainColor']}}@endif">
  <div class="brand-sidebar">
    <h1 class="logo-wrapper">
      <a class="brand-logo darken-1" href="{{asset('/')}}">
        @if(!empty($configData['mainLayoutType']) && isset($configData['mainLayoutType']))
          @if($configData['mainLayoutType']=== 'vertical-modern-menu')
          <img class="hide-on-med-and-down" src="{{asset($configData['largeScreenLogo'])}}" alt="materialize logo" />
          <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['smallScreenLogo'])}}"
            alt="materialize logo" />

          @elseif($configData['mainLayoutType']=== 'vertical-menu-nav-dark')
          <img src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />

          @elseif($configData['mainLayoutType']=== 'vertical-gradient-menu')
          <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['largeScreenLogo'])}}"
            alt="materialize logo" />
          <img class="hide-on-med-and-down" src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />

          @elseif($configData['mainLayoutType']=== 'vertical-dark-menu')
          <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['largeScreenLogo'])}}"
            alt="materialize logo" />
          <img class="hide-on-med-and-down" src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />
          @endif
        @endif
        <span class="logo-text hide-on-med-and-down">
          @if(!empty ($configData['templateTitle']) && isset($configData['templateTitle']))
          {{$configData['templateTitle']}}
          @else
          Asistencias
          @endif
        </span>
      </a>
      <a class="navbar-toggler" href="javascript:void(0)"><i class="material-icons">radio_button_checked</i></a></h1>
  </div>
  <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
    data-menu="menu-navigation" data-collapsible="menu-accordion">
    {{-- Foreach menu item starts --}}
    {{-- @if (session('empresa') != null) 
    <li class="navigation-header">
      <a class="navigation-header-text">{{session('empresa')}}</a>
      <i class="navigation-header-icon material-icons">more_horiz</i>
    </li>--}}
    <li class="bold">
      @if(isset($menu->submenu))
        @include('panels.submenu', ['menu' => $menu->submenu])
      @endif
    </li>
    {{-- @php
        dd($menuData[0], Auth::user()->rol->id_rol);
    @endphp --}}
      @if(!empty($menuData[0]) && isset($menuData[0]))
        @foreach ($menuData[0]->menu as $menu)
          @php
            $custom_classes="";
            $acceso = "hidden";
            if(isset($menu->class))
            {
              $custom_classes = $menu->class;
            }
            if (in_array(Auth::user()->rol->id_rol, $menu->acceso)) {
              $acceso = '';
            }
          @endphp
          @if(isset($menu->navheader))
          <li class="navigation-header" {{$acceso}}>
            <a class="navigation-header-text">{{ $menu->navheader }}</a>
            <i class="navigation-header-icon material-icons">{{$menu->icon}}</i>
          </li>
          @else
            <li class="bold {{(request()->is($menu->url.'*')) ? 'active' : '' }}" {{$acceso}}>
  
              <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active '.$configData['activeMenuColor'] : ''}}"
                @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
                href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
                {{isset($menu->newTab) ? 'target="_blank"':''}}>
                <i class="material-icons">{{$menu->icon}}</i>
                <span class="menu-title">{{ __('locale.'.$menu->name)}} </span>
                @if(isset($menu->tag))
                <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
                @endif
              </a>
              @if(isset($menu->submenu))
                @include('panels.submenu', ['menu' => $menu->submenu])
              @endif
            </li>
          @endif
        @endforeach
      @endif
    {{-- @endif --}}
    <li class="bold " style="position: absolute; bottom: 0; margin-left: 18px;">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a class="white-text"
          href="{{ route('logout') }}" onclick="event.preventDefault();
          this.closest('form').submit();">
          <i class="material-icons">keyboard_tab</i>
          <span class="menu-title">Cerrar Sesión</span>
        </a>
      </form>      
    </li>
  </ul>
  <div class="navigation-background"></div>
  <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only"
    href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>