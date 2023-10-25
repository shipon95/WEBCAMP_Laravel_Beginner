@extends('layout')

{{-- タイトル --}}
@section('title')(詳細画面)@endsection

{{-- メインコンテンツ --}}
@section('contets')
        <h1>完了タスクの一覧</h1>

        <table border="1">
        <tr>
            <th>タスク名
            <th>期限
            <th>重要度
             <th>タスク完了日
@foreach ($list as $completed_tasks)
        <tr>
            <td>{{ $completed_tasks->name }}
            <td>{{ $completed_tasks->period }}
            <td>{{ $completed_tasks->getPriorityString() }}
            <td>{{ $completed_tasks->created_at}}

@endforeach
        </table>
        <!-- ページネーション -->
        {{-- {{ $list->links() }} --}}
        現在 {{ $list->currentPage() }} ページ目<br>
        @if ($list->onFirstPage() === false)
            <a href="/completed_tasks/list">最初のページ</a>
        @else
            最初のページ
        @endif
        /
        @if ($list->previousPageUrl() !== null)
            <a href="{{ $list->previousPageUrl() }}">前に戻る</a>
        @else
            前に戻る
        @endif
        /
        @if ($list->nextPageUrl() !== null)
            <a href="{{ $list->nextPageUrl() }}">次に進む</a>
        @else
            次に進む
        @endif
        <br>
        <hr>
        <menu label="リンク">
        <a href="/logout">ログアウト</a><br>
        </menu>
@endsection