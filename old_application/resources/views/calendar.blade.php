@extends('layouts.app')

@section('title', __('welcome.calendar'))

@section('content')
<div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col padding-10" style="height: calc(100vh - 80px);">
	<iframe width="100%" height="100%" src="https://calendar.google.com/calendar/embed?src=cv3mmahedsdpuerd1r4t9kh2q8@group.calendar.google.com&ctz=America/Sao_Paulo&pli=1">
		
	</iframe>

</div>
@endsection