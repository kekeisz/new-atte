@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
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
    <div class="title">
        <h1 class="title__txt">
            従業員一覧
        </h1>
    </div>
    <table class="table">
        <tr class="table__row">
            <th class="table__header-id">ID</th>
            <th class="table__header-name">名前</th>
        </tr>
        @foreach ($users as $user)
        <tr class="table__row">
            <td class="table__user-id">{{ $user->id }}</td>
            <td class="table__user-name">{{ $user->name }}</td>
            <td class="table__user-attendance">
                <form action="/personal/{{ $user->id }}" class="button__attendance" method="get">
                    <button class="btn__show-attendance">勤怠データを見る</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection