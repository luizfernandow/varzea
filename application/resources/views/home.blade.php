@extends('layouts.app')

@section('content')
<div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    You are logged in!
</div>
@endsection
