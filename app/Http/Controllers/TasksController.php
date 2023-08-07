<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;

class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        // タスク一覧を収得
        $tasks = Task::all();
        
        // タスク一覧ビューでそれを表示
        return view('tasks.index', [
            'tasks' => $tasks,
            ]);
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
        
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // getでtasks/ (任意のid)にアクセスされた場合の「収得表示処理」
    public function show($id)
    {
        // idの値でタスクを検索して収得
        $task = Task::findOrFail($id);
        
        // タスク詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
            ]);
    }

    // getでtasks/　(任意のid) /edit にアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        //idの値でタスクを検索して収得
        $task = Task::findOrFail($id);
        
        // タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
            ]);
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
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // deleteでtasks/ (任意のid)にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        //idの値でメッセージを収得
        $task = Task::findOrFail($id);
        // メッセージを削除
        $task->delete();
        
        // トップページヘリダイレクトさせる
        return redirect('/');
    }
}
