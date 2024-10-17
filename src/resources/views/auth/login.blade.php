@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="form__title">
        <h3 class="form__title-txt">
            ログイン
        </h3>
    </div>
    <form action="/login" class="form" method="post">
        @csrf
        <div class="form__input-email">
            <input type="email" class="form__input" name="email" value="{{ old('email') }}" placeholder="メールアドレス">
        </div>
        <div class="form__message-error-email">
            <p class="form__message-error">
                @error ('email')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="form__input-password">
            <input type="password" class="form__input" name="password" value="" placeholder="パスワード">
        </div>
        <div class="form__message-error-password">
            <p class="form__message-error">
                @error ('password')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="form__button">
            <input class="form__button-submit" type="submit" value="ログイン">
        </div>
    </form>
    <div class="bottom-message">
        <div class="bottom-message__txt">
            <p>アカウントをお持ちでない方はこちらから</p>
        </div>
        <div class="bottom-message__link">
            <a href="/register">会員登録</a>
        </div>
    </div>
</div>
@endsection