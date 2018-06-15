@extends('layouts.app')

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
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col table-responsive">
            <table class="mdl-data-table mdl-js-data-table">
                <thead>
                    <tr>
                        <th scope="col" class="mdl-data-table__cell--non-numeric">@lang('races.index.name')</th>
                        <th scope="col" class="mdl-data-table__cell--non-numeric">@lang('races.index.date')</th>
                        <th scope="col" class="mdl-data-table__cell--non-numeric">@lang('races.index.hour')</th>
                        <th scope="col">@lang('races.index.laps')</th>
                        @auth
                            <th></th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                        @foreach($races as $race)
                            <tr>
                                <td data-label="@lang('races.index.name')" class="mdl-data-table__cell--non-numeric">
                                    <a href="{!! route('races.show', [$race->id]) !!}"> {!! $race->name !!} </a>
                                </td>
                                <td data-label="@lang('races.index.date')" class="mdl-data-table__cell--non-numeric">{{ $race->date_start }}</td>
                                <td data-label="@lang('races.index.hour')" class="mdl-data-table__cell--non-numeric">{{ $race->time_start }}</td>
                                <td data-label="@lang('races.index.laps')" >{{ $race->laps }}</td>
                                @auth
                                    <td scope="col">
                                        @if(!$race->locked)
                                            {!!Form::anchor(__('races.link.selectRacers'))->primary()->route('selectRacers', [$race->id])!!} 
                                        @endif 
                                        {!!Form::anchor(__('races.link.edit'))->secondary()->route('races.edit', [$race->id])!!} 
                                    
                                        {!!Form::open()->delete()->url("races/$race->id")!!}
                                            {!!Form::submit(__('races.link.delete'))->danger()!!}
                                        {!!Form::close()!!}
                                    </td>
                                @endauth
                            </tr>
                        @endforeach
                  </tbody>
            </table>
        </div>
    </div>
@endsection