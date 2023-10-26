<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User as UserModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterPost;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
      public function index()
    {
        return view('user.register');
    }


 public function register(UserRegisterPost $request)
    {
       // validate済みのデータの取得
        $datum = $request->validated();
          $datum['password'] = Hash::make($datum['password']);
        //
        //$user = Auth::user();
        //$id = Auth::id();
        //var_dump($datum, $user, $id); exit;
        // user_id の追加

        // テーブルへのINSERT
        try {
            $r = UserModel::create($datum);
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
            exit;

        }

 $request->session()->flash('front._register_success', true);
        // 一覧に遷移する
        return redirect('/');
    }




}
