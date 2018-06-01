@extends('layouts.app')

@section('content')
<div class="container">
    @auth
        <div class="row mb-2">
            <div class="col">
                {!!Form::anchor(__('races.link.create'))->info()->route('races.create')!!}            
            </div>
        </div>
    @endauth
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">@lang('races.index.name')</th>
                            <th scope="col">@lang('races.index.date')</th>
                            <th scope="col">@lang('races.index.hour')</th>
                            <th scope="col">@lang('races.index.laps')</th>
                            @auth
                                <th scope="col"></th>
                                <th scope="col"></th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($races as $race)
                            <tr>
                                <td>
                                    <a href="{!! route('races.show', [$race->id]) !!}"> {!! $race->name !!} </a>
                                </td>
                                <td>{{ $race->date_start }}</td>
                                <td>{{ $race->time_start }}</td>
                                <td>{{ $race->laps }}</td>
                                @auth
                                    <td scope="col">
                                        {!!Form::anchor(__('races.link.edit'))->secondary()->route('races.edit', [$race->id])!!} 
                                    </td> 
                                    <td scope="col">
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
    </div>
</div>
@endsection