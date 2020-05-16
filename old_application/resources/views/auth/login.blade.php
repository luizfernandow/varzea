@extends('layouts.app')

@section('content')
<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <div class="login-card-wide mdl-card mdl-shadow--4dp">
            <div class="mdl-card__supporting-text">
                {{ __('Login') }}
            </div>
            <div class="mdl-card__supporting-text">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--12-col">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label {{ $errors->has('email') ? 'is-invalid' :'' }}">
                                <input class="mdl-textfield__input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                <label class="mdl-textfield__label" for="email">{{ __('E-Mail Address') }}</label>
                                @if ($errors->has('email'))
                                    <span class="mdl-textfield__error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--12-col">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label { $errors->has('password') ? 'is-invalid' :'' }}">
                                <input class="mdl-textfield__input" type="password" id="password" name="password" value="{{ old('password') }}" required>
                                <label class="mdl-textfield__label" for="password">{{ __('Password') }}</label>
                                @if ($errors->has('password'))
                                    <span class="mdl-textfield__error">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--12-col">
                            <label for="chkbox1" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
                              <input type="checkbox" id="chkbox1" class="mdl-checkbox__input" name="remember" {{ old('remember') ? 'checked' : '' }}>
                              <span class="mdl-checkbox__label">{{ __('Remember Me') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--6-col mdl-cell--6-col-phone">
                            <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                                {{ __('Login') }}
                            </button>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col mdl-cell--6-col-phone">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>        
    </div>
</div>
@endsection
