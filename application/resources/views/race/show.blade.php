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
            <a data-id="{{ $laps->racer->id  }}" class="times-show mdl-list__item-secondary-action mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="#">
                <i class="material-icons">expand_more</i>
            </a>
        </div>
        <div id="times-{{ $laps->racer->id  }}" class="mdl-list__item hide times-details">
            <span class="mdl-list__item-primary-content">
                <ol>
                    @foreach($timeLaps[$laps->racer->id] as $timeIndex => $time)
                        <li>{{ $time }}</li>
                    @endforeach
                </ol> 
            </span>
        </div>
    @endforeach
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    $(function(){
        $('.times-show').click(function(e){
            e.preventDefault();
            e.stopPropagation();
            var obj = $(this);
            $('#times-' + obj.data('id')).slideToggle('fast', function(){
                var icon = obj.find('i.material-icons');
                (icon.html() == 'expand_more') ? icon.html('expand_less') : icon.html('expand_more');
            });
        });
    });
</script>
@endsection