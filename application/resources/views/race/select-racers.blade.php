@extends('layouts.app')

@section('title', __('races.selectRacers.header'))

@section('content')
<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--6-col mdl-cell--6-col-phone">
        <button type="button" id="filter" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
            @lang('races.selectRacers.filter')
        </button>
        <button type="button" id="removeFilter" class="hide mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
            @lang('races.selectRacers.remove_filter')
        </button>
    </div>
</div>

<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col-phone">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input"  type="text" id="racerFilter">
            <label for="racerFilter" class="mdl-textfield__label">@lang('races.selectRacers.racer_filter')</label>
        </div>
    </div>  
</div>

{!! Form::model(null, ['route' => ['startRace', $id], 'class'=>'', 'role' => 'form']) !!}
<div id="selectRacers" class="mdl-layout__content ">
        @foreach ($racers as $racerId => $racer)
            <div class="mdl-grid racer-line">
                <div class="mdl-cell mdl-cell--3-col-phone mdl-cell--6-col-desktop padding-top-20">
                    <label for="switch{{$racerId}}" class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                          <input type="checkbox" id="switch{{$racerId}}" class="mdl-switch__input racers-select" name="racers[]" value="{{$racerId}}">
                          <span class="mdl-switch__label label-racer">{{$racer}}</span>
                    </label>
                </div>
                <div class="mdl-cell mdl-cell--1-col-phone mdl-cell--6-col-desktop">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input racers-number" data-racer="{{$racerId}}" name="numbers[{{$racerId}}]" type="number" id="number{{$racerId}}">
                        <label for="number{{$racerId}}" class="mdl-textfield__label">@lang('races.selectRacers.number')</label>
                    </div>
                </div>                
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

let racersSelStorage = window.localStorage.getItem('racersSel{{ $id }}');
if (racersSelStorage) {
    let racersSel = JSON.parse(racersSelStorage);
    for (let [key, value] of Object.entries(racersSel)) {
        $('#switch' + value).parent().click();
    }
}

let racersNumberStorage = window.localStorage.getItem('racersNumber{{ $id }}');
if (racersNumberStorage) {
    let racersNumber = JSON.parse(racersNumberStorage);
    for (let [key, value] of Object.entries(racersNumber)) {
        $('#number' + key).val(value);
    }
}

dialog.querySelector('.resume-race').addEventListener('click', function() {
    $('#selectRacers input[type="submit"]').click();
});

dialog.querySelector('.new-race').addEventListener('click', function() {
    dialog.close();
    $('#selectRacers').show();
     window.localStorage.clear();
});

$('.racers-select').on('change', function(){
    var checkeds = $('.racers-select:checked');
    var selecteds = [];
    checkeds.each(function( index ) {
        selecteds.push($( this ).val());
    });
    window.localStorage.setItem('racersSel{{ $id }}', JSON.stringify(selecteds));
});

$('.racers-number').on('change', function(){
    var nummbers = $('.racers-number');
    var nummbersSel = [];
    nummbers.each(function( index ) {
        if ($( this ).val() != '') {
            nummbersSel[$(this).data('racer')] = $( this ).val();
        }
    });
    window.localStorage.setItem('racersNumber{{ $id }}', JSON.stringify(nummbersSel));
});

$('#filter').click(function() {
    $('.racer-line').addClass('hide');
    $('.racers-select:checked').parents('.racer-line').removeClass('hide');
    $('#filter').addClass('hide');
    $('#removeFilter').removeClass('hide');
});

$('#removeFilter').click(function() {
    $('.racer-line').removeClass('hide');
    $('#removeFilter').addClass('hide');
    $('#filter').removeClass('hide');
});

$('#racerFilter').on('keyup', function() {
    var search = $('#racerFilter').val();
    $('.racer-line').removeClass('hide-search');
    if (search != '') {
        $('.racer-line').addClass('hide-search');
        $(".label-racer").each(function () {
            if (search != "" && $(this).text().search(new RegExp(search,'gi')) != -1) {
                $(this).parents('.racer-line').removeClass('hide-search');
            }
        });
    }
});

</script>
@endsection