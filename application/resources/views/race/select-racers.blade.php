@extends('layouts.app')

@section('title', __('races.selectRacers.header'))

@section('content')
{!! Form::model(null, ['route' => ['startRace', $id], 'class'=>'', 'role' => 'form']) !!}
<div class="mdl-grid ">
        @foreach ($racers as $racerId => $racer)
            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
                <label for="switch{{$racerId}}" class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                      <input type="checkbox" id="switch{{$racerId}}" class="mdl-switch__input" name="racers[]" value="{{$racerId}}">
                      <span class="mdl-switch__label">{{$racer}}</span>
                </label>
            </div>
        @endforeach
    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
        {!! Form::submit(__('races.selectRacers.submit'), ['class' => 'mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white mdl-button--raised']); !!}
    </div>
</div>
{!! Form::close() !!}
@endsection