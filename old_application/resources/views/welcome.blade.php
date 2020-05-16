@extends('layouts.app')

@section('title', __('welcome.title'))

@section('content')
<div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
    @lang('welcome.header')
    <img src="{{ asset('images/logo-150x150.png') }}">
</div>

<div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
    <img class="img-responsive" src="{{ asset('images/mapa.jpeg') }}">
</div>
@endsection