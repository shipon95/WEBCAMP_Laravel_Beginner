<?php
declare(strict_types=1);
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRegisterPostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Task as TaskModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CompletedTask as CompletedTaskModel;

class TaskController extends Controller
{  /**

       /**
     * 一覧用の Illuminate\Database\Eloquent\Builder インスタンスの取得
     */
    protected function getListBuilder()
    {
        return TaskModel::where('user_id', Auth::id())
                     ->orderBy('priority', 'DESC')
                     ->orderBy('period')
                     ->orderBy('created_at');
    }
      /**
     * タスク一覧ページ を表示する
     *
     * @return \Illuminate\View\View
     */
    public function list()
    {
        // 1Page辺りの表示アイテム数を設定
        $per_page = 20;

        // 一覧の取得
        $list = TaskModel::where('user_id', Auth::id())
                         ->orderBy('priority', 'DESC')
                         ->orderBy('period')
                         ->orderBy('created_at')
                         ->paginate($per_page);
                        // ->get();
        $list = $this->getListBuilder()
                     ->paginate($per_page);
/*$sql = TaskModel::where('user_id', Auth::id())
                 ->orderBy('priority', 'DESC')
                 ->orderBy('period')
                 ->orderBy('created_at')
                 ->toSql();
$sql = $this->getListBuilder()
            ->toSql();
//echo "<pre>\n"; var_dump($sql, $list); exit;
var_dump($sql);
*/
        //
        return view('task.list', ['list' => $list]);
    }

    /**
     * タスクの新規登録
     */
    public function register(TaskRegisterPostRequest $request)
    {
        // validate済みのデータの取得
        $datum = $request->validated();
        //
        //$user = Auth::user();
        //$id = Auth::id();
        //var_dump($datum, $user, $id); exit;
        // user_id の追加
        $datum['user_id'] = Auth::id();
        // テーブルへのINSERT
        try {
            $r = TaskModel::create($datum);
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
            exit;
             // タスク登録成功

        }
         $request->session()->flash('front.task_register_success', true);
        // 一覧に遷移する
        return redirect('/task/list');
    } /**
     * CSV ダウンロード
     */
    public function csvDownload()
    {
        $data_list = [
            'id' => 'タスクID',
            'name' => 'タスク名',
            'priority' => '重要度',
            'period' => '期限',
            'detail' => 'タスク詳細',
            'created_at' => 'タスク作成日',
            'updated_at' => 'タスク修正日',
        ];

        /* 「ダウンロードさせたいCSV」を作成する */
        // データを取得する
        $list = $this->getListBuilder()->get();

        // バッファリングを開始
        ob_start();

        // 出力用のファイルハンドルを作成する
        $file = new \SplFileObject('php://output', 'w');
        // ヘッダを書き込む
        $file->fputcsv(array_values($data_list));
         // CSVをファイルに書き込む(出力する)
        foreach($list as $datum) {
            $awk = []; // 作業領域の確保
            // $data_listに書いてある順番に、書いてある要素だけを $awkに格納する
            foreach($data_list as $k => $v) {
                if ($k === 'priority') {
                    $awk[] = $datum->getPriorityString();
                } else {
                    $awk[] = $datum->$k;
                }
            }
            // CSVの1行を出力
            $file->fputcsv($awk);
        }

        // 現在のバッファの中身を取得し、出力バッファを削除する
        $csv_string = ob_get_clean();

        // 文字コードを変換する
        $csv_string_sjis = mb_convert_encoding($csv_string, 'SJIS', 'UTF-8');

        // ダウンロードファイル名の作成
        $download_filename = 'task_list.' . date('Ymd') . '.csv';
        // CSVを出力する
        return response($csv_string_sjis)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $download_filename . '"');


}}