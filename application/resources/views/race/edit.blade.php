@extends('layouts.app')

@section('title', __('races.title'))

@section('content')
{!! Form::model($race, ['route' => ['races.update', $race->id], 'method' => 'put', 'class'=>'', 'role' => 'form']) !!}
<div class="mdl-grid ">
    @include('race.form')

    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
        {!! Form::submit(__('races.form.save'), ['class' => 'mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white mdl-button--raised']); !!}
    </div>
</div>
{!! Form::close() !!}
@endsection