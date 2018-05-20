@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {!!Form::open()->route('racers.store')!!}
                {!!Form::text('name', __('racers.form.name'))!!}  
                {!!Form::submit(__('racers.form.submit'))!!}
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection