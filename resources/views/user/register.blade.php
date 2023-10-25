@extends('layout')

{{-- メインコンテンツ --}}
@section('contets')
        <h1>`ユーザ登録</h1>
        @if (session('front.task_register_success') == true)
                ユーザを登録しました！！<br>
        @if ($errors->any())
            <div>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            </div>
        @endif
        <form action="/" method="post">
            @csrf
            名前：<input name="name" value="{{ old('name') }}"><br>
            email：<input name="email" value="{{ old('email') }}"><br>
            パスワード：<input  name="password" type="password"><br>
            <button>登録する</button><br>

        </form>
@endsection