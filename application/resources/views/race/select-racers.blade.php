@extends('layouts.app')

@section('title', __('races.selectRacers.header'))

@section('content')
{!! Form::model(null, ['route' => ['startRace', $id], 'class'=>'', 'role' => 'form']) !!}
<div id="selectRacers" class="mdl-grid ">
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
<dialog class="mdl-dialog">
    <h4 class="mdl-dialog__title">Load race in progress?</h4>
    <div class="mdl-dialog__content">
        <p>
        There is a race in progress, loading it will resume the race.
        </p>
    </div>
    <div class="mdl-dialog__actions">
        <button type="button" class="mdl-button resume-race">Resume the race</button>
        <button type="button" class="mdl-button new-race">New Race</button>
    </div>
</dialog>
@endsection

@section('javascript')
    @parent('javascript')
<script type="text/javascript">
var dialog = document.querySelector('dialog');
if (!dialog.showModal) {
    dialogPolyfill.registerDialog(dialog);
}

let racersTimeStorage = window.localStorage.getItem('racersTime{{ $id }}');
if (racersTimeStorage) {
    $('#selectRacers').hide();
    dialog.showModal();
}

dialog.querySelector('.resume-race').addEventListener('click', function() {
    let racersTime = JSON.parse(racersTimeStorage);
    for (let [key, value] of Object.entries(racersTime)) {
        $('#switch' + key).parent().click();
    }
    $('#selectRacers input[type="submit"]').click();
});

dialog.querySelector('.new-race').addEventListener('click', function() {
    dialog.close();
    $('#selectRacers').show();
     window.localStorage.clear();
});
</script>
@endsection