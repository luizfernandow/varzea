@extends('layouts.app')

@section('title', __('races.title'))

@section('content')
{!! Form::model(new App\Race, ['route' => ['races.store'], 'class'=>'', 'role' => 'form']) !!}
<div class="mdl-grid ">
    @include('race.form')

    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
        {!! Form::submit(__('races.form.submit'), ['class' => 'mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white mdl-button--raised']); !!}
    </div>
</div>
{!! Form::close() !!}
@endsection