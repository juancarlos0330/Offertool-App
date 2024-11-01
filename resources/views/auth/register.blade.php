@extends('layouts.app')
@section('content')
<div class="auth-card">
    <div class="flex flex-shrink-0 justify-center">
        <a href="{{ route('login') }}">
            <img class="responsive" src="{{ asset('img/logo.png') }}" alt="logo">
        </a>
    </div>

    @if(session('message'))
        <div class="alert success">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label class="block">
            <span class="text-gray-700 text-sm">{{ trans('global.login_name') }}</span>
            <input type="text" name="name" id="name" class="form-input {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" autofocus required>
            @if($errors->has('name'))
                <p class="invalid-feedback">{{ $errors->first('name') }}</p>
            @endif
        </label>

        <label class="block mt-3">
            <span class="text-gray-700 text-sm">{{ trans('global.login_email') }}</span>
            <input type="email" name="email" class="form-input {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" autofocus required>
            @if($errors->has('email'))
                <p class="invalid-feedback">{{ $errors->first('email') }}</p>
            @endif
        </label>

        <label class="block mt-3">
            <span class="text-gray-700 text-sm">{{ trans('global.login_password') }}</span>
            <input type="password" name="password" class="form-input{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
            @if($errors->has('password'))
                <p class="invalid-feedback">{{ $errors->first('password') }}</p>
            @endif
        </label>

        <label class="block mt-3">
            <span class="text-gray-700 text-sm">{{ trans('global.login_password_confirmation') }}</span>
            <input type="password" name="password_confirmation" class="form-input" required>
        </label>

        <div class="flex justify-center items-center mt-4">
            <div>
                <a class="link" href="{{ route('login') }}">Sign In Here</a>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="button">
                {{ trans('global.register') }}
            </button>
        </div>
    </form>
</div>
@endsection