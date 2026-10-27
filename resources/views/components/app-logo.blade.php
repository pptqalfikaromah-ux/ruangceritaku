@props(['height' => '40', 'class' => '', 'style' => ''])

<img src="{{ app_logo() }}"
     alt="{{ app_setting('app_name', 'Ruang Ceritaku') }}"
     style="max-height: {{ is_numeric($height) ? $height . 'px' : $height }}; width: auto; object-fit: contain; {{ $style }}"
     class="{{ $class }}">
