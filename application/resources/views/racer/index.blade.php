@extends('layouts.app')

@section('title', __('racers.title'))

@section('content')
    @auth
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <a href="{{ route('racers.create') }}" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                    @lang('racers.link.create')
                </a>              
            </div>
        </div>  
    @endauth
    <div class='mdl-list'>
        @foreach($racers as $index => $racer)
            <div class="mdl-list__item mdl-list__item--two-line">
                <span class="mdl-list__item-primary-content">
                    <i class="material-icons  mdl-badge mdl-badge--overlap mdl-list__item-avatar" data-badge="{{ ($index + 1) }}">person</i>
                    <span>{{ $racer->name }}</span>
                    <span class="mdl-list__item-sub-title">{{ $racer->points }} @lang('racers.index.points')</span>
                </span>
                @auth
                <div class="mdl-list__item-secondary-action">
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="racer-action-{{ ($index + 1) }}">
                        <i class="material-icons">more_vert</i>
                    </button>
                    <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="racer-action-{{ ($index + 1) }}">
                        <li class="mdl-menu__item">
                            <a class="mdl-navigation__link" href="{{ route('racers.edit', [$racer->id]) }}">
                                @lang('racers.link.edit')
                            </a>
                        </li>
                        <li class="mdl-menu__item">
                            {!!Form::open()->delete()->url("racers/$racer->id")!!}
                                <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                                    @lang('racers.link.delete')
                                </button>
                            {!!Form::close()!!}
                        </li>
                    </ul>   
                </div>
                @endauth
            </div>
        @endforeach
    </div>
@endsection