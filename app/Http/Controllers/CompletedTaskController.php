<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompletedTask as CompletedTaskModel;

class CompletedTaskController extends Controller
{
  public function list()
    {

        // 1Page辺りの表示アイテム数を設定
        $per_page = 20;

        // 一覧の取得
        $list = $this->getListBuilder()
                     ->paginate($per_page);
        return view('completed_tasks.list');
    }
}
