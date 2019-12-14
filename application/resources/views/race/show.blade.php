@extends('layouts.app')

@section('title', __('races.show.title'))

@section('content')
<div class="race-card-wide mdl-card mdl-shadow--2dp">
    <div class="mdl-card__title">
        <h2 class="mdl-card__title-text">{!! $race->name !!}</h2>
    </div>
    <div class="mdl-card__supporting-text">
        {!! $race->date_start !!} - {!! $race->time_start !!}
        @if ($bestLap)
            <div class="card-best-lap">
                <h6>@lang('races.show.bestLap') <i class="fa fa-medal"></i> </h6>  
                <span>{{ $bestLap->Racer->name }} - {{ $bestLap->time }}</span>    
            </div>
        @endif
    </div>
</div>
@if($race->isTypeHours())
    <div class='mdl-list'>
        @foreach($race->getRankGroup() as $index => $laps)
            <div class="mdl-list__item mdl-list__item--two-line">
                <span class="mdl-list__item-primary-content">
                    <i class="material-icons  mdl-badge mdl-badge--overlap mdl-list__item-avatar" data-badge="{{ ($index + 1) }}">
                    person
                    </i>
                    <span>{{ $laps->group  }} | {{ implode(', ', $racers[$laps->group]) }}</span>
                    <span class="mdl-list__item-sub-title">
                        {{ $laps->time  }}
                    </span>
                </span>
            </div>
        @endforeach
    </div>
    <div class="mdl-grid">
        <h2 class="mdl-card__title-text">@lang('Individual time')</h2>  
    </div>     
@endif

<div class='mdl-list'>
    @php
        $rank = $race->getRank();
        $bestTime = isset($rank[0]) ? $rank[0]->time : 0; 
    @endphp
    @foreach($rank as $index => $laps)
        <div class="mdl-list__item mdl-list__item--two-line">
            <span class="mdl-list__item-primary-content">
                <i class="material-icons  mdl-badge mdl-badge--overlap mdl-list__item-avatar" data-badge="{{ ($index + 1) }}">
                person
                </i>
                <span>{{ $laps->racer->name  }}</span>
                <span class="mdl-list__item-sub-title">
                    {{ ($laps->laps == $race->laps || $race->isTypeHours() || $laps->time > $bestTime) ? $laps->time : '-' }}
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
                        <li class="{{ ($time == $bestLap->time) ? 'best-lap' : '' }}" >
                            {{ $time }}
                            @if( $time == $bestLap->time) 
                                <i class="fa fa-medal"></i> 
                            @endif
                        </li>
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