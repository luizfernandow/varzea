@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{!! $race->name !!}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{!! $race->date_start !!} {!! $race->time_start !!}</h6>
      </div>
    </div>
     <div class="row justify-content-center">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">@lang('races.show.name')</th>
                            <th scope="col">@lang('races.show.time')</th>
                            <th scope="col">@lang('races.show.point')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($race->getRank() as $index => $laps)
                            <tr>
                                <th scope="row">{{ ($index + 1) }}</th>
                                <td>{{ $laps->racer->name }}</td>
                                <td>{{ ($laps->laps == $race->laps) ? $laps->time : '-' }}</td>
                                <td>
                                    @if ($laps->laps == $race->laps)
                                        @base_point($index)
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                  </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection