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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Date</th>
                        <th scope="col">Hour</th>
                        <th scope="col">Laps</th>
                        @auth
                            <th scope="col"></th>
                            <th scope="col"></th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach($races as $race)
                        <tr>
                            <td>{{ $race->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($race->date_start)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($race->time_start)->format('H:i') }}</td>
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
@endsection