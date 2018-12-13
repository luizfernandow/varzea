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
    <label for="type" class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
          <input type="checkbox" id="type" class="mdl-switch__input" name="type" {{ (isset($race) && $race->isTypeHours()) || (old('type') && old('type') == 'on') ? 'checked' : '' }}>
          <span class="mdl-switch__label">@lang('races.form.type')</span>
    </label>
</div>

<div class="lap-group mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
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

<div class="hour-group mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('hours') ? 'is-invalid' :'' }}">
        {!! Form::number('hours', NULL, array('id' => 'races-hours', 'class' => 'mdl-textfield__input')) !!}
        {!! Form::label('hours', __('races.form.hours'), array('class' => 'mdl-textfield__label')); !!}
        
        @if ($errors->has('hours'))
            <span class="mdl-textfield__error">
                <strong>{{ $errors->first('hours') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="hour-group mdl-cell mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('group') ? 'is-invalid' :'' }}">
        {!! Form::number('group', NULL, array('id' => 'races-group', 'class' => 'mdl-textfield__input')) !!}
        {!! Form::label('group', __('races.form.group'), array('class' => 'mdl-textfield__label')); !!}
        
        @if ($errors->has('group'))
            <span class="mdl-textfield__error">
                <strong>{{ $errors->first('group') }}</strong>
            </span>
        @endif
    </div>
</div>

@section('javascript')
    @parent('javascript')

<script type="text/javascript">
$(function() {
    $('#type').on('change', function(){
        var obj = $(this);
        if (obj.prop('checked')) {
            $('.lap-group').addClass('hide');
            $('.hour-group').removeClass('hide');
        } else {            
            $('.lap-group').removeClass('hide');
            $('.hour-group').addClass('hide');
        }
    }).change();
});
</script>
@endsection