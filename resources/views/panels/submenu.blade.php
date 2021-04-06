<div class="collapsible-body">
  <ul class="collapsible collapsible-sub" data-collapsible="accordion">
    @foreach ($menu as $submenu)
    @php
      $custom_classes="";
      $acceso = "hidden";
      if(isset($submenu->class))
      {
      $custom_classes = $submenu->class;
      }
      if (in_array(Auth::user()->rol->id_rol, $submenu->acceso)) {
              $acceso = '';
       }
      @endphp
    <li class="{{(request()->is($submenu->url.'*')) ? 'active' : '' }}" {{$acceso}}>
      <a href="@if(($submenu->url) === 'javascript:void(0)'){{$submenu->url}} @else{{url($submenu->url)}} @endif"
        class="{{$custom_classes}} {{(request()->is($submenu->url.'*')) ? 'active '.$configData['activeMenuColor'] : '' }}"
        @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
        target="{{isset($submenu->newTab) ? '_blank':''}}">
        <i class="material-icons">radio_button_unchecked</i>
        <span>{{ __('locale.'.$submenu->name)}}</span>
      </a>
      @if (isset($submenu->submenu))
      @include('panels.submenu', ['menu' => $submenu->submenu])
      @endif
    </li>
    @endforeach
  </ul>
</div>