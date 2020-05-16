@extends('layouts.app')

@section('title', __('championships.title'))

@section('content')
{!! Form::model(new App\Championship, ['route' => ['championships.store'], 'class'=>'', 'role' => 'form']) !!}
<div class="mdl-grid ">
	<div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
		<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('name') ? 'is-invalid' :'' }}">
		    {!! Form::text('name', NULL, array('id' => 'championship-name', 'class' => 'mdl-textfield__input')) !!}
		    {!! Form::label('name', __('championships.form.name'), array('class' => 'mdl-textfield__label')); !!}
		    
		    @if ($errors->has('name'))
                <span class="mdl-textfield__error">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
		</div>
	</div>
	<div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
		{!! Form::submit(__('championships.form.submit'), ['class' => 'mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white mdl-button--raised']); !!}
	</div>
</div>
{!! Form::close() !!}
@endsection