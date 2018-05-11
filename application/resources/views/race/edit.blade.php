@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {!!Form::open()->route('races.update',[$race->id])->put()->fill($race)!!}
                {!!Form::text('name', __('races.form.name'))!!}  
                {!!Form::text('date_start', __('races.form.date'))!!}  
                {!!Form::text('time_start', __('races.form.hour'))!!}                
                {!!Form::text('laps', __('races.form.laps'))->type('number')!!}
                {!!Form::submit(__('races.form.save'))!!}
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection