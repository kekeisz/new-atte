@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="form__title">
        <h3 class="form__title-txt">
            会員登録
        </h3>
    </div>
    <form action="/register" class="form" method="post">
        @csrf
        <div class="form__input-name">
            <input type="text" class="form__input" name="name" value="{{ old('name') }}" placeholder="名前">
        </div>
        <div class="form__message-error-name">
            <p class="form__message-error">
                @error ('name')
                {{ $message }}
                @enderror
            </p>
        </div>
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
        <div class="form__input-password_confirmation">
            <input type="password" class="form__input" name="password_confirmation" value="" placeholder="確認用パスワード">
        </div>
        <div class="form__message-error-password_confirmation">
            <p class="form__message-error">
                @error ('password')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="form__button">
            <input class="form__button-submit" type="submit" value="会員登録">
        </div>
    </form>
    <div class="bottom-message">
        <div class="bottom-message__txt">
            <p>アカウントをお持ちの方はこちらから</p>
        </div>
        <div class="bottom-message__link">
            <a href="/login">ログイン</a>
        </div>
    </div>
</div>
@endsection