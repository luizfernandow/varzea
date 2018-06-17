@extends('layouts.app')

@section('title', __('races.title'))

@section('content')
     @auth
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <a href="{{ route('races.create') }}" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                    @lang('races.link.create')
                </a>              
            </div>
        </div>  
    @endauth

    <div class="mdl-list">
        @foreach($races as $race)
            <div class="mdl-list__item mdl-list__item--three-line">
                <span class="mdl-list__item-primary-content">
                    <span><a href="{!! route('races.show', [$race->id]) !!}"> {!! $race->name !!} </a></span>
                    <span class="mdl-list__item-text-body">
                        {{ $race->date_start }} - {{ $race->time_start }}
                        <br>
                        {{ $race->laps }} @lang('races.index.laps')
                    </span>
                </span>
                @auth
                    <span class="mdl-list__item-secondary-content">
                        <div class="mdl-list__item-secondary-action">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="race-action-{{ $race->id }}">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="race-action-{{ $race->id }}">
                                @if(!$race->locked)
                                    <li class="mdl-menu__item">
                                        <a class="mdl-navigation__link" href="{{ route('selectRacers', [$race->id]) }}">
                                            @lang('races.link.selectRacers')
                                        </a>
                                    </li>
                                @endif 
                                <li class="mdl-menu__item">
                                    <a class="mdl-navigation__link" href="{{ route('races.edit', [$race->id]) }}">
                                        @lang('races.link.edit')
                                    </a>
                                </li>
                                <li class="mdl-menu__item">
                                    {!!Form::open()->delete()->url("races/$race->id")!!}
                                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                                            @lang('races.link.delete')
                                        </button>
                                    {!!Form::close()!!}
                                </li>
                            </ul>
                        </div>
                    </span>
                @endauth
            </div>
        @endforeach
    </div>
@endsection