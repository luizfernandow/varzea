@extends('layouts.app')

@section('title', __('races.selectGroups.header'))

@section('content')
{!! Form::model(null, ['route' => ['saveGroups', $id], 'class'=>'', 'role' => 'form']) !!}
<div class="mdl-grid ">
        @foreach ($racers as $racerId => $racer)
            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--4-col-desktop">
                <label for="switch{{$racerId}}" class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                      <input type="checkbox" id="switch{{$racerId}}" class="mdl-switch__input" name="racers[]" value="{{$racerId}}">
                      <span class="mdl-switch__label">{{$racer}}</span>
                </label>
            </div>
            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--4-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('group') ? 'is-invalid' :'' }}">
                    {!! Form::number("group[$racerId]", NULL, array('class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('group', __('races.selectGroups.group'), array('class' => 'mdl-textfield__label')); !!}
                    
                    @if ($errors->has('group'))
                        <span class="mdl-textfield__error">
                            <strong>{{ $errors->first('group') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--4-col-desktop">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('number') ? 'is-invalid' :'' }}">
                    {!! Form::number("number[$racerId]", NULL, array('class' => 'mdl-textfield__input')) !!}
                    {!! Form::label('number', __('races.selectGroups.number'), array('class' => 'mdl-textfield__label')); !!}
                    
                    @if ($errors->has('number'))
                        <span class="mdl-textfield__error">
                            <strong>{{ $errors->first('number') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    <div class="mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
        {!! Form::submit(__('races.selectRacers.submit'), ['class' => 'mdl-button mdl-js-button mdl-js-ripple-effect mdl-color--primary mdl-color-text--white mdl-button--raised']); !!}
    </div>
</div>
{!! Form::close() !!}
@endsection