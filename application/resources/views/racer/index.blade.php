@extends('layouts.app')

@section('content')
<div class="container">
    @auth
        <div class="row mb-2">
            <div class="col">
                {!!Form::anchor(__('racers.link.create'))->info()->route('racers.create')!!}            
            </div>
        </div>
    @endauth
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">@lang('racers.index.name')</th>
                            <th scope="col">@lang('racers.index.points')</th>
                            @auth
                                <th scope="col"></th>
                                <th scope="col"></th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($racers as $index => $racer)
                            <tr>
                                <th scope="row">{{ ($index + 1) }}</th>
                                <td>{{ $racer->name }}</td>
                                <td>{{ $racer->points }}</td>
                                @auth
                                    <td scope="col">
                                        {!!Form::anchor(__('racers.link.edit'))->secondary()->route('racers.edit', [$racer->id])!!} 
                                    </td> 
                                    <td scope="col">
                                        {!!Form::open()->delete()->url("racers/$racer->id")!!}
                                            {!!Form::submit(__('racers.link.delete'))->danger()!!}
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