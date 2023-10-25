<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CompletedTask as CompletedTaskModel;
use Illuminate\Support\Facades\DB;


class CompletedTaskController extends Controller
{

   protected function getListBuilder()
    {
        return CompletedTaskModel::where('user_id', Auth::id())
                     ->orderBy('priority', 'DESC')
                     ->orderBy('period')
                     ->orderBy('created_at');
    }
  public function list()
    {

        // 1Page辺りの表示アイテム数を設定
        $per_page = 20;

        // 一覧の取得
       $list = CompletedTaskModel::where('user_id', Auth::id())
                         ->orderBy('priority', 'DESC')
                         ->orderBy('period')
                         ->orderBy('created_at')
                         ->paginate($per_page);

        $list = $this->getListBuilder()
                     ->paginate($per_page);
        return view('task.completed_list', ['list' => $list]);
    }
}
