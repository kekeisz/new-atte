@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
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
    <div class="date">
        <form class="form__pre-date" method="GET" action="/attendance/{{ $previousDate }}">
            @csrf
            <div class="date-navigation">
                <button type="submit" class="btn__previous ">
                    <span class="txt__previous"><</span>
                </button>
            </div>
        </form>

        <span class="list-date">{{ $date ?? '' }}</span>

        <form class="form__next-date" method="GET" action="/attendance/{{ $nextDate }}">
            @csrf
            <div class="date-navigation">
                <button type="submit" class="btn__next">
                    <span class="txt__next">></span>
                </button>
            </div>
        </form>
    </div>
    <table class="table">
        <tr class="table__row">
            <th>名前</th>
            <th>勤務開始</th>
            <th>勤務終了</th>
            <th>休憩時間</th>
            <th>勤務時間</th>
        </tr>
        @foreach ($attendances as $attendance)
        <tr class="table__row">
            <td>
                <a class="table__name-link" href="/personal/{{$attendance->id}}">{{ $attendance->name }}</a>
            </td>
            <td>{{ $attendance->work_in }}</td>
            <td>{{ $attendance->work_out }}</td>
            <td>{{ $attendance->total_rest_time }}</td>
            <td>{{ $attendance->total_work_time }}</td>
        </tr>
        @endforeach
    </table>
    <div class="pagination">
        <div>{{ $attendances->links() }}</div>
    </div>
</div>
@endsection