@extends('layouts.app')

@section('title', __('races.title'))

@section('content')
{!! Form::model(new App\Race, ['route' => ['races.store'], 'class'=>'', 'role' => 'form']) !!}
<div class="mdl-grid ">
    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('name') ? 'is-invalid' :'' }}">
            {!! Form::text('name', NULL, array('id' => 'races-name', 'class' => 'mdl-textfield__input')) !!}
            {!! Form::label('name', __('races.form.name'), array('class' => 'mdl-textfield__label')); !!}
            
            @if ($errors->has('name'))
                <span class="mdl-textfield__error">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('date_start') ? 'is-invalid' :'' }}">
            {!! Form::text('date_start', NULL, array('id' => 'races-date_start', 'class' => 'mdl-textfield__input')) !!}
            {!! Form::label('date_start', __('races.form.date'), array('class' => 'mdl-textfield__label')); !!}
            
            @if ($errors->has('date_start'))
                <span class="mdl-textfield__error">
                    <strong>{{ $errors->first('date_start') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('time_start') ? 'is-invalid' :'' }}">
            {!! Form::text('time_start', NULL, array('id' => 'races-time_start', 'class' => 'mdl-textfield__input')) !!}
            {!! Form::label('time_start', __('races.form.hour'), array('class' => 'mdl-textfield__label')); !!}
            
            @if ($errors->has('time_start'))
                <span class="mdl-textfield__error">
                    <strong>{{ $errors->first('time_start') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('laps') ? 'is-invalid' :'' }}">
            {!! Form::number('laps', NULL, array('id' => 'races-laps', 'class' => 'mdl-textfield__input')) !!}
            {!! Form::label('laps', __('races.form.laps'), array('class' => 'mdl-textfield__label')); !!}
            
            @if ($errors->has('laps'))
                <span class="mdl-textfield__error">
                    <strong>{{ $errors->first('laps') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
        {!! Form::submit(__('races.form.submit'), ['class' => 'mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white mdl-button--raised']); !!}
    </div>
</div>
{!! Form::close() !!}
@endsection