@extends('layouts.app')

@section('title', __('racers.title'))

@section('content')
{!! Form::model($racer, ['route' => ['racers.update', $racer->id], 'method' => 'put', 'class'=>'', 'role' => 'form']) !!}
<div class="mdl-grid ">
	<div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('name') ? 'is-invalid' :'' }}">
		    {!! Form::text('name', NULL, array('id' => 'racer-name', 'class' => 'mdl-textfield__input')) !!}
		    {!! Form::label('name', __('racers.form.name'), array('class' => 'mdl-textfield__label')); !!}
		    
		    @if ($errors->has('name'))
                <span class="mdl-textfield__error">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
		</div>
	</div>
	<div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
		{!! Form::submit(__('racers.form.save'), ['class' => 'mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white mdl-button--raised']); !!}
	</div>
</div>
{!! Form::close() !!}
@endsection