@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {!!Form::open()->route('races.store')!!}
                {!!Form::text('name', __('races.form.name'))!!}  
                {!!Form::text('date', __('races.form.date'))!!}  
                {!!Form::text('hour', __('races.form.hour'))!!}                
                {!!Form::text('laps', __('races.form.laps'))->type('number')!!}
                {!!Form::submit(__('races.form.submit'))!!}
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection