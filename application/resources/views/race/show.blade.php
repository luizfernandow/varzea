@extends('layouts.app')

@section('title', __('races.show.title'))

@section('content')
<div class="race-card-wide mdl-card mdl-shadow--2dp">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">{!! $race->name !!}</h2>
    </div>
    <div class="mdl-card__supporting-text">
        {!! $race->date_start !!} - {!! $race->time_start !!}
    </div>
</div>
<div class='mdl-list'>
    @foreach($race->getRank() as $index => $laps)
        <div class="mdl-list__item mdl-list__item--two-line">
            <span class="mdl-list__item-primary-content">
                <i class="material-icons  mdl-badge mdl-badge--overlap mdl-list__item-avatar" data-badge="{{ ($index + 1) }}">
                person
                </i>
                <span>{{ $laps->racer->name  }}</span>
                <span class="mdl-list__item-sub-title">
                    {{ ($laps->laps == $race->laps) ? $laps->time : '-' }}
                    @if ($laps->laps == $race->laps)
                       / @base_point($index) @lang('races.show.points')
                    @endif
                </span>
            </span>
        </div>
    @endforeach
</div>
@endsection