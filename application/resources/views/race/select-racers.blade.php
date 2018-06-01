@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('races.selectRacers.header')</h5>
                    {!!Form::open()->route('startRace', [$id])!!}
                        @foreach ($racers as $racerId => $racer)
                            <div class="btn-group-toggle mb-2" data-toggle="buttons">
                              <label class="btn btn-outline-primary btn-lg btn-block">
                                <input type="checkbox" name="racers[]" value="{{$racerId}}" autocomplete="off"> {{$racer}}
                              </label>
                            </div>
                        @endforeach
                        {!!Form::submit(__('races.selectRacers.submit'))!!}
                    {!!Form::close()!!}                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection