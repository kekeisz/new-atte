@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('menu')
<div class="menu__box">
    <div class="menu">
        <a href="/" class="menu__home">ホーム</a>
    </div>
    <div class="menu">
        <a href="/attendance" class="menu__attendance">日付一覧</a>
    </div>
    <div class="menu">
        <a href="/user" class="menu__user">従業員一覧</a>
    </div>
    <div class="menu">
        <form action="/logout" class="menu__logout" method="post">
            @csrf
            <button>ログアウト</button>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="content">
    <div class="form__title">
        <h3 class="form__title-txt">
            {{ $user }}さん、お疲れ様です！
        </h3>
    </div>
    <div class="form">
        <div class="form__input">
            <div class="button__work">
                <form action="/workin" class="button" method="get">
                    @csrf
                    <button class="button__item"
                        @if($status != 'not_working') disabled @endif>
                        勤務開始
                    </button>
                </form>
                <form action="/workout" class="button" method="get">
                    @csrf
                    <button class="button__item"
                        @if($status != 'working') disabled @endif>
                        勤務終了
                    </button>
                </form>
            </div>
            <div class="button__break">
                <form action="/reststart" class="button" method="get">
                    @csrf
                    <button class="button__item"
                        @if($status != 'working') disabled @endif>
                        休憩開始
                    </button>
                </form>
                <form action="/restend" class="button" method="get">
                    @csrf
                    <button class="button__item"
                        @if($status != 'resting') disabled @endif>
                        休憩終了
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection