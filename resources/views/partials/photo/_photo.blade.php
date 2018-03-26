@php
    $width = isset($width) ? $width : 25;
@endphp

@if($user->photo()->count())
    <img src="{{ asset($user->photo->getUrl()) }}" width="{{ $width }}px">
@endif
