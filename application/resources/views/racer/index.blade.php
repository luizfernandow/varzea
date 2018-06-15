@extends('layouts.app')

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
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <table class="mdl-data-table mdl-js-data-table">
                <thead>
                    <tr>
                        <th scope="col" class="mdl-data-table__cell--non-numeric">#</th>
                        <th scope="col" class="mdl-data-table__cell--non-numeric">@lang('racers.index.name')</th>
                        <th scope="col">@lang('racers.index.points')</th>
                        @auth
                            <th></th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach($racers as $index => $racer)
                    <tr>
                        <td scope="row">{{ ($index + 1) }}</td>
                        <td class="mdl-data-table__cell--non-numeric">{{ $racer->name }}</td>
                        <td>{{ $racer->points }}</td>
                        @auth
                            <td scope="col">
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
                            </td>
                        @endauth
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
@endsection