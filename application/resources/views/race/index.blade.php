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
                    </tr>
                </thead>
                <tbody>
                    @foreach($races as $race)
                        <tr>
                            <td>{{ $race->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($race->date_start)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($race->time_start)->format('H:i') }}</td>
                            <td>{{ $race->laps }}</td>
                        </tr>
                    @endforeach
              </tbody>
            </table>
        </div>
    </div>
</div>
@endsection