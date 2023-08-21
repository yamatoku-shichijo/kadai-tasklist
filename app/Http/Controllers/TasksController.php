<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        $data = [];
        if(\Auth::check()) { //認証済みの場合
            //認証済みユーザを収得
            $user = \Auth::user();
            // タスク一覧を収得 変更した
            $tasks = $user->tasks()->orderBy('id', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
                ];
        
        }
        
        // タスク一覧ビューでそれを表示
        return view('dashboard', $data);
    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;
        
        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
            ]);
        
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリテーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
            ]);
        
        // $task = new Task;
        // $task->user_id = \Auth::user();
        // $task->status = $request->status;
        // $task->content = $request->content;
        // $task->save();
        
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);
        
        // トップページへリダイレクトさせる
        return redirect( route('tasks.index') ); //redirect('/')から変えた
    }

    // getでtasks/ (任意のid)にアクセスされた場合の「収得表示処理」
    public function show($id)
    {
        // idの値でタスクを検索して収得
        $task = Task::findOrFail($id);
        
        // タスク詳細ビューでそれを表示
        if(\Auth::id() === $task->user_id) {
        return view('tasks.show', [
            'task' => $task,
            ]);
        }
        
        return redirect('/');
    }

    // getでtasks/　(任意のid) /edit にアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        //idの値でタスクを検索して収得
        $task = Task::findOrFail($id);
        
        // タスク編集ビューでそれを表示
        if(\Auth::id() === $task->user_id) {
        return view('tasks.edit', [
            'task' => $task,
            ]);
        }
        
        return redirect('/');
    }

    // putまたはpatchでtasks/ (任意のid)にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
            ]);
        
        //idの値でタスクを検索して収得
        $task = Task::findOrFail($id);
        // タスクを更新
        if(\Auth::id() === $task->user_id) {
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        // トップページへリダイレクトさせる
        return redirect( route('tasks.index') ); //redirect('/')から変えた
        }
        
        return redirect('/');
    }

    // deleteでtasks/ (任意のid)にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        //idの値でタスクを収得
        $task = Task::findOrFail($id);
        // メッセージを削除
        if(\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect( route('tasks.index') )
                ->with('success', 'Delete Successful');
        }
 
        
        // トップページヘリダイレクトさせる
        return redirect( route('/') )//tasklist.indexから変えたが多分変わらない
            ->with('Delete Failed'); //redirect('/')から変えた
    }
}
