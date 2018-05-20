@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {!!Form::open()->route('racers.update',[$racer->id])->put()->fill($racer)!!}
                {!!Form::text('name', __('racers.form.name'))!!}  
                {!!Form::submit(__('racers.form.save'))!!}
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection